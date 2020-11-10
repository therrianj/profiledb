<?php 

function url_test( $url ) {
	$timeout = 10;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	$http_respond = curl_exec($ch);
	$http_respond = trim( strip_tags( $http_respond));
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if (( $http_code == "200") || ( $http_code == "302")) {
		return true;
	} else {
		return false;
	}

	curl_close( $ch );
}
// if (isset($_POST['url'])){
// 	$website = $_POST['url'];
// 	if ( !url_test($website)){
// 		echo $website." is down!";
// 	}
// 	else {
// 		echo $website ." functions correctly.</br></br>";
// 		echo '<img src="'.$_POST['url'].'" alt="image not found"';
// }}


?>

<!-- <html>
<form method=post>
	<p>
	Image (url):
	<input type="text" name="url" >
	</p>
	<input type="submit" name="submit">
</form> -->