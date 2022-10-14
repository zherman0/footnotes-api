<?php
require_once ('DataBaseMysqliClass.php');

class LocationsClass extends DataBaseMysqli {

var $locations_tbl = "hikingLocations";

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

function setLocationsTable($tablename)
{
	$this->locations_tbl = $tablename;
}

function getLocationsTable()
{
	return $this->locations_tbl;
}

public function getLocations($_id=null, $_arg=null)
{
    $query = "SELECT tbl.locationId, tbl.name, tbl.description, tbl.directions, tbl.last_updated, tbl.status FROM hikingLocations tbl";
    if (($_id == null) && ($_arg == null)) {
       $query .= " WHERE status = 'enabled' ORDER BY locationId";
       $stmt = $this->DBconn->prepare($query);
    } else if (($_id != null) && ($_arg == null)){
       $query .= " WHERE locationId=?";
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

function getWhereClause($_arg)
{
    $where = [];
    $params = [];
    $bind = "";
    foreach ($_arg as $key=>$value) {
        $where[]="$key = ?";
        $params[]=$value;
        if (in_array($key, ["name", "description","directions", "status"], "last_updated")) 
            $bind .= "s";
        else if (in_array($key, ["locationId"]))
                $bind .= "i";
    } //end foreach on args
    $whereClause = " WHERE " . implode(" AND ", $where);
    $results["where"] = $whereClause;
    $results["bind"] = $bind;
    $results["params"] = $params;
    return $results;
}

function saveLocation($_data, $_id = null)
{
	$_tbl = $this->getLocationTable();
	if (!$this->UserAllowed) {
		return "ERROR: Unable to confirm authorization";
	}
    if ($_id == null) {
		$action = "INSERT INTO ";
	} else {
		$action = "UPDATE ";
	}

	if (($_data["name"] != NULL) && ($_data["description"] != NULL) && ($_data["directions"] != NULL) )  // at least the name/desc must be set
	{
		$sql_update = "$action $_tbl SET name = ?, description = ?, directions = ?, status = ?";
		
		$stmt = $this->DBconn->prepare($sql_update);
		if ($stmt == false) {
			return "Error with $action: $sql_update";
		}
        if ($_id != null) {
			$sql_update .= " WHERE locationId=$_id";
		}
        $stmt->bind_param("ssss", 
			$_data["name"],
			$_data["description"],
			$_data["directions"],
			$_data["status"]
		);	

		// update the table
		$update = $stmt->execute();
		if ($update) {
            $msg = "Successful: user saved at " . time(); // All is good
        } else {
                $msg = "ERROR: An error occurred adding user: " . $stmt->error;
        }
    } else {
        $msg = "ERROR: saveEntry: Username, full name and email must be set.";
    }
    return $msg;
    
} // end saveSchema

function deleteLocation($_data, $_id)
{
	 $_tbl = $this->getLocationTable();
    if ($this->UserAllowed) 
    {
        $sql_update = "UPDATE $_tbl SET status = 'disabled' WHERE locationId=$_id";
        $update = $this->query($sql_update);
    }
}

function search($_arg)
{
    return $this->getLocations(null,$_arg);
}



} //end class Schema
