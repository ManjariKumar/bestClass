<?php 
require '../connection.php';
ini_set('max_execution_time', 4000);  //set this value in php ini file
ini_set('memory_limit', '512M');
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
            CURLOPT_CONNECTTIMEOUT => 400,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 400,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 50, // Setting the maximum number of redirections to follow
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
	 $url_one = "http://www.92y.org/Uptown/Classes/Newborn-5-yrs/All-Classes.aspx";
	 $url_two = "http://www.92y.org/Uptown/Classes/6-12-yrs/All-Classes.aspx";
	 $url_three = "http://www.92y.org/Uptown/Classes/Teens/All-Classes.aspx";
	 $urls = Array($url_one,$url_two,$url_three);
	 foreach($urls as $url){
	 $continue = 'find';
	 while ($continue=='find') {
		 $scraped_page = curl($url);
		 $scraped_data = scrape_between($scraped_page, "<div class=\"hr\" id=\"div1\"><hr /></div>", "<div class=\"addtoany\">");
		 $scraped_again = scrape_between($scraped_data, "<div class=\"todays-events\" id=\"InfoWrap\">", "<div class=\"pagi-nav\">");
		 $separate_results = explode("<li>", $scraped_again);
		 foreach ($separate_results as $separate_result) {
		 $url = "www.92y.org".scrape_between($separate_result, "href=\"", "\"");
		 $scraped_page = curl($url);
		 if(strpos($scraped_page, "<div class=\"buy-tickets-module\">") && strpos($scraped_page, "<div class=\"related-events-classes\">")){
			 if(strpos($scraped_page, "<div id=\"panel_Overview\" class=\"panel\">")){
			 $short_description = scrape_between($scraped_page, "<div id=\"panel_Overview\" class=\"panel\">", "<div class=\"cta-top\">");
			 }else{
			 $short_description = '';
			 }
			 $scraped_data_portion = scrape_between($scraped_page, "<div class=\"buy-tickets-module\">", "<div class=\"related-events-classes\">");
			 $separate_results = explode("<li>", $scraped_data_portion);
			 foreach ($separate_results as $separate_result) {
			 $scraped_whole_info = scrape_between($separate_result, "<div class=\"info-col\">", "<div class=\"cta-col\">");
				$title = scrape_between($scraped_whole_info, "<h5 class=\"perf-title\">", "</h5>");
				$title = strtolower(trim($title));
				if(strpos($scraped_whole_info, "<strong>First Session:</strong>")){
				$session = scrape_between($scraped_whole_info, "<strong>First Session:</strong>", "</p>");
				$session = trim($session);
				}else{
				$session = "";
				}
				if(strpos($scraped_whole_info, "<p><strong>Day(s):</strong>")){
				$days = scrape_between($scraped_whole_info, "<p><strong>Day(s):</strong>", "</p>");
				$days = trim($days);
				}else{$days="";}
				if(strpos($scraped_whole_info, "<strong>Sessions:</strong>")){
				$num_session = scrape_between($scraped_whole_info, "<strong>Sessions:</strong>", "</p>");
				$num_session = trim($num_session);
				}else{$num_session="";}
				if(strpos($scraped_whole_info, "<strong>Location:</strong>")){
				$location = scrape_between($scraped_whole_info, "<strong>Location:</strong>", "</p>");
				$location = trim($location);
				}else{$location="";}
				if(strpos($scraped_whole_info, "<strong>Room:</strong>")){
				$room = scrape_between($scraped_whole_info, "<strong>Room:</strong>", "</p>");
				$room = trim($room);
				}else{$room="";}
				if(strpos($scraped_whole_info, "<strong>Price:</strong>")){
				$price = scrape_between($scraped_whole_info, "<strong>Price:</strong>", "<p/>");//"This paragaraph tag was wrong closed in thata website from where data is scrapping "
				$price = trim($price);
				$price = preg_replace("/[^0-9^.]/","",$price);
				}else{$price="";}
				if(strpos($scraped_whole_info, "<strong>Date:</strong>")){
				$date = scrape_between($scraped_whole_info, "<strong>Date:</strong>", "</p>");
				$date = trim($date);
				}else{$date="";}
				$source = "www.92y.org";
				$business_title = "92Y";
				$class_url = $url;
			 $select_query = mysql_query("SELECT * FROM wwwdot92ydotorg WHERE title='$title' AND session='$session' AND days='$days' AND num_session='$num_session' AND location='$location' AND room='$room' AND price='$price'");
			 $num_select_query = mysql_num_rows($select_query);
			 if($num_select_query<1){
			 $update_query = mysql_query("INSERT INTO wwwdot92ydotorg (id,title,session,days,num_session,location,room,price,description,source,class_url,business_title) VALUES('','".$title."','".$session."','".$days."','".$num_session."','".$location."','".$room."','".$price."','".$short_description."','".$source."','".$class_url."','".$business_title."')");
			 if(!($update_query)){
			 echo 'Website is not updated successfully<br>'.mysql_error();
			 }
			 }
			 }
		 }
	 }
	 if (strpos($scraped_data, "<span class=\"offscreen\">Next</span>")) {
	 $next = scrape_between($scraped_data, "</ol>", "</div>");
	 $url = "www.92y.org".scrape_between($next, "href=\"", "\"");
	 }else{
	 $continue = 'no-find';
	 }
	 }
	 }
?>
</body>
</html>