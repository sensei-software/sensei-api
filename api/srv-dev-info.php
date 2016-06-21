<?php
	header('Content-Type: application/json; charset=utf-8');
	mb_internal_encoding("UTF-8");

	include_once(dirname(__FILE__)."/config.php");
	include_once(dirname(__FILE__)."/lib/db.php");
	include_once(dirname(__FILE__)."/lib/functions.php");

	function readCmd($cmd){
		return shell_exec($cmd);
	}
	function readCmdLines($cmd){
		return explode(PHP_EOL, trim(shell_exec($cmd)));
	}
	function startsWith($haystack, $needle) {
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
	function buildCommands($devName,$devModel,$commandList){
		$dev["Name"]=trim(preg_replace('/\s\s+/', ' ', $devName));
		$dev["Model"]=trim(preg_replace('/\s\s+/', ' ', $devModel));
		for($i=0;$i<count($commandList); $i++){
			$commandList[$i]=trim(preg_replace('/\s\s+/', ' ', $commandList[$i]));
		}
		$dev["isReact"]=false;
		if(startsWith($devModel,"ReAct")){
			$dev["isReact"]=true;
		}
		$dev["commands"]=$commandList;
		return $dev;
	}


	$devs=readCmdLines("$SENSEI_PATH/bin/sensei-port -a 2>&1");
	$devices=Array();
	$device=Array();
	$device["port"]="";
	$device["device"]="";
	$DIR="$SENSEI_PATH";;
  foreach($devs as $dev){
		if($dev != ""){
		    $devName=readCmd("cat $DIR/$dev/sensei.name");
				$devModel=readCmd("cat $DIR/$dev/sensei.model");
		    $cmds=readCmdLines("cat $DIR/$dev/sensei.commands");
				$device["port"]=$dev;
		    $device["device"]= buildCommands($devName,$devModel,$cmds);
		    $devices[]=$device;
		}
   }
   $response["devices"]=$devices;
   echo json_encode($response);
?>
