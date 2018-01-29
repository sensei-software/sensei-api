<?php
// If DEBUG mode is ON:
// - DB password change
define("DEBUG", 0);

// DB credentials
$MYSQL_HOST="localhost";
$MYSQL_DB= "sensei";
$MYSQL_USR= "sensei";
if(DEBUG)
  $MYSQL_PASS="";
else
  $MYSQL_PASS="senseimonitor777";

$SENSEI_PATH = "/home/sensei/sensei-server";

$SenseiMonitorRefresh = 600;
$SenseiMonitorLastMinutes ="60&r=60";
$SenseiMonitorLastHours = "12&r=60";

?>
