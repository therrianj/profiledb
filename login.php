<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: logout.php");
    return;
}
require_once 'pdo.php';


$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is meow123 for hash 'a8609e8d62c043243c4e201cbb342862'

// $failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION['email']);
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User Name and password are required";
        header("Location: login.php");
        return;
        }
        
     elseif (strpos ($_POST['email'], '@') == false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
        
    }
    else {
        $check = hash('md5', $salt.$_POST['pass']);

        $stmt = $pdo->prepare('SELECT user_id, name FROM users

        WHERE email = :em AND password = :pw');

        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        if ( $row !== false ) {

            $_SESSION['name'] = $row['name'];

            $_SESSION['user_id'] = $row['user_id'];

            header("Location: index.php");

            return;
        } else {
            error_log("Login fail ".$_POST['email']." $check");
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;

        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>aa955ab6 Jonathan Therrian's Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>

<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
}
?>
<form method="post">
    <label for="nam">Email</label>
    <input type="text" name="email" id="email"><br/>
    <label for="id_1723">Password</label>
    <input type="text" name="pass" id="id_1723"><br/>
    <input type="submit" onclick="return doValidate();" value="Log In">
    <input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The p h p 123. -->
</p>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        var addr = document.getElementById('email').value;
        var pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    
}
</script>
</div>
</body>
