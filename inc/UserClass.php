<?php
require_once ('DataBaseMysqliClass.php');

class UserClass extends DataBaseMysqli {

var $user_tbl = "user";

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

function setUserTable($tablename)
{
	$this->user_tbl = $tablename;
}

function getUserTable()
{
	return $this->user_tbl;
}

public function getUsers($_id=null, $_arg = null)
{
    $query = "SELECT tbl.userId, tbl.username, tbl.fullname, tbl.email, tbl.status FROM user tbl";
    if (($_id == null) && ($_arg == null)) {
       $query .= " ORDER BY userId";
       $stmt = $this->DBconn->prepare($query);
    } else if (($_id != null) && ($_arg == null)){
       $query .= " WHERE userId=?";
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
        if (in_array($key, ["fullname", "username", "email", "status"])) 
            $bind .= "s";
        else if (in_array($key, ["userId"]))
                $bind .= "i";
    } //end foreach on args
    $whereClause = " WHERE " . implode(" AND ", $where);
    $results["where"] = $whereClause;
    $results["bind"] = $bind;
    $results["params"] = $params;
    return $results;
}

function saveUser($_data, $_id = null)
{

	$_tbl = $this->getUserTable();
	if (!$this->UserAllowed) {
		return "ERROR: Unable to confirm authorization";
	}
	if ($_id == null) {
		$action = "INSERT INTO ";
	} else {
		$action = "UPDATE ";
	}
	if (($_data["username"] != NULL) && ($_data["fullname"] != NULL) && ($_data["email"] != NULL) )  // at least the name/desc must be set
	{
		// return html("Testing stmt");
		$sql_update = "$action $_tbl SET fullname = ?, username = ?, status = ?, email = ?";
		if ($_id != null) {
			$sql_update .= " WHERE userId=$_id";
		}
		$stmt = $this->DBconn->prepare($sql_update);
		if ($stmt == false) {
			return "Error with $action: $sql_update";
		}
		
		$stmt->bind_param("ssss", 
			$_data["fullname"],
			$_data["username"],
			$_data["status"],
			$_data["email"]
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
    
} // end saveUser

function deleteUser($_data, $_id)
{
	 $_tbl = $this->getUserTable();
    if ($this->UserAllowed)  // PW must be correct
    {
        $sql_update = "UPDATE $_tbl SET status = 'disabled' WHERE userId=$_id";
        $update = $this->query($sql_update);
    }
}

function search($_arg)
{
    return $this->getUsers(null,$_arg);
}


} //end class User
