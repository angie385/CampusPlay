<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "auth.php";
requireLogin(); 

include("db.php");

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM inscriptions WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'user_id' => $user_id
    ]);

    header("Location: mes-inscriptions.php");
    exit();
} else {
    echo "ID manquant";
}
?>