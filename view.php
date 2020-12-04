<?php
session_start();

require_once "pdo.php";
$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid");
$stmt->execute(array(       
                ':pid' => $_GET['profile_id'],));
$rows = $stmt->fetchall(PDO::FETCH_ASSOC);
?>


<html>
<head>
<title>aa955ab6 Jonathan Therrian</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Profile Information</h1>
<!-- <?php 
// 	if (isset($_SESSION['success'])){
// 	echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
//     unset($_SESSION['success']);
// }
?>
<h2>Automobiles</h2> -->
<?php 
// print_r($rows);
$stpos = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :pid");
$stpos->execute(array(       
                ':pid' => $_GET['profile_id'],));
$posrows = $stpos->fetchall(PDO::FETCH_ASSOC);




foreach ($rows as $k) {

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
    echo "Positions: ";
    echo "</br></br>";
    echo "<ul>";
    foreach ($posrows as $j){
        echo "<li>".htmlentities($j['year']).": ".htmlentities($j['description'])."</li>";
                

    }
    echo "</ul>";
    echo "</br></br>";
    echo "Image: </br>";
    if (strlen($k['image']) >=1){echo ('<img src="'.$k['image'].'" alt="image not found"');}
    echo "</br></br>";


}


?>

<p>
<a href="index.php">Done</a>

</p>
</body>
</html>
