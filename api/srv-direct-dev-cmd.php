<?php
	header('Content-Type: text/plain; charset=utf-8');
	mb_internal_encoding("UTF-8");

	include_once(dirname(__FILE__)."/config.php");
	include_once(dirname(__FILE__)."/lib/functions.php");

	$sid=gGet("sid");
	$cmd=gGet("cmd");


	if(!$cmd){
		echo "NO COMMAND";
		return;
	}
	if(!$sid){
		echo "NO SID";
		return;
	}
	$cmd="$SENSEI_PATH/bin/sensei-direct-cmd \"$cmd\" $sid 2>&1";	

	echo shell_exec("$cmd");
?>
