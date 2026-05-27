<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

$sql = "SELECT * FROM events";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gouvernance</title>

    <style>

        table{
            width:100%;
            border-collapse: collapse;
        }

        th, td{
            border:1px solid black;
            padding:10px;
        }

        .approve{
            background:green;
            color:white;
            padding:5px;
        }

        .reject{
            background:red;
            color:white;
            padding:5px;
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

<td><?php echo $event['titre']; ?></td>

<td><?php echo $event['date_event']; ?></td>

<td><?php echo $event['status']; ?></td>

<td>

<a class="approve"
href="valider-evenement.php?id=<?php echo $event['id']; ?>">
Valider
</a>

<a class="reject"
href="refuser-evenement.php?id=<?php echo $event['id']; ?>">
Refuser
</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>