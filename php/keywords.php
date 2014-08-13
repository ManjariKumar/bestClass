<?php 
require '../connection.php';
$select_query = mysql_query("SELECT  * FROM keywords WHERE keywords=''");
while($run = mysql_fetch_assoc($select_query)){
		$id = $run['id'];
		$title = $run['title'];
		$source = $run['source'];
		if(isset($_POST[''.$id.''])){ 
		$keywords = trim($_POST[''.$id.'']);
		if(!empty($keywords)){
		$update_keywords = mysql_query("UPDATE keywords SET keywords='".$keywords."' WHERE id='".$id."'");
		if($update_keywords){echo "Your keywords are saved successfully";}
		}
		}
		}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="www.bestclassfor.me">
<meta name="author" content="mustehsen cheema">
<title>Admin Area</title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
<style>
#cont_form{
width:1000px;
background:#ccc;
margin:20px auto;
}
ul{
	width:990px;
	margin:20px auto;
	padding-bottom:20px;
}
ul li{
	 width:900px;
	 list-style:none;
	 padding:13px;
	 font:16px arial,sans-serif;
	 color:#000;
	 line-height:100px;
}
ul li textarea{
	 float:right;
	 width:400px;
	 height:100px;
	 
}
.submit{
	 width:200px;
	 margin:0 auto;
	 text-align:center;
}
</style>
</head>
<body>
	 <div id="cont_form">
	 
	 <ul>
		 <?php 
		$select_query = mysql_query("SELECT  * FROM search WHERE keywords='' GROUP BY title ");
		while($run = mysql_fetch_assoc($select_query)){
		$title = trim($run['title']);
		$description = trim($run['description']);
		$source = $run['source'];
		$query_keywords = mysql_query("SELECT * FROM keywords WHERE title='$title'");
		$kewords_found = mysql_num_rows($query_keywords);
		if($kewords_found<1){
		$inser_record = mysql_query("INSERT INTO keywords(id,title,keywords,source,description) VALUES('','".$title."','','".$source."','".$description."')");
		}
		}
		echo'<form method="post">';
		$select_query = mysql_query("SELECT  * FROM keywords WHERE keywords=''");
		while($run = mysql_fetch_assoc($select_query)){
		$id = $run['id'];
		$title = $run['title'];
		$description = $run['description'];
		$source = $run['source'];
		echo'<li>'.$title.'<textarea class="'.$id.'" name="'.$id.'"></textarea>
		<input class="description" type="button" value="enter" title="'.$id.'">
		</li>';
		}
		echo'<br><br><p class="submit" ><input type="submit" value="Save Keywords"></p></form>';
		?>
	 </ul>
	 </div>
</body>
</html>