<?php

$host = "localhost";    //we create a var with the address of the base
$db = "schooldb";    // make a var with the name of the base that we want to "work"
$user = "root";         // the username that i connect on the base
$pass = "";             // password of the base
$charset = "utf8mb4";   // the language that the base read

$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; //a way to open connection with db


try { //try to make a conneciton 
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "you have a conn!"; //TODO: test to find out if we are in
} catch (PDOException $e) {
    $pdo = null;
    die("invalid access: " . $e->getMessage());
}