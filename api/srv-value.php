<?php
	header('Content-Type: application/json; charset=utf-8');
	mb_internal_encoding("UTF-8");

	include_once(dirname(__FILE__)."/config.php");
	include_once(dirname(__FILE__)."/lib/db.php");
	include_once(dirname(__FILE__)."/lib/functions.php");

	$sid=gGet("sid");
	$sensor=gGet("sensor");
	$measure=gGet("measure");
	$samples=gGet("samples",1);

	$sensorName="$sid>$sensor";

	$sql="SELECT AVG(Value) FROM (SELECT Value FROM sensors_values
					WHERE SensorName='$sensorName' AND Measure = '$measure'
					ORDER BY DateField DESC LIMIT 0,$samples) as t;";

	$val=round(readScalar($sql),1);

	echo "$val";

?>
