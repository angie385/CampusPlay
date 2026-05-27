<?php
require_once "auth.php";
requireLogin();
require_once "db.php";

$userId = $_SESSION["user_id"];
$id = intval($_POST["id"] ?? 0);

$stmt = $pdo->prepare("
    UPDATE notifications
    SET is_read = 1
    WHERE id = ? AND user_id = ?
");

$stmt->execute([$id, $userId]);