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
		$cmd="$SENSEI_PATH/bin/$cmd";
	}

	echo shell_exec("$cmd");
?>
