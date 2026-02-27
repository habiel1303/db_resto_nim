<?php 
session_start();
if(!isset($_SESSION['user']) AND !isset($_COOKIE['coo_user'])){
    header("location:http://localhost/db_resto_nim/login.php");
}
?>
