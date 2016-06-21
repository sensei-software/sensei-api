<?php
	header('Content-Type: text/plain; charset=utf-8');
	mb_internal_encoding("UTF-8");

	include_once(dirname(__FILE__)."/config.php");
	include_once(dirname(__FILE__)."/../commands.conf.php");
	include_once(dirname(__FILE__)."/lib/functions.php");

	$cmdName=gGet("alias");
	$cmd=str_replace("\n","",$CMD[$cmdName]);
	$r=gGet("r");

	if(!$cmd){
		echo "NO COMMAND";
		return;
	}

	echo shell_exec("$cmd");
?>
