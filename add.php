
<?php
session_start();

if ( ! isset($_SESSION['name'] ) && ! isset($_SESSION['email'] )) {
    
    die ("Not logged in");
    return;
}
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}
require_once "pdo.php";
require("curlTestImage.php");
require('startswith.php');
$failure = false;
$success = false;
// function startsWith ($string, $startString) 
// { 
//     $len = strlen($startString); 
//     return (substr($string, 0, $len) === $startString); 
// } 
if ( isset($_POST['first_name']) && isset($_POST['last_name']) 
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

        if  (strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1|| strlen($_POST['email'])<1 || strlen($_POST['headline'])<1 || strlen($_POST['summary'])<1) {
           $_SESSION['error'] = 'All fields are required';
           header('location: add.php');
            return;

        } elseif (strpos ($_POST['email'], '@') == false) {
            $_SESSION['error'] = "Email must have an at-sign (@)";
        	header('location: add.php');
        	return;

        } elseif (strlen($_POST['image']) > 1 && (startsWith ($_POST['image'], 'https://')  || startsWith ($_POST['image'], 'http://'))  == false)  {
            $_SESSION['error'] = "image url must start with http:// or https:// ";
          header('location: add.php');
          return;

        } elseif (strlen($_POST['image']) > 1 && !url_test($_POST['image'])) {
        
               $_SESSION['error'] = "Image url is down!";
               header('location: add.php');
               return;
             

        }  else {

            $stmt = $pdo->prepare('INSERT INTO Profile
              (user_id, first_name, last_name, email, headline, summary, image)
              VALUES ( :uid, :fn, :ln, :em, :he, :su, :img)');

            $stmt->execute(array(
              ':uid' => $_SESSION['user_id'],
              ':fn' => $_POST['first_name'],
              ':ln' => $_POST['last_name'],
              ':em' => $_POST['email'],
              ':he' => $_POST['headline'],
              ':su' => $_POST['summary'],
              ':img' => $_POST['image'])
            );
   //          $stmt = $pdo->query("SELECT make, year, mileage FROM autos");
			// $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['success'] = "Profile added";
            header('location: index.php');
        	return;

        }
    //          $_SESSION['stmt'] = $pdo->query("SELECT make, year, mileage FROM autos");
			// $_SESSION['rows'] = $stmt->fetchAll(PDO::FETCH_ASSOC);     
}

?>

<html>
<head>
<title>aa955ab6 Jonathan Therrian Profile Add</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Adding profile for <?php echo htmlentities($_SESSION['name']);?></h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="post">
    <p>First Name:
    <input type="text" name="first_name" size="60">
    </p>
    <p>Last Name:
    <input type="text" name="last_name" size="60">
    </p>
    <p>Email:
    <input type="text" name="email" size='40'>
    </p>
    <p>Headline:
        </br>
    <input type="text" name="headline" size='60'>
    </p>
    <p>Summary:
    </br>
    <textarea name="summary" rows="8" cols="80"></textarea>
    </p>
    <p>Image (url):
        </br>
    <input type="text" name="image" size='60'>
    </p>
    <input type="submit" name="add" value="Add">
    <input type="submit" name="cancel" value="Cancel">
</form>