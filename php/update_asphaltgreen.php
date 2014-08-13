<?php 
require '../connection.php';
$query = mysql_query("SELECT * FROM wwwdotasphaltgreendotorg");
while($run = mysql_fetch_assoc($query)){
	$id = $run['id'];
	$title = strtolower(trim($run['title']));
	$session = strtolower(trim($run['session'])); 
	$price = strtolower(trim($run['price']));
	$category = trim($run['category']);
	$description = trim($run['description']);
	$source = trim($run['source']);
	$business_title = trim($run['business_title']);
	$zip_code = 10128;
/*arrangement of min and max age*/
$scale_universal="years";
if(strpos($title,"years") || strpos($title,"yrs")){$scale_universal = "years";}
if(strpos($title,"months") || strpos($title,"mos") || strpos($title,"month")){$scale_universal = "months";}
if(strpos($title,"weeks") || strpos($title,"week") || strpos($title,"wks")){$scale_universal = "weeks";}
	 if(strpos($title,"(")){
	 preg_match('~\((.*?)\)~', $title, $output);
	 $target_title = $output[1];
	 $target_title_contain = preg_replace("/[^0-9^.]/","",$target_title);
	 
	 $split = explode("-",$target_title);
	 if(count($split) === 1){
	 $split = explode("to",$target_title);
	 if(count($split) === 1){
	 $split = explode("/",$target_title);
	 }
	 }
	 }else{
	 $split = explode("-",$title);
	 if(count($split) === 1){
	 $split = explode("to",$title);
	 if(count($split) === 1){
	 $split = explode("/",$title);
	 }
	 }
	 }
	 $age = array();
	 foreach($split as $splits){
	 if(strpos($splits,"years") || strpos($splits,"yrs")){$scale = "years";}
	 else{if(strpos($splits,"months") || strpos($splits,"mos")){$scale = "months";}
	 else{if(strpos($splits,"weeks") || strpos($splits,"week") || strpos($splits,"wks")){$scale = "weeks";}
	 else{ $scale=$scale_universal;}}}
	 
	 if(strpos($title,"under") || strpos($title,"older")){
	 $ages = preg_replace("/[^0-9^.]/","",$title);
	 }else{
	 $ages = preg_replace("/[^0-9^.]/","",$splits);
	 }
	 
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
	  if(strpos($title,"under")){
	  $min_age = 0;
	 }
	 }else{$min_age = "";}
	 
	 if(!empty($age[1])){
	  $max_age = $age[1];
	  $max_age = number_format($max_age,4);
	 }else{
	 if(strpos($title,"older")){
	  $max_age=18;
	 }else{
	 if(strpos($title,"under")){
	 $max_age = $age[0];
	 }else{
	 $max_age = "";
	 }
	 }
	 }
	 /*timing of  classes*/
	 $timing = explode("<br>",$session);
	 $count_break = count(explode("<br>",$session))-1;
	 if($count_break==2){
	 $day_date = $timing[0];
	 $day_date = explode(",",$day_date);
	 $day = $day_date[0];
	 $date = $day_date[1];
	 $time = trim($timing[1]);
	 $date = ucfirst($date.", 2013");
	 $num_session = 1;
	 
	 }else if($count_break==3){
	 $day = $timing[0];
	 $num_session = preg_replace("/[^0-9^.]/","",$day);
	 $day_lenght = strlen($day);
	 if(strpos($day,"times on")){
	 $parameter_lenght = strlen("times on");
	 $day_position = strpos($day,"times on")+$parameter_lenght;
	 $day = substr($day,$day_position,$day_lenght);
	 $time = $timing[1];
	 }
	 $time = trim($timing[1]);
	 $date = trim($timing[2]);
	 $date = explode("-",$date);
	 $date = $date[0];
	 $date = ucfirst($date.", 2013");
	 $second_day ="";
	 $second_time="";
	 
	 }else{
	 if($count_break==5){
	 $day = $timing[0];
	 $num_session = preg_replace("/[^0-9^.]/","",$day);
	 $day_lenght = strlen($day);
	 if(strpos($day,"times on")){
	 $parameter_lenght = strlen("times on");
	 $day_position = strpos($day,"times on")+$parameter_lenght;
	 $day = substr($day,$day_position,$day_lenght);
	 $time = $timing[1];
	 $second_day =  $timing[2];
	 $second_time =  $timing[3];
	 $date =  $timing[4];
	 $date = explode("-",$date);
	 $date = $date[0];
	 $date =ucfirst($date.", 2013");
	 }
	 }
	 }
	 $price_arrangement =  strip_tags($price);
	 if(strpos($price_arrangement,"member")){
	 $prices = explode("member",$price_arrangement);
	 $price = $prices[1];
	 $price = number_format(preg_replace("/[^0-9^.]/","",$price),2);
	 }else{
	 $prices = explode("for",$price_arrangement);
	 $price = $prices[0];
	 $price = number_format(preg_replace("/[^0-9^.]/","",$price),2);
	 }
	 $date_code = strtotime($date);
	 
	 /*$id,$title,$day,$time,$num_session,$price,$short_description,$min_age,$max_age,$date,$date_code*/
	 $check_query = mysql_query("SELECT * FROM search WHERE title='$title' AND day='$day' AND time='$time' AND sessions='$num_session' AND price='$price' AND min_age='$min_age' AND max_age='$max_age' AND date_code='$date_code' AND date='$date'");
	 if(!$check_query){die(mysql_error());}
	 $num = mysql_num_rows($check_query);
	 if((mysql_num_rows($check_query))<1){
	 $insert_query = mysql_query("INSERT INTO search(id,title,date,day,time,sessions,price,source,keywords,min_age,max_age,location,date_code,days,description,zip_code,business_title) VALUES('','".$title."','".$date."','".$day."','".$time."','".$num_session."','".$price."','".$source."','','".$min_age."','".$max_age."','','".$date_code."','".$day."','".$description."','".$zip_code."','".$business_title."')");
	 if(!($insert_query)){
	 die("not enter any record".mysql_error());
	 }else{
	 echo "Website succecfully updated<br>";
	 }
	 }
	 
}
?>