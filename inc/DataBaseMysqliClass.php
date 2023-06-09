<?php

/**
 * DataBaseMysqliClass.php
 *  by Zac Herman
 *  2015.jan.07
 *  This class will do all the standard DB stuff for mysql on php
 *    @author Zac Herman
 *  @copyright 2015
 *
 */
//////////////////////////////////////////////////////////////////////////////////////

//Start Class

class DataBaseMysqli
{
    public $DBname = ""; //db name
    public $DBhost = "";
    public $DBuser = ""; //db user id
    public $DBpass = ""; //password
    public $UserAllowed = true;

    public $DBconn; // Connection to mysql
    public $rst; // Query result
    public $numOfRows; // Query result
    public $numOfFields; // Query result
    public $DBdata; // Data from result in array

    /**
     * Constructor which prepares db connection for us
     *
     */
    public function __construct()
    {
        if (file_exists(basename(dirname(__FILE__)) . "/cred.txt")) {
            $cred = parse_ini_file(basename(dirname(__FILE__)) . "/cred.txt");
            $this->DBname = $cred["dbName"]; //db name
            $this->DBuser = $cred["dbUser"]; //db user id 
            $this->DBpass = $cred["dbDrowssap"]; //password
            $this->DBhost = $cred["dbHost"];
        }
    }

    //=======================================
    // Function:    dbConnect
    // Parameters:  database name, database username, database password, database host
    // Return Type: 0 for success, 1 for error
    // Description: connect to database, and select
    //=======================================

    public function connect($name = "", $user = "", $pass = "", $host = "")
    {
        // Use the pass in parms if set
        if ($name != "") {
            $this->DBname = $name;
        }

        if ($user != "") {
            $this->DBuser = $user;
        }

        if ($pass != "") {
            $this->DBpass = $pass;
        }

        if ($host != "") {
            $this->DBhost = $host;
        }

        $this->DBconn = new mysqli($this->DBhost, $this->DBuser, $this->DBpass, $this->DBname);

        // Check connection
        if ($this->DBconn->connect_error) {
            die("Connection failed: " . $this->DBconn->connect_error);
        }

        return "connected";
    } // end zDataBase constructor
    //---------------------------------------------------------------------------------

    //==========================================
    // Function: dbClose
    // Parameters:  none
    // Return Type: 0 for success
    // Description: Close db conn
    //==========================================
    public function close()
    {
        $this->DBconn->close();

    } // end dbClose
    //////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////////////////////////
    //==========================================
    // Function:    rawQuery
    // Parameters:  sql
    // Return Type: raw result set
    // Description: executes sql statement
    //==========================================
    public function rawQuery($sql)
    {

        if (!$this->DBconn) {
            $this->connect();
        }

        $sql = trim($sql);

        return $this->DBconn->query($sql);
    }

    //==========================================
    // Function:    dbQuery
    // Parameters:  sql
    // Return Type: none
    // Description: executes sql statement
    //==========================================
    public function query($sql, $dataType = MYSQLI_ASSOC)
    {

        if (!$this->DBconn) {
            $this->connect();
        }

        $sql = trim($sql);

        if ($this->rst = $this->DBconn->query($sql)) {
            $sqlKeyWords = "SELECT|SHOW|EXPLAIN|DESCRIBE";
            if (preg_match("/$sqlKeyWords/i", $sql)) {
                $ii = 0;
                $data = array();
                while ($row = $this->rst->fetch_array($dataType)) {
                    $data[$ii] = $row;
                    $ii++;
                }
                $this->numOfRows = $this->rst->num_rows;
                $this->numOfFields = $this->rst->field_count;
                $this->DBdata = $data;
            } else {
                // No results from query since it was insert/delete/update
                return 0;
            }
        } else {
            // No results so check the rst value
            if ($this->rst === true) {
                return 0;
            } else {
                if ($this->rst === false) {
                    return -1;
                } else {
                    return $this->rst;
                }

            }
        }

        return 0; // all good
    } // end query

    //==========================================
    // Function:    jsonQuery
    // Parameters:  query
    // Return Type: json string
    // Description: returns data in json using query
    //==========================================

    public function jsonQuery($query, $dataType = MYSQLI_ASSOC)
    {
        $output = "";
        $result = $this->query($query, $dataType);
        $results = $this->getResults();
        $numRows = $this->getNumberOfRows();
        $jsonresult = json_encode($results);
        // if($numRows > 0)
        // {
        //     $output .= '({"total":"'.$numRows.'","results":'.$jsonresult.'})';
        // } else {
        //     $output .= '({"total":"0", "results":""})';
        // }
        return $jsonresult;
        //    return $output;

    } // end jsonQuery

    //==========================================
    // Function:    dbGetResults
    // Parameters:  none
    // Return Type: array
    // Description: returns data from pervious query
    //==========================================

    public function getResults()
    {
        return $this->DBdata;
    } // end dbGetResults

    //-------------------------------------------------------------------------

    //==========================================================================
    // Function:    dbGetTableData
    // Parameters:  table
    // Return Type: array with table info
    // Description: returns info about table
    //==========================================================================

    public function getTableData($tbl)
    {
        if (!$this->query("SHOW COLUMNS From $tbl")) {
            return $this->getResults();
        } else {
            return 1;
        }
        //error

    } //end func dbGetTableData($tbl)
    //-------------------------------------------------------------------------

    public function getNumberOfRows()
    {
        return $this->numOfRows;
    }

    public function getNumberOfFields()
    {
        return $this->numOfFields;
    }

    public function fetchAssocResults($stmt)
    {
        if ($stmt->num_rows > 0) {
            $result = array();
            $md = $stmt->result_metadata();
            $params = array();
            while ($field = $md->fetch_field()) {
                $params[] = &$result[$field->name];
            }
            call_user_func_array(array($stmt, 'bind_result'), $params);
            if ($stmt->fetch())
                return $result;
        }
        return null;
    }

    public function getPreparedResults($stmt)
    {
        $results = array();
        if ($stmt) {
            $stmt->execute();
            $stmt->store_result();
            while ($assoc_array = $this->fetchAssocResults($stmt)) {
                $results[] = $assoc_array;
            }
            $stmt->close();
        }
        return $results;
    }
} // end DataBaseMysqliClass
