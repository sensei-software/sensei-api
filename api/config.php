<?php
// If DEBUG mode is ON:
// - TOR is disabled
// - DB password change
define("DEBUG", 0);

// DB credentials
$MYSQL_HOST="localhost";
$MYSQL_DB= "sensei";
$MYSQL_USR= "sensei";
if(DEBUG)
  $MYSQL_PASS="";
else
  $MYSQL_PASS="";

$SENSEI_PATH = "../../sensei-server";

$SenseiMonitorRefresh = 600;
$SenseiMonitorLastMinutes ="60&r=60";
$SenseiMonitorLastHours = "12&r=60";

?>
