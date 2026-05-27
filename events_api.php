<?php
header("Content-Type: application/json; charset=utf-8");
require_once "db.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC, event_time ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "events" => $events
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if ($method === "POST") {
    $stmt = $pdo->prepare("
        INSERT INTO events 
        (name, category, event_date, event_time, place, capacity, status, resource, description)
        VALUES 
        (:name, :category, :event_date, :event_time, :place, :capacity, :status, :resource, :description)
    ");

    $stmt->execute([
        ":name" => $data["name"],
        ":category" => $data["category"],
        ":event_date" => $data["event_date"],
        ":event_time" => $data["event_time"],
        ":place" => $data["place"],
        ":capacity" => $data["capacity"],
        ":status" => $data["status"],
        ":resource" => $data["resource"],
        ":description" => $data["description"]
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Événement ajouté avec succès"
    ]);
    exit;
}

if ($method === "PUT") {
    $stmt = $pdo->prepare("
        UPDATE events SET
            name = :name,
            category = :category,
            event_date = :event_date,
            event_time = :event_time,
            place = :place,
            capacity = :capacity,
            status = :status,
            resource = :resource,
            description = :description
        WHERE id = :id
    ");

    $stmt->execute([
        ":id" => $data["id"],
        ":name" => $data["name"],
        ":category" => $data["category"],
        ":event_date" => $data["event_date"],
        ":event_time" => $data["event_time"],
        ":place" => $data["place"],
        ":capacity" => $data["capacity"],
        ":status" => $data["status"],
        ":resource" => $data["resource"],
        ":description" => $data["description"]
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Événement modifié avec succès"
    ]);
    exit;
}

if ($method === "DELETE") {
    $id = $_GET["id"] ?? null;

    if (!$id) {
        echo json_encode([
            "success" => false,
            "message" => "ID manquant"
        ]);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
    $stmt->execute([":id" => $id]);

    echo json_encode([
        "success" => true,
        "message" => "Événement supprimé avec succès"
    ]);
    exit;
}

echo json_encode([
    "success" => false,
    "message" => "Méthode non autorisée"
]);
?>