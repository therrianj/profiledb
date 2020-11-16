<?php
session_start();
require_once("pdo.php");

?>

<!DOCTYPE html>
<html>
<head>
<title>Jonathan Therrian - Resume Registry</title>
<?php require_once "bootstrap.php"; ?>
</head>
<div class="container">
<h1>Jonathan Therrian Resume Registry</h1>
<h2>Search Results for: </h2>
<h3>
<?php 

if (isset($_SESSION['search'])){
	echo($_SESSION['search']);
	$search = "%".$_SESSION['search']."%";
	$stmt  = $pdo->prepare("SELECT * FROM Profile WHERE first_name LIKE ?");
	$stmt->execute([$search]);
	$data = $stmt->fetchAll();
	
} ?>
</h3>
</div>
<?php 
// $search = "%search%";
// $stmt  = $pdo->prepare("SELECT * FROM Profile WHERE first_name LIKE ?");
// $stmt->execute([$search]);
// $data = $stmt->fetchAll();

// $stmted = $pdo->prepare("SELECT * FROM Profile WHERE first_name LIKE ?");
// $search = $_SESSION['search'];
// $stmted->execute(array("%$search%"));
// $row = $stmted->fetch(PDO::FETCH_ASSOC);
// $fn = $row['first_name'];
// $ln = $row['last_name'];
// $em = $row['email'];
// $he = $row['headline'];
// $su = $row['summary'];
// $img = $row['image'];
// print_r($row);
// print_r($data);
?>
<div class="container">
<?php
foreach ($data as $k) {

    echo "</br>";
    echo "First Name: ".htmlentities($k['first_name']);
    echo "</br></br>";
    echo "Last Name: ".htmlentities($k['last_name']);
    echo "</br></br>";
    echo "Email: ".htmlentities($k['email']);
    echo "</br></br>";
    echo "Headline: </br>".htmlentities($k['headline']);
    echo "</br></br>";
    echo "Summary: </br>".htmlentities($k['summary']);
    echo "</br></br>";
    echo "Image: </br>";
    if ($k['image'] !== null){echo ('<img src="'.$k['image'].'" alt="image not found"');}
    echo "</br></br>";
}
?>
</div>
</html>









