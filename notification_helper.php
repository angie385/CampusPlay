<?php
function addNotification($pdo, $userId, $title, $message, $type = "info", $link = null) {
    $stmt = $pdo->prepare("
        INSERT INTO notifications (user_id, title, message, type, link, is_read)
        VALUES (?, ?, ?, ?, ?, 0)
    ");

    $stmt->execute([
        $userId,
        $title,
        $message,
        $type,
        $link
    ]);
}
?>