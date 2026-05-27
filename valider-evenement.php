<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $sql = "UPDATE events
            SET status = 'approved'
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);

    header("Location: gouvernance-membre.php");
    exit();

} else {

    echo "ID manquant";

}
?>