<?php
$CMD["CronTab"]="
  cat /etc/crontab
    | grep -v '^$'
    | grep sensei-cmd
    | sed 's/ \//\//g'
    | sed 's/\([0-9]\) \%22/\1%22/g'
    | awk '{ print $5 %22/%22 $4 %22/%22 $3 %22 %22 $2 %22:%22 $1 %22 -> %22 $12 %22 %22 $13 %22 %22 $14  }'
    | sort -k 2 -V
";
$CMD["SenseiPs"]="$SENSEI_PATH/bin/sensei-ps";
$CMD["Rules"]="cat $SENSEI_PATH/conf/rules/*.active";
$CMD["TaskManager"]="top -b -n 1 | head -n 20";
$CMD["CpuLoad"]="cat /proc/loadavg";
$CMD["DiskFree"]="df";
$CMD["SenseiPorts"]="$SENSEI_PATH/bin/sensei-port -a";
$CMD["CheckUsbEMI"]="dmesg -T | grep EMI | tail";
$CMD["PortDisconnect"]="grep 'disconnected from tty' /var/log/messages | tail";
$CMD["PortConnect"]="grep 'attached to tty' /var/log/messages | tail";
$CMD["ComingValues"]="$SENSEI_PATH/bin/sensei-db-command
  \"select * from sensors_values ORDER BY DateField DESC LIMIT 0,40\"
";
$CMD["LastValues"]="$SENSEI_PATH/bin/sensei-db-values";
$CMD["SenseiLog"]="tail -n 20 $SENSEI_PATH/logs/sensei.log";
$CMD["SenseiErrors"]="tail -n 20 $SENSEI_PATH/logs/sensei_errors.log";
$CMD["MinicronLog"]="grep 'Running command' /var/log/minicron.log | tail -n 40";
$CMD["SenseiStop"]="$SENSEI_PATH/bin/sensei-stop";
$CMD["SenseiStart"]="$SENSEI_PATH/bin/sensei-start";
$CMD["SenseiRestart"]="$SENSEI_PATH/bin/sensei-restart";
$CMD["SenseiDiscover"]="$SENSEI_PATH/bin/sensei-discover";
$CMD["MinicronRestart"]="sudo minicron server restart";
?>
