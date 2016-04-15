<?php
    require ("include/common.php");

    // This checks if the user is logged in.
    // If $_SESSION['user'] is empty then he didnt log in
    // or the session expired.
    if(empty($_SESSION['user'])) {
        // Redirects to main page if user is not logged in.
        header("Location: index.php");
        die("You are not logged in. Redirecting to index.php");
    }

    $email_taken = false;
    $user = $_SESSION['user'];
    $name = $user->getName($db);

    $query = "SELECT type FROM user WHERE email = :email";
    $query_params = array(
        ':email' => $user->getEmail()
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex) {
        die("Failed to run query." . $ex);
    }

    $row = $stmt->fetch();
    $userType = $row['type'];


    if (!empty($_POST['adm-add-user']) && $userType == 'ADM') {
        $user_name = $_POST['adm-add-name'];
        $user_email = $_POST['adm-add-email'];
        $user_type = $_POST['adm-add-usertype'];
        $user_password = $_POST['adm-add-password'];

        // Check if the email is in use
        $query = "SELECT 1 from user WHERE email = :email";
        $query_params = array(
            ':email' => $user_email
        );

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex) {
            die("Failed to run query.");
        }
        $row = $stmt->fetch();
        
        if($row) {
            $email_taken = true;
        } else {
            $query = "INSERT INTO user (password, salt, email, name, type) VALUE (:password, :salt, :email, :name, :type)";

            // Salt is a sort of key use to encrypt the password
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
            // Encrypt the password 500 times
            $encrypted_password = hash('sha256', $user_password . $salt);
            for($round = 0; $round < 500; $round++) {
                $encrypted_password = hash('sha256', $encrypted_password . $salt);
            }

            $query_params = array(
                ':password' => $encrypted_password,
                ':salt' => $salt,
                ':email' => $user_email,
                ':name' => $user_name,
                ':type' => $user_type
            );
            try  {
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            } catch(PDOException $ex) {
                die("Failed to run query." . $ex);
            }
        }
    }
?>

<!DOCTYPE HTML>
<!--
	Prologue by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Auto Repair Shop</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css">
		<style>
		table, th, td {
			border: 1px solid black;
		}
</style>
	</head>
	<body>

		<!-- Header -->
			<div id="header">

				<div class="top">

					<!-- Logo -->
						<div id="logo">
							<span class="image avatar48"><img src="images/avatar.jpg" alt="" /></span>
							<h1 id="title"><?php echo htmlentities($name, ENT_QUOTES, 'UTF-8'); ?></h1>
                            <?php if ($userType == "ADM"): ?>
                                Adminstrator
                            <?php elseif ($userType == "BKC"): ?>
                                Booking Clerk
                            <?php elseif ($userType == "MEC"): ?>
                                Mechanic
                            <?php elseif ($userType == "CSM"): ?>
                                Customer
                            <?php endif ?>
						</div>

					<!-- Nav -->
						<nav id="nav">
							<!--

								Prologue's nav expects links in one of two formats:

								1. Hash link (scrolls to a different section within the page)

								   <li><a href="#foobar" id="foobar-link" class="icon fa-whatever-icon-you-want skel-layers-ignoreHref"><span class="label">Foobar</span></a></li>

								2. Standard link (sends the user to another page/site)

								   <li><a href="http://foobar.tld" id="foobar-link" class="icon fa-whatever-icon-you-want"><span class="label">Foobar</span></a></li>

							-->
                            
							<ul>
                                <?php if ($userType == "ADM"): ?>
                                    <li><a href="#top" id="top-link" class="skel-layers-ignoreHref"><span class="icon fa-home">Intro</span></a></li>
                                    <li><a href="#adduser" id="adduser-link" class="skel-layers-ignoreHref"><span class="icon fa-th">Add User</span></a></li>
                                <?php endif ?>
								
							</ul>
							
							<ul>
                                <?php if ($userType == "MEC"): ?>
                                    <li><a href="#queue" id="queue-link" class="skel-layers-ignoreHref"><span class="icon fa-home">Car Queue</span></a></li>
									<li><a href="#history" id="history-link" class="skel-layers-ignoreHref"><span class="icon fa-th">Car History</span></a></li>
									<li><a href="#repair" id="repair-link" class="skel-layers-ignoreHref"><span class="icon fa-user">Car Repair work</span></a></li>
									<li><a href="#listparts" id="list-link" class="skel-layers-ignoreHref"><span class="icon fa-envelope">Parts List</span></a></li>
									<li><a href="#parts" id="order-link" class="skel-layers-ignoreHref"><span class="icon fa-home">Order Parts</span></a></li>
                                <?php endif ?>
								
							</ul>
						</nav>

				</div>

				<div class="bottom">

					<!-- Social Icons -->
						<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-github"><span class="label">Github</span></a></li>
							<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
							<li><a href="#" class="icon fa-envelope"><span class="label">Email</span></a></li>
						</ul>

				</div>

			</div>

		<!-- Main -->
			<div id="main">

				<!-- Intro -->
					<section id="top" class="one dark cover">
						<div class="container">

							<header>
								<h2 class="alt">Welcome!</p>
							</header>

							<footer>
								<a href="#adduser" class="button scrolly">Add User</a>
							</footer>

						</div>
					</section>

				<!-- Add User - ADMIN ONLY -->
                <?php if ($userType == "ADM"): ?>
					<section id="adduser" class="two">
						<div class="container">
							<header>
								<h2>Add User</h2>
							</header>

							<p>Here you can add users for the system:</p>
                            <?php if($email_taken): ?>
                                <p style="color: #ea102e;">Email already taken :(</p>
                            <?php endif ?>
                            <form method="post" action="#">
								<div class="row">
									<div class="6u 12u$(mobile)"><input type="text" name="adm-add-name" placeholder="Name" /></div>
									<div class="6u 12u$(mobile)"><input type="text" name="adm-add-email" placeholder="Email" /></div>
                                    <div class="6u 12u$(mobile)">
                                        <label>User Type</label>
                                        <select name="adm-add-usertype">
                                            <option value="ADM">Administrator</option>
                                            <option value="MEC">Mechanic</option>
                                            <option value="BKC">Booking Clerk</option>
                                            <option value="CSM">Customer</option>
                                        </select>
                                    </div>
                                    <div class="6u 12u$(mobile)"><label>Password</label><input type="password" name="adm-add-password" placeholder="Password" /></div>
									<div class="12u$">
										<input type="submit" name="adm-add-user" value="Add User" />
									</div>
								</div>
							</form>

						</div>
					</section>
                <?php endif ?>

			
			
			<!-- Mechanic only -->
                <?php if ($userType == "MEC"): ?>
					<!-- Car queue -->
					<section id="queue" class="three">
						<div class="container">

							<header>
								<h2>Car Queue</h2>
							</header>
						
							<p style="color:black;">Car repair queue listed below</p>
							<?php
									error_reporting(E_ERROR | E_PARSE);
							
									$servername = "localhost";
									$username = "root";
									$password = "";
									$dbname = "autorepair";

									// Create connection
									$conn = new mysqli($servername, $username, $password, $dbname);
									// Check connection
									if ($conn->connect_error) {
										 die("Connection failed: " . $conn->connect_error);
									} 

									$sql = "SELECT idrepair, mechanicEmail, position FROM repairqueue";
									$result = $conn->query($sql);

									if ($result->num_rows > 0) {
										echo "<table><tr><th>ID</th><th>Mechanic</th><th>Postion</th></tr>";
										 // output data of each row
										 while($row = $result->fetch_assoc()) {
											 echo "<tr><td>". $row["idrepair"]. "</td><td>". $row["mechanicEmail"]. "</td><td>" . $row["position"] . "</td></tr>";
										 }
										 echo "</table>";
									} else {
										 echo "0 results";
									}

									$conn->close();
									?> 
						</div>
					</section>
					
					<!-- Car history -->
					<section id="history" class="three">
						<div class="container">

							<header>
								<h2>Car history</h2>
							</header>

							<a href="#" class="image featured"><img src="images/pic08.jpg" alt="" /></a>

							<p>Select car to view history</p>
							
							<?php
									$servername = "localhost";
									$username = "root";
									$password = "";
									$dbname = "autorepair";

									// Create connection
									$conn = new mysqli($servername, $username, $password, $dbname);
									// Check connection
									if ($conn->connect_error) {
										 die("Connection failed: " . $conn->connect_error);
									} 

									$sql = "SELECT idcar, owner, make, model, colour, mileage FROM car";
									$result = $conn->query($sql);

									if ($result->num_rows > 0) 
									{
										 // output data of each row
										 echo "<table id='carhistory'><tr><th>ID</th><th>Owner</th><th>Make</th><th>Model</th><th>Colour</th><th>Mileage</th></tr>";
										 while($row = $result->fetch_assoc()) 
										 {
											echo "<tr><td>". $row["idcar"]. "</td><td>". $row["owner"]. "</td><td>" . $row["make"]. "</td><td>". $row["model"].  "</td><td>". $row["colour"].  "</td><td>". $row["mileage"].   "</td></tr>";
										 }
										 echo "</table>";
									} 
									else {
										 echo "0 results";
									}

									$conn->close();
									?>  

						</div>
					</section>
					
					<!-- Car repair work done -->
					<section id="repair" class="four">
						<div class="container">

							<header>
								<h2>Repair work</h2>
							</header>

							<p></p>

							<form method="post" action="#">
								<div class="row">
									<div class="6u 12u$(mobile)"><input type="text" name="idrep" placeholder="Car ID" /></div>
									<div class="6u$ 12u$(mobile)"><input type="text" name="price" placeholder="Price" /></div>
									<div class="12u$">
										<textarea name="commrep" placeholder="Enter work done"></textarea>
									</div>
									<div class="12u$">
										<input type="submit" value="Send Message" />
									</div>
								</div>
							</form>
							
							<?php
								$servername = "localhost";
								$username = "root";
								$password = "";
								$dbname = "autorepair";

								// Create connection
								$conn = new mysqli($servername, $username, $password, $dbname);
								// Check connection
								if ($conn->connect_error) {
									die("Connection failed: " . $conn->connect_error);
								} 

								$sql="INSERT INTO workDone (idrepair, price, comment)
								VALUES
								('$_POST[idrep]','$_POST[price]','$_POST[commrep]')";

								if ($conn->query($sql) === TRUE) {
									echo "New record created successfully";
								} else {
									echo "Error: " . $sql . "<br>" . $conn->error;
								}

								$conn->close();
								?>

						</div>
					</section>
					
				<!-- Car parts lists -->
					<section id="listparts" class="five">
						<div class="container">

							<header>
								<h2>Parts list</h2>
							</header>

							<p>Enter youre details for parts used below</p>

							<form method="post" action="#">
								<div class="row">
									<div class="12u$">
										<textarea name="message" placeholder="Message"></textarea>
									</div>
									<div class="12u$">
										<input type="submit" value="Send Message" />
									</div>
								</div>
							</form>

						</div>
					</section>
					
				<!-- order parts list -->
					<section id="parts" class="six">
						<div class="container">

							<header>
								<h2>Order parts</h2>
							</header>

							<p>Enter the parts yo wish to be ordered below</p>

							<form method="post" action="#">
								<div class="row">
								<div class="6u 12u$(mobile)"><input type="text" name="name" placeholder="Name of part" /></div>
									<div class="6u$ 12u$(mobile)"><input type="text" name="email" placeholder="Email to order" /></div>
									<div class="12u$">
										<textarea name="message" placeholder="Additional info"></textarea>
									</div>
									<div class="12u$">
										<input type="submit" value="Order part" />
										<input type="hidden" name="button_pressed" value="1" />
									</div>
								</div>
							</form>
							<?php

								if(isset($_POST['button_pressed']))
								{
									$to = $_POST['email'];
									$subject = $_POST['name'];
									$message = $_POST['message'];
									$headers = 'From: webmaster@example.com' . "\r\n" .
										'Reply-To: webmaster@example.com' . "\r\n" .
										'X-Mailer: PHP/' . phpversion();

									mail($to, $subject, $message, $headers);

									echo 'Email Sent.';
								}
								?>
						</div>
					</section>
                <?php endif ?>
				
			</div>

		<!-- Footer -->
			<div id="footer">

				<!-- Copyright -->
					<ul class="copyright">
						<li>&copy; Auto Repair Shop. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollzer.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
