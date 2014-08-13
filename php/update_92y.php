<?php 
require '../connection.php';
ini_set('max_execution_time', 1000); 
$query = mysql_query("SELECT * FROM wwwdot92ydotorg");
while($run = mysql_fetch_assoc($query)){
$title = trim($run['title']);

if(substr( $title, 0,3 ) === "92y"){$title = preg_replace('/^[0-9]*(\w+)/', '', $title);}
$session = trim($run['session']);
$days = trim($run['days']);
$num_session = trim($run['num_session']);
$location = trim($run['location']);
$room = trim($run['room']);
$price = trim($run['price']);
$description = trim($run['description']);
$source = trim($run['source']);
$business_title = trim($run['business_title']);
$zip_code = 10128;


$scale_universal = "years";
if(strpos($title,"years") || strpos($title,"yrs") || strpos($title,"age")){$scale_universal = "years";}
if(strpos($title,"months") || strpos($title,"mos") || strpos($title,"month")){$scale_universal = "months";}
if(strpos($title,"weeks") || strpos($title,"week") || strpos($title,"wks")){$scale_universal = "weeks";}
	 //code for mini age and max age
	 if(strpos($title,"(")){
	 preg_match('~\((.*?)\)~', $title, $output);
	 $target_title = $output[1];
	 $split = explode("-",$target_title);
	 if(count($split) === 1){
	 $split = explode("to",$target_title);
	 if(count($split) === 1){
	 $split = explode("/",$target_title);
	 }
	 }
	 }else{
	 $target_title = preg_replace("/92y/","",$title);
	 $split = explode("-",$target_title);
	 if(count($split) === 1){
	 $split = explode("to",$target_title);
	 if(count($split) === 1){
	 $split = explode("age",$target_title);
	 }
	 }
	 }
	 
	 $age = array();
	 foreach($split as $splits){
if(strpos($splits,"years") || strpos($splits,"yrs") || strpos($splits,"age")){
	 $scale = "years";
}else{
if(strpos($splits,"months") || strpos($splits,"mos")){
	 $scale = "months";
}else{
if(strpos($splits,"weeks") || strpos($splits,"week") || strpos($splits,"wks")){
	 $scale = "weeks";
}else{ $scale=$scale_universal;}
}
}
	
	 $ages = preg_replace("/[^0-9^.]/","",$splits);
	 
	 

	 if(!empty($ages) && $ages<=36 ){
			if($scale == "months"){
			$ages = ($ages/12);
			}
			if($scale == "weeks"){
			$ages = (($ages/30)/12);
			}
			$age[] = $ages;
		}
	 }
	 if(!empty($age[0])){
	  $min_age = $age[0];
	  $min_age = number_format($min_age,4);
	 }else{
	  $min_age = "";
	 }
	 if(!empty($age[1])){
	  $max_age = $age[1];
	  $max_age = number_format($max_age,4);
	 }else{
	  $max_age = "";
	 }
	 
	  //code for expire date 
	  if($session!=''){
			 $split_session = explode(",",$session);
				 $day =$split_session[0];
				 $date = $split_session[1];
				 $year = $split_session[2];
				 $time = $split_session[3];
			}else{
				 $day ="-";
				 $date = "-";
				 $year = "-";
				 $time = "-";
			}
	 $date =  $date.','.$year;
	 $date_code = strtotime($date); 
	 $slect_query = mysql_query("SELECT * FROM search WHERE title='".$title."' AND days='".$days."' AND day='".$day."' AND sessions='".$num_session."' AND price='".$price."' AND time='".$time."' AND min_age='".$min_age."'  AND date_code='".$date_code."' ");
	 $num_slect_query = mysql_num_rows($slect_query);
	 if($num_slect_query<1){
	 $insert_query = mysql_query("INSERT INTO search(id,title,date,day,time,sessions,price,source,keywords,min_age,max_age,location,date_code,days,description,zip_code,business_title) VALUES('','".$title."','".$date."','".$day."','".$time."','".$num_session."','".$price."','".$source."','','".$min_age."','".$max_age."','".$location."','".$date_code."','".$days."','".$description."','".$zip_code."','".$business_title."') ");
	 }
}
?>