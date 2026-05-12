<?php
$conn = mysqli_connect("localhost", "root", "", "myDB");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>