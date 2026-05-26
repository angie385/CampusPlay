<?php
session_start();

function isConnected() {
    return isset($_SESSION["user_id"]);
}

function getRole() {
    return $_SESSION["role"] ?? "visiteur";
}

function requireLogin() {
    if (!isConnected()) {
        header("Location: connexion.php");
        exit;
    }
}

function requireMember() {
    requireLogin();

    if (getRole() !== "membre") {
        header("Location: activite.php");
        exit;
    }
}
?>