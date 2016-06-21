<?php
include_once(dirname(__FILE__)."/../config.php");
include_once(dirname(__FILE__)."/../lib/db.php");


function replace($val, $ReplacePattern){
   $t=explode('|',$ReplacePattern);
   $pattern=$t[0];
   $replacement=$t[1];
   if(count($t)>2)
    $limit=$t[2];
    else 
      $limit=1;
   //echo " (pattern=$pattern,replacement=$replacement,limit=$limit) ";
   return preg_replace($pattern, $replacement, $val, $limit);
}

function parse($data, $pattern){
  if(preg_match_all($pattern,$data,$matches)  ){
   if(DEBUG) print_r($matches);
   if(count($matches)>1)
      return $matches[1];
   else
      return $matches[0];
  }
}


// GET/POST data helpers  
function gGet($par,$default=""){
	if (isset($_GET[$par])) 
		return   $_GET[$par]; 
	else
		return $default;
}

function gPost($par){
    if (isset($_POST[$par])) return   $_POST[$par]; else return "";
}
	
function gGlobal($par){
    if (isset($GLOBALS[$par])) return   $GLOBALS[$par]; else return "";
}
  
function gCheck($data){
	if($data=="on") return 1; else return 0;
}
// HTML mail send
function hmail($from, $to, $bcc, $replyto, $subject, $body){
	$sMsg ="";
	$mail_boundary = "=_NextPart_".md5(uniqid(time())); //Genera un boundary
	
	$sHeaders = "From: $from \n";
	if($bcc)
		$sHeaders .= 'Bcc: '.$bcc."\r\n";
	if($replyto)
		$sHeaders .= 'Reply-To: '.$replyto."\r\n";

	$sHeaders .= "MIME-Version: 1.0\n";
	$sHeaders .= "Content-Type: multipart/alternative;\n\tboundary=\"$mail_boundary\"\n";
	$sHeaders .= "X-Mailer: PHP ".phpversion();
	
	$sMsg .= "\n--$mail_boundary\n";
	$sMsg .= "Content-Type: text/html; charset=\"UTF-8\"\n";
	$sMsg .= "Content-Transfer-Encoding: 8bit\n\n";
	$sMsg .= $body; // aggiungi il messaggio in formato HTML
	
	mail($to, $subject, $sMsg, $sHeaders);
}



?>