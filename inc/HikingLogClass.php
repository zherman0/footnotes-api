<?php
require_once ('DataBaseMysqliClass.php');

class HikingLogClass extends DataBaseMysqli {

var $hike_log_tbl = "hikingLog";

/**
 * Constructor which creates db connection for us
 *
 */
function __construct() 
{
	parent::__construct();
	if (is_null($this->DBconn))
	{
		$this->connect();
	}
	
}

function setHikingLogTable($tablename)
{
	$this->hike_log_tbl = $tablename;
}

function getHikeLogTable()
{
	return $this->hike_log_tbl;
}

public function getHikes($_id=null, $_arg = null)
{
	$query = "SELECT tbl.hikeId, tbl.userId, tbl.locationId, tbl.hikeDate, tbl.description FROM hikingLog tbl";
	if (($_id == null) && ($_arg == null)) {
       $query .= " ORDER BY hikeDate";
       $stmt = $this->DBconn->prepare($query);
	} else if (($_id != null) && ($_arg == null)){
       $query .= " where hikeId=?";
       $stmt = $this->DBconn->prepare($query);
       $stmt->bind_param("i",$_id);
	} else if ($_arg != null) {
        $results = $this->getWhereClause($_arg); 
        $query .= $results["where"];
        $stmt = $this->DBconn->prepare($query);
        // error_log("Look at stuff");
        // error_log(print_r($query, true));
        // error_log(print_r($results["bind"], true));
        // error_log(print_r($results["params"], true));
        $stmt->bind_param($results["bind"],...$results["params"]); 
    }
//    return $query;
   return $this->getPreparedResults($stmt);
}

function saveHike($_data, $_id = null)
{
	$_tbl = $this->getHikeLogTable();
	if (!$this->UserAllowed) {
		return "ERROR: Unable to confirm authorization";
	}
	if ($_id == null) {
		$action = "INSERT INTO ";
	} else {
		$action = "UPDATE ";
	}

	if (($_data["userId"] != NULL) && ($_data["locationId"] != NULL) )  // at least the user and location must be set
	{
		$sql_update = "$action $_tbl SET userId = ?, locationId = ?, description = ?, hikeDate = ?";
		if ($_id != null) {
			$sql_update .= " WHERE userId=$_id";
		}
		$stmt = $this->DBconn->prepare($sql_update);
		if ($stmt == false) {
			return "Error with $action: $sql_update";
		}
		$stmt->bind_param("iisS", 
			$_data["userId"],
			$_data["locationId"],
			substr($_data["description"], 0, 64000),
			$_data["hikeDate"]
		);

		// update the table
		$update = $stmt->execute();
		if ($update) {
				$msg = "Successful: hike saved at " . time(); // All is good
		} else {
				$msg = "ERROR: An error occurred adding entry: " . $stmt->error;
		}
	} else {
		$msg = "ERROR: saveEntry: User and location must be set.";
	}
	return $msg;
    
} // end saveHike

function deleteHike($_data, $_id)
{
	 $_tbl = $this->getHikeLogTable();
    if ($this->UserAllowed)  
    {
        $sql_update = "DELETE FROM $_tbl WHERE hikeId=$_id";
        $update = $this->query($sql_update);
    }
}

function getWhereClause($_arg)
{
    $where = [];
    $params = [];
    $bind = "";
    foreach ($_arg as $key=>$value) {
        $where[]="$key = ?";
        $params[]=$value;
        if (in_array($key, ["description","hikeDate"])) 
            $bind .= "s";
        else if (in_array($key, ["locationId","userId","hikeId"]))
                $bind .= "i";
    } //end foreach on args
    $whereClause = " WHERE " . implode(" AND ", $where);
    $results["where"] = $whereClause;
    $results["bind"] = $bind;
    $results["params"] = $params;
    return $results;
}

function search($_arg)
{
    return $this->getHikes(null,$_arg);
}



} //end class HikeLog
