<?php
require_once("config.php");

session_start();
$username = null;
$password = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//print_r($_POST); exit;
	if(!empty($_POST["username"]) && !empty($_POST["password"])) {
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		if($username == LOGIN_USER && $password == LOGIN_PASSW) {
			$_SESSION["authenticated"] = 'true';
			header('Location: index.php');
		}
		else {
			header('Location: login.php');
		}
		
	} else {
		header('Location: login.php');
	}
} else if ((!empty($_SESSION)) && ($_SESSION["authenticated"] == 'true')) {
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/style.css" rel="stylesheet">
</head>
<body>
  
  <form class="modal-content animate" method="POST" action="login.php">
    <div class="imgcontainer">
       <img src="img/icons-login.png" alt="Login" class="avatar">
    </div>

    <div class="container">
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="username" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>
        
      <button type="submit">Login</button>
    </div>

  </form>

</body>
</html>
