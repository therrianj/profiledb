
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
require_once "bootstrap.php";
require_once "curlTestImage.php";
require_once "util.php";





if ( isset($_POST['first_name']) && isset($_POST['last_name']) 
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

        $msg = validateProfile();

        if ( is_string($msg)){
          $_SESSION['error'] = $msg;
          header('location: add.php');
          return;


        } 

        $msg = validatePos();

        if ( is_string($msg)){
          $_SESSION['error'] = $msg;
          header('location: add.php');
          return;

        }

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

            $profile_id = $pdo->lastInsertId();

            $rank = 1;
            for($i=1; $i<=9; $i++) {
              if ( ! isset($_POST['year'.$i]) ) continue;
              if ( ! isset($_POST['desc'.$i]) ) continue;

              $year = $_POST['year'.$i];
              $desc = $_POST['desc'.$i];

              $stmt = $pdo->prepare('INSERT INTO Position (profile_id, ranks, year, description) VALUES ( :pid, :ranks, :year, :desc)');

              $stmt->execute(array(
                ':pid' => $profile_id,
                ':ranks' => $rank,
                ':year' => $year,
                ':desc' => $desc)
              );

              $rank++;

            }



            $_SESSION['success'] = "Profile added";
            header('location: index.php');
        	return;

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
flashMessages();

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
    <p>Position: <input type="submit" id="addPos" value="+"></p>
    <div id="position_fields"></div>
    <input type="submit" name="add" value="Add">
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