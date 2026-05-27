<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT events.titre,
               events.date_event,
               inscriptions.id

        FROM inscriptions

        JOIN events
        ON inscriptions.evenement_id = events.id

        WHERE inscriptions.user_id = :user_id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'user_id' => $user_id
]);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mes inscriptions</title>

    <style>

        table{
            width:100%;
            border-collapse: collapse;
        }

        th, td{
            border:1px solid black;
            padding:10px;
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

<td><?php echo $row['titre']; ?></td>

<td><?php echo $row['date_event']; ?></td>

<td>

<a href="annuler-inscription.php?id=<?php echo $row['id']; ?>">
    Annuler
</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>