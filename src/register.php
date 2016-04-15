<?php
    require("include/common.php");
    $email_taken = false;
    if(!empty($_POST)) {
        // Check if the email is in use
        $query = "SELECT 1 from user WHERE email = :email";
        $query_params = array(
            ':email' => $_POST['email']
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
            $query = "INSERT INTO user (password, salt, email, name) VALUE (:password, :salt, :email, :name)";

            // Salt is a sort of key use to encrypt the password
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
            // Encrypt the password 500 times
            $password = hash('sha256', $_POST['password'] . $salt);
            for($round = 0; $round < 500; $round++) {
                $password = hash('sha256', $password . $salt);
            }

            $query_params = array(
                ':password' => $password,
                ':salt' => $salt,
                ':email' => $_POST['email'],
                ':name' => $_POST['name']
            );
            try  {
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            }
            catch(PDOException $ex) {
                die("Failed to run query." . $ex);
            }
            header("Location: index.php");
            die("Redirecting to index.php");
        }
    }
?>

<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel='stylesheet prefetch' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
    <link rel="stylesheet" href="css/style.css">

    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
</head>

<body>
    <div class="login-card">
        <h1>Register</h1>
        <?php if($email_taken): ?>
            <div style="background-color: #ea102e; color: white; text-align: center;">Email Taken.</div><br>
        <?php endif ?>
        <form method="post">
            <input type="text" name="name" placeholder="Your Name">
            <input type="text" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" name="register" class="login login-submit" value="register">
        </form>

        <div class="login-help">
            <a href="index.php">Login</a> • <a href="#">Forgot Password</a>
        </div>
    </div>


</body>
</html>
