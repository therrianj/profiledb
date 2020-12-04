<?php


function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
} 

function validateProfile(){
  
  if  (strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1|| strlen($_POST['email'])<1 || strlen($_POST['headline'])<1 || strlen($_POST['summary'])<1) {
           return 'All fields are required';
           
        } 
    if (strpos ($_POST['email'], '@') === false) {
            return "Email must have an at-sign (@)";
          
        } 

    if (strlen($_POST['image']) > 1 && (startsWith ($_POST['image'], 'https://')  || startsWith ($_POST['image'], 'http://'))  == false)  {
            return "image url must start with http:// or https:// ";
         

        } 
    if (strlen($_POST['image']) > 1 && !url_test($_POST['image'])) {
        
               return "Image url is down!";
               

    } 
  
  return true;
    

}

function validatePos() {
  for($i=1; $i<=9; $i++) {
    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;

    $year = $_POST['year'.$i];
    $desc = $_POST['desc'.$i];

    if ( strlen($year) == 0 || strlen($desc) == 0 ) {
      return "All fields are required";
    }

    if ( ! is_numeric($year) ) {
      return "Position year must be numeric";
    }
  }
  return true;
}


function flashMessages () {

	if ( isset($_SESSION['success']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
	}
	if ( isset($_SESSION['error']) ) {
	    // Look closely at the use of single and double quotes
	    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
	    unset($_SESSION['error']);
	}
}

function search (){

	if (isset($_POST['search'])){
    $_SESSION['search'] = $_POST['search'];
    header('location: search.php');
    return;
}




}



