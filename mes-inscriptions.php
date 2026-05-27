<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "auth.php";
requireLogin();
include("db.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT events.name,
               events.event_date,
               inscriptions.id
        FROM inscriptions
        JOIN events ON inscriptions.evenement_id = events.id
        WHERE inscriptions.user_id = :user_id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes inscriptions</title>
    <style>
        table { 
			width:100%; 
			border-collapse: collapse; 
			font-family: Arial; 
			}
        th, td { 
			border:1px solid black; 
			padding:10px; 
			}
        th { 
			background:#eee; 
			}
    </style>
</head>
<body>

<h1>Mes inscriptions</h1>

<table>
<tr>
    <th>Événement</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php foreach($result as $row) { ?>
<tr>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['event_date']); ?></td>
    <td>
        <a href="annuler-inscription.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Annuler cette inscription ?');">
            Annuler
        </a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>