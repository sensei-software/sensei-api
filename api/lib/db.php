<?php
include_once(dirname(__FILE__)."/../config.php");

function myConnect($mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){
	if(is_null($mysql_host)) $mysql_host=$GLOBALS["MYSQL_HOST"];
	if(is_null($mysql_db)) $mysql_db=$GLOBALS["MYSQL_DB"];
	if(is_null($mysql_user)) $mysql_user=$GLOBALS["MYSQL_USR"];
	if(is_null($mysql_pass)) $mysql_pass=$GLOBALS["MYSQL_PASS"];	
	$conn = mysqli_connect("p:".$mysql_host, $mysql_user, $mysql_pass,$mysql_db);
	if (!$conn) {
            throw new Exception('Could not connect: ' . mysqli_error($conn));
        }  else {
		return $conn;
	}
}
	

function myQuery($sql, $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	$data=null;
        $conn = myConnect($mysql_host, $mysql_db, $mysql_user, $mysql_pass);
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            throw new Exception('Invalid query: ' . mysqli_error($conn)." FULL QUERY: ".$sql);            
        }       
	return $result;
}


function myQueryU($sql, $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	$data=null;
        $conn = myConnect($mysql_host, $mysql_db, $mysql_user, $mysql_pass);
	$r= mysqli_query($conn, "SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED ;");
        $result = mysqli_query($conn, $sql);
	$r= mysqli_query($conn, "SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ  ;");
        if (!$result) {
            throw new Exception('Invalid query: ' . mysqli_error($conn)." FULL QUERY: ".$sql);            
        }       
	return $result;
}


function executeMultiSql($sql,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	//echo $sql;
	$data=null;
        $conn = myConnect($mysql_host, $mysql_db, $mysql_user, $mysql_pass);        
        mysqli_multi_query($conn, $sql);
	$results=null;
	$i=0;
	do {
	    if($result = mysqli_store_result($conn)){		
		mysqli_free_result($result);
	    }
	    $results[$i]=$result;
	    $i++;
	} while(mysqli_next_result($conn));
	if(mysqli_error($conn)) {
	    throw new Exception('ERROR: ' . mysqli_error($conn)."\n FULL QUERY: ".$sql);            
	}
}

function readTable($sql,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	$data = null;
	$i=0;
        $result=myQuery($sql, $mysql_host, $mysql_db, $mysql_user, $mysql_pass);
        if($result) {
                while($row=mysqli_fetch_array($result, MYSQL_BOTH)){
                        $data[$i]=$row;
                        $i++;
                }
                return $data;
        }
        else
                throw new Exception("error"); 
}
function readTableU($sql,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){		
	$data = null;
	$i=0;
        $result=myQueryU($sql, $mysql_host, $mysql_db, $mysql_user, $mysql_pass);
        if($result) {
                while($row=mysqli_fetch_array($result, MYSQL_BOTH)){
                        $data[$i]=$row;
                        $i++;
                }
                return $data;
        }
        else
                throw new Exception("error"); 
}
function readArray($sql,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
        $result=myQuery($sql, $mysql_host, $mysql_db, $mysql_user, $mysql_pass);        
        $i=0;
        if($result) {
                $data = Array();
                while($row=mysqli_fetch_array($result, MYSQL_ASSOC)){
                        $data[$i]=$row;
                        $i++;
                }
                return $data;
        }
        else
                throw new Exception("error"); 
}
function readRecord($sql,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	$data=readArray($sql, $mysql_host, $mysql_db, $mysql_user, $mysql_pass);    
	if(sizeof($data)>0)
		return $data[0];
}

function readArrayVertical($sql,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
        $result=myQuery($sql, $mysql_host, $mysql_db, $mysql_user, $mysql_pass);        
        $i=0;
        if($result) {
                $data = Array();
                while($row=mysqli_fetch_array($result, MYSQL_NUM)){
                        $data[$i]=$row[0];
                        $i++;
                }
                return $data;
        }
        else
                throw new Exception("error"); 
}

function readScalar($sql,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	

        $data=readTable($sql, $mysql_host, $mysql_db, $mysql_user, $mysql_pass);  
	//print_r(Array($sql,$data,$data[0][0]));	
        return $data[0][0];
        
}

function executeUpdateSql($sql,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	$data=null;
        $conn = myConnect($mysql_host, $mysql_db, $mysql_user, $mysql_pass);        
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            throw new Exception('Invalid query: ' . mysqli_error($conn)." FULL QUERY: ".$sql);            
        }       
        $rows=mysqli_affected_rows($conn);
	return $rows;

}

function executeSql($sql,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	$data=null;
        $conn = myConnect($mysql_host, $mysql_db, $mysql_user, $mysql_pass);        
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            throw new Exception('Invalid query: ' . mysqli_error($conn)." FULL QUERY: ".$sql);            
        }       
	$id=mysqli_insert_id($conn);
        if($result) 
                return $id;
        else
                throw new Exception("error"); 
}



function mysql_escape($string,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	$conn = myConnect($mysql_host, $mysql_db, $mysql_user, $mysql_pass);	
	$res=mysqli_real_escape_string($conn,$string);
	return $res;
				
}

function existsDatabase($dbName,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'";
	$data = readScalar($sql, $mysql_host, $mysql_db, $mysql_user, $mysql_pass); 
	return $data;
}
function existsTable($tableName,  $mysql_host=null, $mysql_db=null, $mysql_user=null, $mysql_pass=null){	
	$sql = "
	SELECT table_name
		FROM information_schema.tables    
		WHERE table_type = 'BASE TABLE' 
			AND table_schema='".$GLOBALS["MYSQL_DB"]."'  
			AND table_name ='".$tableName."'
		ORDER BY table_name DESC
		LIMIT 1
	";
	$data = readScalar($sql);
	return $data;
}

function lastTableName($tableName){
	$sql = "
	SELECT table_name
		FROM information_schema.tables    
		WHERE table_type = 'BASE TABLE' 
			AND table_schema='".$GLOBALS["MYSQL_DB"]."'  
			AND table_name LIKE '".$tableName."_%'
		ORDER BY table_name DESC
		LIMIT 1
	";
	$data = readScalar($sql);
	return $data;
}
function lastTablesInfo($tableName,$limit=3){
	$sql = "
	SELECT table_name, table_rows, 'rows', CONVERT(data_length/1024/1024,DECIMAL(4,2)), 'MB'
			FROM information_schema.tables    			
		WHERE table_type = 'BASE TABLE' 
			AND table_schema='".$GLOBALS["MYSQL_DB"]."'  
			AND table_name LIKE '".$tableName."_%'
		ORDER BY table_name DESC
		LIMIT 0,$limit
	";
	$data = readTable($sql);
	return $data;
}
function GetTableSize($tablename){
	return readScalar("
		SELECT round(((data_length + index_length) / 1024 / 1024), 2) AS MB
		FROM information_schema.TABLES 
		WHERE table_schema = '".$GLOBALS["MYSQL_DB"]."'
		AND table_name = '".$tablename."'");
}
?>