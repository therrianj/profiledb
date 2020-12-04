
<?php
session_start();

if ( ! isset($_SESSION['name'] ) && ! isset($_SESSION['email'] )) {
    
    die ("Not logged in.");
    return;
}
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}
require "pdo.php";
require("curlTestImage.php");
require "util.php";
require_once "bootstrap.php";

$stmted = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :xyz");
$stmted->execute(array(":xyz" => $_REQUEST['profile_id']));
$row = $stmted->fetch(PDO::FETCH_ASSOC);
$fn = $row['first_name'];
$ln = $row['last_name'];
$em = $row['email'];
$he = $row['headline'];
$su = $row['summary'];
$img = $row['image'];

if($_SESSION['user_id'] !== $row['user_id']){
  die ("Bad User ID.");
  return;
}

// function startsWith ($string, $startString) 
// { 
//     $len = strlen($startString); 
//     return (substr($string, 0, $len) === $startString); 
// } 
// location: edit.php?profile_id='.$_GET['profile_id']

if ( isset($_POST['first_name']) && isset($_POST['last_name']) 
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

        $msg = validateProfile();
        if ( is_string($msg)){

        $_SESSION['error'] = $msg;
        header('location: edit.php?profile_id='.$_GET['profile_id']);
        return;
             

        }

        $msg = validatePos();

        if ( is_string($msg)){
          $_SESSION['error'] = $msg;
          header('location: add.php');
          return;

        }


            $stmt = $pdo->prepare('UPDATE Profile set user_id = :uid, first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su, image = :img
              WHERE profile_id = :pid');

            $stmt->execute(array(
              ':pid' => $_POST['profile_id'],
              ':uid' => $_SESSION['user_id'],
              ':fn' => $_POST['first_name'],
              ':ln' => $_POST['last_name'],
              ':em' => $_POST['email'],
              ':he' => $_POST['headline'],
              ':su' => $_POST['summary'],
              ':img' => $_POST['image'])
            );

            
            $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
            $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

            $rank = 1;
            for($i=1; $i<=9; $i++) {
              if ( ! isset($_POST['year'.$i]) ) continue;
              if ( ! isset($_POST['desc'.$i]) ) continue;

              $year = $_POST['year'.$i];
              $desc = $_POST['desc'.$i];

              $stmt = $pdo->prepare('INSERT INTO Position (profile_id, ranks, year, description) VALUES ( :pid, :ranks, :year, :desc)');

              $stmt->execute(array(
                ':pid' => $_REQUEST['profile_id'],
                ':ranks' => $rank,
                ':year' => $year,
                ':desc' => $desc)
              );

              $rank++;

            }

            $_SESSION['success'] = "Profile updated";
            header('location: index.php');
        	   return;

        
 
}
if ( isset($_POST['save']) ) {
    if ( ! isset($_POST['first_name']) || ! isset($_POST['last_name']) 
     || ! isset($_POST['email']) || ! isset($_POST['headline']) || ! isset($_POST['summary'])) {
      $stmt = $pdo->prepare('UPDATE Profile set user_id = :uid, first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su, image = :img
              WHERE profile_id = :pid');

            $stmt->execute(array(
              ':pid' => $_POST['profile_id'],
              ':uid' => $_SESSION['user_id'],
              ':fn' => $_POST['first_name'],
              ':ln' => $_POST['last_name'],
              ':em' => $_POST['email'],
              ':he' => $_POST['headline'],
              ':su' => $_POST['summary'],
              ':img' => $_POST['image'])
            );

      header('location: index.php');
    return;
  }
    
}
?>

<html>
<head>
<title>aa955ab6 Jonathan Therrian Profile Edit</title>

</head>
<body>
<div class="container">
<h1>Editing profile for <?php echo htmlentities($_SESSION['name']);?></h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
flashMessages();
$stpos = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :pid");
$stpos->execute(array(       
                ':pid' => $_GET['profile_id'],));
$posrows = $stpos->fetchall(PDO::FETCH_ASSOC);
?>
<form method="post">
    <p>First Name:
    <input type="text" name='first_name' size="60" value="<?= $fn ?>">
    </p>
    <p>Last Name:
    <input type="text" name="last_name" size="60" value="<?= $ln ?>">
    </p>
    <p>Email:
    <input type="text" name="email" size='40' value="<?= $em ?>">
    </p>
    <p>Headline:
        </br>
    <input type="text" name="headline" size='60' value="<?= $he ?>">
    </p>
    <p>Summary:
    </br>
    <textarea name="summary" rows="8" cols="80" /><?= $su ?></textarea>
    </p>
    <p>Image (url):
        </br>
    <input type="text" name="image" size='60' value="<?= $img ?>">
    </p>
    <p>Position: <input type="submit" id="addPos" value="+"></p>
    <div id="position_fields"></div>
    <?php foreach ($posrows as $j){

        echo "<div id='position".htmlentities($j['ranks'])."'>";
        echo "<p>Year: <input type='text' name='year".$j['ranks']."' value=".$j['year'].">";
        // echo "<input type='button' value='-' onclick="."$('#position2').remove();return false;>";
        echo "<input type='button' value='-' onclick="."$('#position".$j['ranks']."').remove();return false;>";
        echo "</p>";
        echo "<textarea name='desc".$j['ranks']."' rows='8' cols='80'>".$j['description']."</textarea>";
        echo "</div>";

    }?>
    <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
    <input type="submit" name="save" value="Save">
    <input type="submit" name="cancel" value="Cancel">
</form>

<script>
countPos = 0;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
});
</script>