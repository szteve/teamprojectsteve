<?php
    /*
     * This file will always need to be included when there is the need for a
     * database connection. It sets the parameters and creates the connection.
    */

    // Since in every page we will need the user information, include it here.
    require ("user.php");

    //Database Information
    $username = "root";
    $password = "";
    $host = "localhost";
    $dbname = "autorepair";

    //Comunicate using UTF8
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    try {
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
    } catch (PDOException $ex) {
        die("Failed to connect to the database.");
    }

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    header('Content-Type: text/html; charset=utf-8');
    session_start();

    // You don't close the php tag here because this file will always be included
    // in other files and never executed on its own.
