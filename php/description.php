<?php 
require '../connection.php';
if(isset($_POST['id'])){
echo $id =$_POST['id'];
$query = mysql_query("SELECT description FROM keywords WHERE id='$id'");
$run = mysql_fetch_assoc($query);
$description = htmlentities(trim($run['description']));
if(!empty($description)){
echo $description;
}else{
echo "No description is found";
}
}
?>