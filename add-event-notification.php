<?php
require_once "auth.php";
requireLogin();
require_once "db.php";
require_once "notification_helper.php";

$userId = $_SESSION["user_id"];
$title = $_POST["title"] ?? "";

if ($title !== "") {
    addNotification(
        $pdo,
        $userId,
        "Inscription confirmée",
        "Votre inscription à l’événement « " . $title . " » a bien été prise en compte.",
        "success",
        "activite.php"
    );
}