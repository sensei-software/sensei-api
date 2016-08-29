<?php
	header('Content-Type: text/plain; charset=utf-8');
	mb_internal_encoding("UTF-8");

	include_once(dirname(__FILE__)."/config.php");
	include_once(dirname(__FILE__)."/lib/functions.php");

	$path=gGet("path");
	$cmd=gGet("cmd");
	$r=gGet("r");

	if(!$cmd){
		echo "NO COMMAND";
		return;
	}

	if($path=="sensei"){
		// $cmd="$SENSEI_PATH/bin/$cmd";
		$cmd="export PATH='/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:$SENSEI_PATH/bin/';$cmd";
	}

	//echo "EXEC: $cmd ";
	// echo "RESULT";
	echo shell_exec("$cmd 2>&1");
?>
