<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);
session_start(); // Starting Session

ob_start();

if (isset($_POST['submit'])) 
{

    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo  "Username or Password is invalid";
    }
    else
    {
// Define $username and $password
        $username=$_POST['username'];
        $password=$_POST['password'];      
		$_SESSION['username'] =$username;
		

		$conn = mysql_connect(':/cloudsql/valid-shine-93823:shine','lav','lav123');
		if(! $conn )
		{
			die('Could not connect: ' . mysql_error());
		}
		echo 'Connected successfully';
		
		$sql = "SELECT * from User where Username = '$username';";
		
		mysql_select_db('lavdb');
		$result= mysql_query( $sql, $conn );	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$user = $row['Username'];
		$pwd = $row['Password'];
		
	
		if($user == $username AND $pwd == $password)
		{
			while (ob_get_status()) 
			{
				ob_end_clean();
			}
			header("location: profile.php");
			
		}
		else
		{
			?>
			<script type='text/javascript'> alert('Invalid Credentials!');</script>
			<?php
		}

		mysql_close($conn);
	}
}
?>


<html>
<head>
    <title>Login</title>
    <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body >

<div id="main">
    <h1>Google App Engine</h1>
    <div class="login">
        
        <form action="index.php" method="post">
            <label>Username :</label>
            <input id="name" name="username" placeholder="username" type="text"></br></br>
            <label>Password :</label>
            <input id="password" name="password" placeholder="**********" type="password"></br></br>
            <input name="submit" type="submit" value=" Login ">
            <button type="reset" value="Reset">Reset</button></br></br>
			<h3>New user?<a href ="/register.php">Signup</a></h3>
        </form>
    </div>
</div>
</body>
</html>
