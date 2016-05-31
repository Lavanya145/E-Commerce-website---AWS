
<?php

//if(isset($_SESSION['login_user'])){
//    header("location: profile.php");
//}
ini_set('display_errors', 'On');
error_reporting(E_ALL);
ob_start();

if (isset($_POST['submit'])) 
{
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Username or Password is invalid";
    }
    else
    {
// Define $username and $password
        $username=$_POST['username'];
        $password=md5($_POST['password']);
		//$password = md5($password);
// Establishing Connection with CloudSQL
      
		$conn = mysql_connect(':/cloudsql/valid-shine-93823:shine','lav','lav123');
		if(! $conn )
		{
			die('Could not connect: ' . mysql_error());
		}
		echo 'Connected successfully';
	
		$sql = "select * from User"."where Username='$username'";
		mysql_select_db('lavdb');
		$result= mysql_query( $sql, $conn );	
		$user = $result['Item']['username']['S'];
		if($user == $username)
		{
			?>
			<script type='text/javascript'> alert('Username already exists');</script>
			<?php
			header('Location: register.php');// Redirecting To Home Page
			}
		else{
// Inserting Data into database
				$sql = "INSERT INTO User".
				"(Username,Password)".
				"VALUES ('$username','$password' )";

				mysql_select_db('lavdb');
				$returnval = mysql_query( $sql, $conn );
				if(! $returnval )
				{
					die('Could not enter data: ' . mysql_error());
				}
				echo "Registered successfully\n";
				mysql_close($conn); 
				
			 

    }
}
?>
<html>
<head>
    <title>Register</title>
    <link href="/css/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main">
    <h1>Google App engine</h1>
    <div id="login">
        <h2><a href ="index.php">Go to Home Page and Signin (or)</a></br>Signup</h2>
        <form action="register.php" method="post">
            <label>Username :</label>
            <input id="name" name="username" placeholder="username" type="text"></br>
            <label>Password :</label>
            <input id="password" name="password" placeholder="**********" type="password"></br>
            <label>Repeat Pwd :</label>
            <input id="repeatpwd" name="repeatpwd" placeholder="**********" type="password"></br></br>
            <input name="submit" type="submit" value=" Register ">
            
        </form>
    </div>
</div>
</body>
</html>

