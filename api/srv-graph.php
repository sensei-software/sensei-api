<?php
	header('Content-Type: application/json; charset=utf-8');
	mb_internal_encoding("UTF-8");

	include_once(dirname(__FILE__)."/config.php");
	include_once(dirname(__FILE__)."/lib/db.php");
	include_once(dirname(__FILE__)."/lib/functions.php");

	function printGraphJSON($data,$header,$type='area',$title){
					if(!$data) return;
	        $h="{\n";
	        if(isset($header)){
						$h.= "\t\"name\": \"$header\",\n";
	        }
					$h.= "\t\"data\": [\n";
        	for($i=0;$i<count($data);$i++){
								$h.="\t[";
                for($j=0;$j<count($data[$i])/2;$j++){
                        $h.= " \"".str_replace(",","",$data[$i][$j])."\" ";
												if ($j<count($data[$i])/2-1) $h.=", ";
                }
                $h.= "]";
								if ($i<count($data)-1) $h.=",\n";
        	}
					$h.= "]\n";
					$h.= "}\n";

	        return $h;
	}

	$sensors= array();
	$sqls= array();
	$type=gGet("type","spline");
	$unit=gGet("unit","m");
	$filter=gGet("filter","");
	$singleGraph=gGet("single","1");
	$timeLast=gGet("last","60");

	$timeFrom=gGet("from","");
	$timeTo=gGet("to","");
	$fillHoles=gGet("fill","1");

	if($unit=="m"){
		$unit="MINUTE";
		$table="sensors_values_m";
		$calendarTable="calendar_m";
		$dateFormat_In="%Y%m%d%H%i";
		//$dateFormat_Out="%m/%d %H:%i";
		$dateFormat_Out="%Y/%m/%d %H:%i:%s";
	}else if ($unit=="h"){
		$unit="HOUR";
		$table="sensors_values_h";
		$calendarTable="calendar_h";
		$dateFormat_In="%Y%m%d%H";
		//$dateFormat_Out="%m/%d %H:%i";
		$dateFormat_Out="%Y/%m/%d %H:%i:%s";
	}else{
		$unit="SECOND";
		$table="sensors_values";
		$calendarTable="calendar_s";
		$dateFormat_In="%Y%m%d%H%i%s";
		//$dateFormat_Out="%m/%d %H:%i:%s";
		$dateFormat_Out="%Y/%m/%d %H:%i:%s";
	}

	if(!$timeFrom){
		$timeFrom = "( (NOW() - INTERVAL $timeLast $unit) +0)";
	}
	if(!$timeTo){
		$timeTo = "( NOW() +0 )";
	}

	// Filter parsing
	$filter=str_replace(" ","%",$filter);
	if(strpos($filter,",")){
		$filters=explode(",",$filter);
		foreach($filters as $f){
			$filterRules[] = "CONCAT(SensorName,':',Measure) LIKE '%$f%'";
		}
		$filterRule=implode(" OR ",$filterRules);
	} else {
		$filterRule="CONCAT(SensorName,':',Measure) LIKE '%$filter%'";
	}

	$sql="SELECT DISTINCT SensorName,Measure,Unit FROM $table WHERE
		DateField > $timeFrom AND DateField < $timeTo
		AND ($filterRule) ORDER BY SensorName,Measure ";

	$sensors=readTable($sql);

	if(!count($sensors)){
		echo "[{\"series\": []}]";
		return;
	}

	foreach($sensors AS $sensor){

			$sensorName=$sensor["SensorName"];
			$sensorMeasure=$sensor["Measure"];
			$sensorUnit=$sensor["Unit"];
			$sensorLabel="($sensorName) - $sensorMeasure - {$sensorUnit}";
			$sensorsLabels[]=$sensorLabel;


			if($fillHoles)
					$dateField ="c.datefield";
			else
				$dateField ="v.datefield" ;
			$sql="
				SELECT DISTINCT
					DATE_FORMAT(STR_TO_DATE($dateField,'$dateFormat_In'),'$dateFormat_Out') as datefield,
					FORMAT(SensorAvg,1) as  `$sensorLabel`
				FROM (
					SELECT
						DateField,
						AVG(Value) as SensorAvg
					FROM `$table` as s
					  WHERE DateField > $timeFrom AND DateField < $timeTo
						  AND s.SensorName ='$sensorName' AND s.Measure='$sensorMeasure'
					GROUP BY
						DateField
				) as v
			";

			if($fillHoles){
				$sql.="
					RIGHT OUTER JOIN $calendarTable as c
					    ON c.datefield = v.datefield
				";
			}
			$sql.="
					WHERE $dateField > $timeFrom AND $dateField < $timeTo
					ORDER BY $dateField ASC
			";
		$data=readTableU($sql);
		if (count($data))
			$graphs[]=printGraphJSON($data, $sensorLabel,"$type","$sensorLabel");
	}

	// JOIN ------------------------------------
	$j="[{\"series\": [\n";
	if (count($graphs))
		$j.=implode(",",$graphs);
	$j.="]}]";

	echo $j;

?>
