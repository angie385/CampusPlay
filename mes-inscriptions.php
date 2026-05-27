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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes inscriptions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #222;
            padding: 40px;
        }
        h1 {
            color: #2563eb;
            margin-bottom: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        th, td { 
            border: 1px solid #e5e7eb; 
            padding: 12px 15px; 
            text-align: left;
        }
        th { 
            background: #f3f4f6; 
            color: #4b5563;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f9fafb;
        }
        .btn-delete {
            color: #dc2626;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-delete:hover {
            text-decoration: underline;
        }
        .no-data {
            padding: 20px;
            background: white;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
            color: #6b7280;
        }
    </style>
</head>
<body>

<h1>Mes inscriptions</h1>

<?php if (empty($result)): ?>
    <div class="no-data">
        Vous n'êtes inscrit à aucun événement pour le moment.
    </div>
<?php else: ?>
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
            <a href="annuler-inscription.php?id=<?php echo $row['id']; ?>" 
               class="btn-delete"
               onclick="return confirm('Voulez-vous vraiment annuler cette inscription ?');">
                Annuler
            </a>
        </td>
    </tr>
    <?php } ?>
    </table>
<?php endif; ?>

</body>
</html>