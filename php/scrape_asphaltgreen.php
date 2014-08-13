<?php 
require '../connection.php';
ini_set('max_execution_time', 5000); // set this value in php ini file
 function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        $data = $data;
		return $data;   // Returning the scraped data from the function
    }
	function curl($url) {
        // Assigning cURL options to an array
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 1000,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 1000,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 100, // Setting the maximum number of redirections to follow
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
        );
         
        $ch = curl_init();  // Initialising cURL 
        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL 
        return $data;   // Returning the data from the function 
    }
?>
<html>
<head>
</head>
<body>

<?php 
	 $continue = 'find';
	 $url = "http://www.asphaltgreen.org/c-1959-p0-Youth-Programs.aspx";
	 $scraped_page = curl($url);
	 $scraped_data = scrape_between($scraped_page,"<div class=\"subCategoryListing\">","</div>");
	 $catogories = explode("<li class=\"\"><span class=\"\">",$scraped_data);
	 foreach($catogories as $catogory){
	 if(!(strpos($catogory,"<strong>"))){
	 $source="www.asphaltgreen.org";
	 $business_title = "Asphalt Green";
	 $catogory_link ="http://www.asphaltgreen.org/".scrape_between($catogory, "href=\"", "\"");
	 $sub_category = curl($catogory_link);
	 if(strpos($sub_category,"<div class=\"subCategoryListing\">")){
		 $scraped_data_subcategory = scrape_between($sub_category,"<div class=\"subCategoryListing\">","</div>");
		 $sub_category_parts = explode("<li class=\"\"><span class=\"\">",$scraped_data_subcategory);
		 foreach($sub_category_parts as $sub_category_part){
		 if(strpos($sub_category_part,"<strong>")){
		 $sub_category_link ="http://www.asphaltgreen.org/".scrape_between($sub_category_part, "href=\"", "\"");
		 //$title =scrape_between($sub_category_part, "<strong>","</strong>");
		 $categories =scrape_between($sub_category, "<title>","</title>");
		 $classes_page = curl($sub_category_link);
		 if(strpos($classes_page,"<table width=\"702\" border=\"0\" cellpadding=\"0\" cellspacing=\"8\" class=\"programbox\">")){
		 //$title = $title;
		 $categories = $categories;
		 $data_classes_table =scrape_between($classes_page, "<span id=\"ProgramContentDisplay\" style=\"width:100%;\">","<span id=\"SeriesSalesDisplay\" style=\"width:100%;\"></span>");
		 $classes_table = explode("<table width=\"702\" border=\"0\" cellpadding=\"0\" cellspacing=\"8\" class=\"programbox\">",$data_classes_table);
		 foreach($classes_table as $class_name){
		 if(strpos($class_name,"<div class=\"programtitle\">")){
		 $title =scrape_between($class_name, "</a>","</div>");
		 }
		 if(strpos($class_name,"<div class=\"programcontent\"><p>")){
		 $description =scrape_between($class_name, "<div class=\"programcontent\"><p>","</p>");
		 }
		 $classes_page_parts = explode("</tr>",$class_name);
		 foreach($classes_page_parts as $class){
		 if(strpos($class,"<span style=\"font-weight:bold;\">")){
		 $session= scrape_between($class,"<span style=\"font-weight:bold;\">","</span>");
		 $price= scrape_between($class,"<div class=\"programcontent\">","</div>");
		 
		 $title=trim($title);
		 $session=trim($session);
		 $price=trim($price);
		 $categories=trim($categories);
		 $description=trim($description);
		 $class_url = $sub_category_link;

		 $num_query = mysql_query("SELECT COUNT(*) AS num FROM wwwdotasphaltgreendotorg WHERE title='$title' AND session='$session' AND price='$price' AND category='$categories'");
		 $num = mysql_fetch_assoc($num_query);
		 $num_query = $num['num'];
		 if($num_query<1){
		 $insert_query = mysql_query("INSERT INTO wwwdotasphaltgreendotorg (id,title,session,price,category,description,source,class_url) VALUES('','".$title."','".$session."','".$price."','".$categories."','".$description."','".$source."','".$class_url."')");
		 }
		 }
		 
		 }
		 }
		 //$classes_page_scraped_data = scrape_between($classes_page,"<table width=\"702\" border=\"0\" cellpadding=\"0\" cellspacing=\"8\" class=\"programbox\">","</table>");
		 }
	 }
	 }
	 }else{
	 if(strpos($sub_category,"<table width=\"702\" border=\"0\" cellpadding=\"0\" cellspacing=\"8\" class=\"programbox\">")){
	 $categories =scrape_between($sub_category, "<title>","</title>");
	 $data_classes_table =scrape_between($sub_category, "<span id=\"ProgramContentDisplay\" style=\"width:100%;\">","<span id=\"SeriesSalesDisplay\" style=\"width:100%;\"></span>");
		 $classes_table = explode("<table width=\"702\" border=\"0\" cellpadding=\"0\" cellspacing=\"8\" class=\"programbox\">",$data_classes_table);
		 foreach($classes_table as $class_name){
		 if(strpos($class_name,"<div class=\"programtitle\">")){
		 $title =scrape_between($class_name, "</a>","</div>");
		 }
		 if(strpos($class_name,"<div class=\"programcontent\"><p>")){
		 $description =scrape_between($class_name, "<div class=\"programcontent\"><p>","</p>");
		 }
		 $classes_page_parts = explode("</tr>",$class_name);
		 foreach($classes_page_parts as $class){
		 if(strpos($class,"<span style=\"font-weight:bold;\">")){
		 $session= scrape_between($class,"<span style=\"font-weight:bold;\">","</span>");
		 $price= scrape_between($class,"<div class=\"programcontent\">","</div>");
		 
		 $title=trim($title);
		 $session=trim($session);
		 $price=trim($price);
		 $categories=trim($categories);
		 $description=trim($description);
		 $class_url = $catogory_link;

		 $num_query = mysql_query("SELECT COUNT(*) AS num FROM wwwdotasphaltgreendotorg WHERE title='$title' AND session='$session' AND price='$price' AND category='$categories' AND description='$description'");
		 $num = mysql_fetch_assoc($num_query);
		 $num_query = $num['num'];
		 if($num_query<1){
		 $insert_query = mysql_query("INSERT INTO wwwdotasphaltgreendotorg (id,title,session,price,category,description,source,class_url,business_title) VALUES('','".$title."','".$session."','".$price."','".$categories."','".$description."','".$source."','".$class_url."','".$business_title."')");
		 }
		 }
		 }
		 }
	 }
	 }
	 }
	 }
	 $continue = 'no-find';
?>
</body>
</html>