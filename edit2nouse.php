
<?php
session_start();
require_once "pdo.php";
if ( ! isset($_SESSION['name'] ) && ! isset($_SESSION['email'] ) ) {
    
    die ("ACCESS DENIED");
    return;
}
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

$failure = false;
$success = false;
if ( isset($_POST['make']) && isset($_POST['model'])&& isset($_POST['year']) 
     && isset($_POST['mileage'])) {
        if (! is_numeric($_POST['year']) 
             || ! is_numeric($_POST['mileage'])){
            $_SESSION['error'] = "Mileage and year must be numeric";
        	header('location: edit.php?autos_id='.$_REQUEST['autos_id']);
        	return;

        } elseif (strlen($_POST['make'])<1 || strlen($_POST['model'])<1|| strlen($_POST['mileage'])<1 || strlen($_POST['year'])<1) {
           $_SESSION['error'] = "All fields required";
           header('location: edit.php?autos_id='.$_REQUEST['autos_id']);
            return;
        } 
        else{

            $sql = "UPDATE autos SET make = :make, model = :model, year = :year, mileage = :mileage WHERE autos_id = :autos_id";


            // echo("<pre>\n".$sql."\n</pre>\n");

            $stmt = $pdo->prepare($sql);

            $make = ($_POST['make']);
            $model = ($_POST['model']);
            $year = ($_POST['year']);
            $mileage = ($_POST['mileage']);
            $autos_id = ($_POST['autos_id']);

            // print_r($stmt);
            // echo $model;
            // echo $mileage;
            // echo $year;
            // echo $autos_id;
            $stmt->execute(array(       
                ':make' => $make,
                ':model' => $model,
                ':year' => $year,
                ':mileage' => $mileage,
                ':autos_id' => $autos_id));

            $_SESSION['success'] = "Record edited";
            header('Location: index.php');
        	return;

        }
    //          $_SESSION['stmt'] = $pdo->query("SELECT make, year, mileage FROM autos");
			// $_SESSION['rows'] = $stmt->fetchAll(PDO::FETCH_ASSOC);     
}

?>

<html>
<head>
<title>aa955ab6 Jonathan Therrian Auto Database</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo htmlentities($_SESSION['email']);?></h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
if ( ! isset($_REQUEST['autos_id']) ) {
  $_SESSION['error'] = "Missing autos_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id = :xyz");
$stmt->execute(array(":xyz" => $_REQUEST['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$m = htmlentities($row['make']);
$md = htmlentities($row['model']);
$mg = htmlentities($row['mileage']);
$y = htmlentities($row['year']);
$autos_id = $row['autos_id'];
?>
<form method="post">
    <p>Make:
    <input type="text" name="make" size="60" value="<?= $m ?>">
    </p>
    <p>Model:
    <input type="text" name="model" size="60" value="<?= $md ?>">
    </p>
    <p>Year:
    <input type="text" name="year" value="<?= $mg ?>">
    </p>
    <p>Mileage:
    <input type="text" name="mileage" value="<?= $y ?>">
    </p>
    <input type="hidden" name="autos_id" value="<?= $autos_id ?>">
    <input type="submit" name="save" value="Save">
    <input type="submit" name="cancel" value="Cancel">
</form>