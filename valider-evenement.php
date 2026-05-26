<?php
include("db.php");

$id = $_GET['id'];

$sql = "UPDATE events
        SET status='approved'
        WHERE id='$id'";

mysqli_query($conn, $sql);

header("Location: gouvernance-membre.php");
exit();
?>