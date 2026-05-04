<?php
$conn = mysqli_connect("localhost", "root", "", "hailshare");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>