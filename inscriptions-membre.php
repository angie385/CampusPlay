<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "auth.php";
requireLogin();
include("db.php");

$user_id = $_SESSION['user_id'];

if(isset($_POST['inscrire'])) {
    $event_id = intval($_POST['event_id']);
	
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
        $message = "Vous êtes déjà inscrit à cet événement.";
    }
}

$eventsQuery = $pdo->prepare("SELECT * FROM events WHERE status = 'approved' OR status = 'active' ORDER BY event_date ASC");
$eventsQuery->execute();
$events = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Inscriptions</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5; 
            padding: 20px; 
        }
        .card { 
            background: white; 
            padding: 20px; 
            margin-bottom: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        .message {
            padding: 10px;
            background: #d4edda;
            color: #155724;
            border-radius: 5px;
            margin-bottom: 20px;
        }
		.error {
			color: #ff0000;
			font-weight: bold;
			}
        button { 
            background: #007bff; 
            color: white; 
            border: none; 
            padding: 10px 15px; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<h1>Événements disponibles</h1>

<?php if(isset($message)): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if(count($events) > 0): ?>
    <?php foreach($events as $event): ?>
    <div class="card">
        <h2><?php echo htmlspecialchars($event['name']); ?></h2>
        <p><?php echo htmlspecialchars($event['description']); ?></p>
        <p><strong>Date :</strong> Le <?php echo htmlspecialchars($event['event_date']); ?> à <?php echo htmlspecialchars($event['event_time']); ?></p>
        
        <form method="POST">
            <input type="hidden" name="event_id" value="<?php echo (int)$event['id']; ?>">
            <button type="submit" name="inscrire">S'inscrire</button>
        </form>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun événement disponible pour le moment.</p>
<?php endif; ?>

</body>
</html>