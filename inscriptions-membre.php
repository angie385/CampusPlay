<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    die("Utilisateur non connecté");
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['inscrire'])) {

    $event_id = $_POST['event_id'];

    $check = "SELECT * FROM inscriptions
              WHERE user_id = :user_id
              AND evenement_id = :event_id";

    $stmtCheck = $pdo->prepare($check);

    $stmtCheck->execute([
        'user_id' => $user_id,
        'event_id' => $event_id
    ]);

    if($stmtCheck->rowCount() == 0) {

        $sql = "INSERT INTO inscriptions(user_id, evenement_id)
                VALUES(:user_id, :event_id)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'user_id' => $user_id,
            'event_id' => $event_id
        ]);

        $message = "Inscription réussie !";

    } else {

        $message = "Vous êtes déjà inscrit.";
    }
}

$eventsQuery = $pdo->prepare("SELECT * FROM events");

$eventsQuery->execute();

$events = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion Inscriptions</title>

    <style>

        body{
            font-family: Arial;
            background:#f5f5f5;
            padding:20px;
        }

        .card{
            background:white;
            padding:20px;
            margin-bottom:20px;
            border-radius:10px;
        }

        button{
            background:#007bff;
            color:white;
            border:none;
            padding:10px;
            border-radius:5px;
        }

    </style>
</head>

<body>

<h1>Événements disponibles</h1>

<?php
if(isset($message)){
    echo "<p>$message</p>";
}
?>

<?php foreach($events as $event) { ?>

<div class="card">

    <h2><?php echo $event['titre']; ?></h2>

    <p><?php echo $event['description']; ?></p>

    <p>Date : <?php echo $event['date_event']; ?></p>

    <form method="POST">

        <input type="hidden"
               name="event_id"
               value="<?php echo $event['id']; ?>">

        <button type="submit" name="inscrire">
            S'inscrire
        </button>

    </form>

</div>

<?php } ?>

</body>
</html>