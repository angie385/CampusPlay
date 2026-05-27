<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "auth.php";
requireLogin();
include("db.php");

$sql = "SELECT * FROM events";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gouvernance</title>
    <style>
        table { 
			width:100%; 
			border-collapse: collapse; 
			font-family: Arial, sans-serif; 
			}
        th, td { 
			border:1px solid #ddd; 
			padding:12px; 
			text-align: left; 
			}
        th { 
			background-color: #f2f2f2; 
			}
        .approve { 
			background:green; 
			color:white; 
			padding:5px 10px; 
			text-decoration:none; 
			border-radius:3px; 
			}
        .reject { 
			background:red; 
			color:white; 
			padding:5px 10px; 
			text-decoration:none; 
			border-radius:3px; 
			}
    </style>
</head>
<body>

<h1>Administration Gouvernance</h1>

<table>
<tr>
    <th>Nom</th>
    <th>Date</th>
    <th>Statut</th>
    <th>Actions</th>
</tr>

<?php foreach($result as $event) { ?>
<tr>
    <td><?php echo htmlspecialchars($event['name']); ?></td>
    <td><?php echo htmlspecialchars($event['event_date']); ?></td>
    <td><?php echo htmlspecialchars($event['status']); ?></td>
    <td>
        <a class="approve" href="valider-evenement.php?id=<?php echo $event['id']; ?>">Valider</a>
        <a class="reject" href="refuser-evenement.php?id=<?php echo $event['id']; ?>">Refuser</a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>