<?php

class User {
    protected $_email;

    public function __construct($email) {
       $this->_email = $email;
    }

    public function getName($db) {
        // Query to get the name
        $query = "SELECT name FROM user WHERE email = :email";
        // The parameters, use this to avoid SQL Injection
        $query_params = array(
            ':email' => $this->_email
        );

        // Execute the query
        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex) {
            // Change this later.
            die("Failed to run query." . $ex);
        }

        // Fetch the result and return
        $rows = $stmt->fetch();
        return $rows['name'];
    }

    public function getEmail() {
        return $this->_email;
    }

}

?>
