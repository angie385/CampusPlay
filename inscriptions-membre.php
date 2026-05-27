<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "auth.php";
requireLogin();
include("db.php");

$user_id = $_SESSION['user_id'];

if(isset($_POST['inscrire'])) {
    $event_id = $_POST['event_id'];

    $check = "SELECT * FROM inscriptions WHERE user_id = :user_id AND evenement_id = :event_id";
    $stmtCheck = $pdo->prepare($check);
    $stmtCheck->execute([
        'user_id' => $user_id,
        'event_id' => $event_id
    ]);

    if($stmtCheck->rowCount() == 0) {
        $sql = "INSERT INTO inscriptions(user_id, evenement_id) VALUES(:user_id, :event_id)";
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

$eventsQuery = $pdo->prepare("SELECT * FROM events WHERE status = 'approved' OR status = 'active'");
$eventsQuery->execute();
$events = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Inscriptions</title>
    <style>
        body { 
			font-family: Arial; 
			background:#f5f5f5; 
			padding:20px; 
			}
        .card { 
			background:white; 
			padding:20px; 
			margin-bottom:20px; 
			border-radius:10px; 
			box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
			}
        button { 
			background:#007bff; 
			color:white; 
			border:none; 
			padding:10px 15px; 
			border-radius:5px; 
			cursor:pointer; 
			}
    </style>
</head>
<body>

<h1>Événements disponibles</h1>

<?php if(isset($message)) { echo "<p><strong>$message</strong></p>"; } ?>

<?php foreach($events as $event) { ?>
<div class="card">
    <h2><?php echo htmlspecialchars($event['name']); ?></h2>
    <p><?php echo htmlspecialchars($event['description']); ?></p>
    <p>Date : <?php echo htmlspecialchars($event['event_date']); ?> à <?php echo htmlspecialchars($event['event_time']); ?></p>
    
    <form method="POST">
        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
        <button type="submit" name="inscrire">S'inscrire</button>
    </form>
</div>
<?php } ?>

</body>
</html>