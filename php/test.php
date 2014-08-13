<html>
<head><title>mustehsen</title>
</head>

<body>
 
<?php 
	require '../connection.php';
	 $session = "Mon, Dec 23<br>8:45 AM - 3:15 PM<br>";
	 //$session = "14 times on Thursdays <br>5:55 PM - 6:40 PM<br>Sep 12 - Dec 19<br>Except: Nov 28";
	 
	 $price = '$115.00 For All<br />CCDHCX03W4';
	 $price_arrangement =  strip_tags($price);
	 $prices = explode("for",$price_arrangement);
	 $price = $prices[0];
	 echo $price = number_format(preg_replace("/[^0-9^.]/","",$price),2);
	
	
?>
</body>
</html>