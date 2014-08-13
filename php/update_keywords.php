<?php 
require '../connection.php';
$cases = Array('ages','age','years','year','yrs','months','month','mos','days','day','weeks','wks','week','falls','fall','to','me','iii','ii','i','II','level','iv','classes','class');
$select_query = mysql_query("SELECT  * FROM search WHERE keywords=''");
if($select_query){
$num_rows = mysql_num_rows($select_query);
if($num_rows>=1){
	 while($run = mysql_fetch_assoc($select_query)){
	 $id = $run['id'];
	 $title = $run['title'];
	 $query_keywords = mysql_query("SELECT * FROM keywords WHERE title='$title'");
	 $kewords_found = mysql_num_rows($query_keywords);
	 if($kewords_found==1){
	 $run_keywords = mysql_fetch_assoc($query_keywords);
	 $keywords = trim($run_keywords['keywords']);
	 if(!empty($keywords)){
	 $keywords = $keywords.' '.$title;
	 $keywords = preg_replace("/[^a-z]+/i", " ", $keywords);
	 $keyword = explode(" ",$keywords);
	 $prefect_keywords  = "";
	 foreach($keyword as $keywords){
	 $keywords = trim($keywords);
	 foreach($cases as $case){
	 if($keywords==$case){
	 $keywords = preg_replace('/'.$case.'/i','',$keywords);
	 }
	 }
	 $prefect_keywords .= $keywords." ";
	 }
	 }
	 $update_record = mysql_query("UPDATE search SET keywords='".$prefect_keywords."' WHERE id='".$id."'");
	 if($update_record){
	 echo "Keywords are scuccefully updared <br>";
	 }else{
	 echo "Keywords are <strong>not</strong> scuccefully updared <br>";
	 }
	}
	}
	}else{
echo "No records found which need to update ";
}
}else{
echo "No records found which need to update ";
}
?>