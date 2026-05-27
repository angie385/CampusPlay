<?php
header("Content-Type: application/json; charset=utf-8");
require_once "db.php";

$method = $_SERVER["REQUEST_METHOD"];

/* ===============================
   FONCTION UPLOAD IMAGE
================================ */
function uploadImage() {
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        return null;
    }

    $uploadDir = __DIR__ . "/images/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp = $_FILES["image"]["tmp_name"];
    $fileName = $_FILES["image"]["name"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExtensions = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($fileExt, $allowedExtensions)) {
        echo json_encode([
            "success" => false,
            "message" => "Format d'image non autorisé. Utilisez JPG, PNG ou WEBP."
        ]);
        exit;
    }

    $newFileName = "event_" . time() . "_" . rand(1000, 9999) . "." . $fileExt;
    $destination = $uploadDir . $newFileName;

    if (!move_uploaded_file($fileTmp, $destination)) {
        echo json_encode([
            "success" => false,
            "message" => "Erreur lors de l'enregistrement de l'image."
        ]);
        exit;
    }

    return $newFileName;
}

/* ===============================
   GET : RECUPERER LES EVENEMENTS
================================ */
if ($method === "GET") {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC, event_time ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "events" => $events
    ]);
    exit;
}

/* ===============================
   DELETE : SUPPRIMER UN EVENEMENT
================================ */
if ($method === "DELETE") {
    $id = $_GET["id"] ?? null;

    if (!$id) {
        echo json_encode([
            "success" => false,
            "message" => "ID manquant."
        ]);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
    $stmt->execute([
        ":id" => $id
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Événement supprimé avec succès."
    ]);
    exit;
}

/* ===============================
   POST / PUT AVEC FORMDATA
================================ */
$action = $_POST["_method"] ?? "POST";

/* ===============================
   POST : AJOUTER UN EVENEMENT
================================ */
if ($method === "POST" && $action === "POST") {
    $imageName = uploadImage();

    $stmt = $pdo->prepare("
        INSERT INTO events 
        (name, category, event_date, event_time, place, capacity, registered, status, resource, description, image)
        VALUES 
        (:name, :category, :event_date, :event_time, :place, :capacity, 0, :status, :resource, :description, :image)
    ");

    $stmt->execute([
        ":name" => $_POST["name"],
        ":category" => $_POST["category"],
        ":event_date" => $_POST["event_date"],
        ":event_time" => $_POST["event_time"],
        ":place" => $_POST["place"],
        ":capacity" => $_POST["capacity"],
        ":status" => $_POST["status"],
        ":resource" => $_POST["resource"],
        ":description" => $_POST["description"],
        ":image" => $imageName
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Événement ajouté avec succès."
    ]);
    exit;
}

/* ===============================
   PUT : MODIFIER UN EVENEMENT
================================ */
if ($method === "POST" && $action === "PUT") {
    $id = $_POST["id"] ?? null;

    if (!$id) {
        echo json_encode([
            "success" => false,
            "message" => "ID manquant pour la modification."
        ]);
        exit;
    }

    $imageName = uploadImage();

    if ($imageName) {
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
                description = :description,
                image = :image
            WHERE id = :id
        ");

        $stmt->execute([
            ":id" => $id,
            ":name" => $_POST["name"],
            ":category" => $_POST["category"],
            ":event_date" => $_POST["event_date"],
            ":event_time" => $_POST["event_time"],
            ":place" => $_POST["place"],
            ":capacity" => $_POST["capacity"],
            ":status" => $_POST["status"],
            ":resource" => $_POST["resource"],
            ":description" => $_POST["description"],
            ":image" => $imageName
        ]);
    } else {
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
            ":id" => $id,
            ":name" => $_POST["name"],
            ":category" => $_POST["category"],
            ":event_date" => $_POST["event_date"],
            ":event_time" => $_POST["event_time"],
            ":place" => $_POST["place"],
            ":capacity" => $_POST["capacity"],
            ":status" => $_POST["status"],
            ":resource" => $_POST["resource"],
            ":description" => $_POST["description"]
        ]);
    }

    echo json_encode([
        "success" => true,
        "message" => "Événement modifié avec succès."
    ]);
    exit;
}

echo json_encode([
    "success" => false,
    "message" => "Méthode non autorisée."
]);
?>