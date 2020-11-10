<?php
session_start();
require_once 'pdo.php';

$stmt = $pdo->query("SELECT first_name, last_name, headline, profile_id, image FROM Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Jonathan Therrian - Resume Registry</title>
<?php require_once "bootstrap.php"; ?>
</head>
<div class="container">
<h1>Jonathan Therrian's Resume Registry</h1>
<?php
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
if ( ! isset($_SESSION['name']) && ! isset($_SESSION['email'])) { ?>
<p>
<a href="login.php">Please log in</a>
</p>
<table>


<?php
	
	echo('<table border="2">'."\n");
	echo('<th>Name</th><th>Headline</th>');
	foreach ( $rows as $row ) {
    
    echo "<tr><td> ";
    echo '<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'].' '.$row['last_name']).'</a>';
    echo("</td><td>");
    echo htmlentities($row['headline']);
    echo("</td><td>");
    echo(" </td></tr>\n");

}} else{  ?>
    <p>
<a href="logout.php">Logout</a>
</p>


<table>
<?php
    
    echo('<table border="2">'."\n");
    echo('<th>Name</th><th>Headline</th><th>Action</th>');
    foreach ( $rows as $row ) {
    
    echo "<tr><td> ";
    echo '<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'].' '.$row['last_name']).'</a>';
    // echo htmlentities($row['first_name'].' '.$row['last_name']);
    echo("</td><td>");
    echo htmlentities($row['headline']);
    echo("</td><td>");
    if ($row['image'] !== null){echo ('<img src="'.$row['image'].'" alt="image not found"');}
    echo("</td><td>");
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'"> Edit /</a>');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    echo(" </td></tr>\n"); }

 ?> 
</table>
</p>
 <a href="add.php">Add New Entry</a> 
</p>
<?php } ?>

</div>
</html>

