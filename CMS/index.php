<?php 
    session_start();
    include 'inc/connection.php';
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Library Management System</title>
	<link rel="stylesheet" href="inc/css/bootstrap.min.css">
	<link rel="stylesheet" href="inc/css/fontawesome-all.min.css">
	<link rel="stylesheet" href="inc/css/pro1.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600" rel="stylesheet">
    <style>
        .login{
            background-image: url(inc/img/3.jpg);
            margin-bottom: 30px;
            padding: 50px;
            padding-bottom: 70px;
        }
        .reg-header h2{
            color: #DDDDDD;
            z-index: 999999;
        }
        .login-body h4{
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
	<div class="login registration">
		<div class="wrapper">
			<div class="reg-header text-center">
				<h2>Library management system</h2>
				<div class="gap-30"></div>
                <div class="gap-30"></div>
			</div>
			<div class="gap-30"></div>
			<div class="login-content">
				<div class="login-body">
					<form action="" method="post">
                    <div class="login-body">
                    <h4>User Login Form</h4>
					<form action="" method="post">
						<div class="mb-20">
							<input type="text" name="username" class="form-control" placeholder="Username" required=""/>
						</div>
						<div class="mb-20">
							<input type="password" name="password" class="form-control" placeholder="Password" required=""/>
						</div>
						<div class="mb-20">
							<input class="btn btn-info submit" type="submit" name="login" value="Login">
                            <a href="registration.php" class="text-right"> Create Account </a>
						</div>
					</form>
				</div>
					</form>
				</div>
                <?php
                if (isset($_POST["login"])) {
                    $username = $_POST["username"];
                    $password = $_POST["password"];

                    // Retrieve the hashed password from the database for the given username
                    $result = mysqli_query($link, "SELECT password FROM users WHERE username='$username'");
                    $row = mysqli_fetch_assoc($result);
                    $hashed_password = $row['password'];

                    // Verify the user-provided password with the hashed password
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION["username"] = $username;
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        echo '<div class="alert alert-warning">
                                    <strong style="color:#333">Invalid!</strong> <span style="color: red;font-weight: bold; ">Username or Password.</span>
                                </div>';
                    }
                }
                ?>
			</div>
		</div>
	</div>
    <div class="footer text-center">
        <p>&copy; All rights reserved Maul</p>
    </div>

	<script src="inc/js/jquery-2.2.4.min.js"></script>
	<script src="inc/js/bootstrap.min.js"></script>
	<script src="inc/js/custom.js"></script>
</body>
</html>