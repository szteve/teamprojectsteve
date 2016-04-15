<?php
    require("include/common.php");

    $submitted_email = '';
    $login_failed = false;

    if(!empty($_POST['login'])) {
        $query = "SELECT name, password, salt, email FROM user WHERE email = :email";
        $query_params = array(
            ':email' => $_POST['email']
        );

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex) {
            // We should not print the PDOException to the user but it is
            // usefull for debbuging. Remove this in the final release.
            die("Failed to run query." . $ex);
        }

        $row = $stmt->fetch();

        // If there is a user (query returned something) check the password
        if($row) {
            $hashed_password = hash('sha256', $_POST['password'] . $row['salt']);
            for($round = 0; $round < 500; $round++) {
                $hashed_password = hash('sha256', $hashed_password . $row['salt']);
            }

            // If this check returns true, the login was successfull.
            if($hashed_password === $row['password']) {
                // Removes the password and the salt for precaution.
                unset($row['salt']);
                unset($row['password']);

                $user = new User($row['email']);
                $_SESSION['user'] = $user;

                // header redirects the page
                header("Location: home.php");
                die("Redirecting to: home.php");
            } else {
                $login_failed = true;
            }
        } else {
            $login_failed = true;
        }

        if ($login_failed) {
            // Save the email so the user doesnt have to re-type it.
            $submitted_email = $_POST['email'];
        }
    }
?>

<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Log-in</title>
    <link rel='stylesheet prefetch' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
    <link rel="stylesheet" href="css/style.css">

    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
</head>

<body>
    <div class="login-card">
        <h1>Log-in</h1>
         <?php if($login_failed): ?>
             <div style="background-color: #ea102e; color: white; text-align: center;">Login Failed.</div><br>
         <?php endif ?>
        <form method="post">
            <input type="text" name="email" placeholder="Email" >
            <input type="password" name="password" placeholder="Password">
            <input type="submit" name="login" class="login login-submit" value="login">
        </form>

        <div class="login-help">
            <a href="register.php">Register</a> • <a href="#">Forgot Password</a>
        </div>
    </div>


</body>
</html>
