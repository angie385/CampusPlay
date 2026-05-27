<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $sql = "DELETE FROM inscriptions WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);

    header("Location: mes-inscriptions.php");
    exit();

} else {

    echo "ID manquant";

}
?>