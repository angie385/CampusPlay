<?php
session_start();
include("db.php");

$id = $_GET['id'];

$sql = "DELETE FROM inscriptions WHERE id='$id'";

mysqli_query($conn, $sql);

header("Location: mes-inscriptions.php");
exit();
?>