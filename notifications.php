<?php
require_once "auth.php";
requireLogin();
require_once "db.php";

$role = getRole();
$userId = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["mark_all_read"])) {
        $stmt = $pdo->prepare("
            UPDATE notifications
            SET is_read = 1
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
    }

    if (isset($_POST["delete_read"])) {
        $stmt = $pdo->prepare("
            DELETE FROM notifications
            WHERE user_id = ? AND is_read = 1
        ");
        $stmt->execute([$userId]);
    }
}

$stmtUser = $pdo->prepare("SELECT phone, gender FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

if (empty($user["phone"]) || empty($user["gender"])) {
    $profileNotification = true;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([$userId]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>OffCampus - Notifications</title>

<style>
		/*  ======== RESET GLOBAL & BASE  ========== */

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: Arial, sans-serif;
		}

		/*  ======== BASE BODY & LAYOUT  ========== */

		body {
			background: #f7f8fc;
			color: #15162b;
		}

		.container {
			display: flex;
			min-height: 100vh;
		}


		/*  ======== SIDEBAR  ========== */

		.sidebar {
			width: 230px;
			height: 100vh;
			position: sticky;
			top: 0;
			background: white;
			padding: 30px 22px;
			border-right: 1px solid #e5e7eb;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			flex-shrink: 0;
		}

		.logo {
			display: flex;
			justify-content: center;
			align-items: center;
			margin-bottom: 40px;
			width: 100%;
		}

		.logo img {
			width: 150px;
			height: 150px;
			object-fit: contain;
		}

		.menu a {
			display: block;
			text-decoration: none;
			color: #555;
			padding: 12px 15px;
			border-radius: 14px;
			margin-bottom: 10px;
			font-weight: 600;
		}

		.menu a:hover,
		.menu a.active {
			background: #edf0ff;
			color: #4f63e8;
		}

		.user-box {
			display: flex;
			align-items: center;
			gap: 12px;
			border-top: 1px solid #e5e7eb;
			padding-top: 22px;
			cursor: pointer;
		}

		.avatar {
			width: 58px;
			height: 58px;
			border-radius: 50%;
			background: #4f63e8;
			color: white;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 22px;
			font-weight: bold;
			flex-shrink: 0;
		}

		.user-box strong {
			font-size: 15px;
			color: #15162b;
			display: block;
			line-height: 1.2;
		}

		.user-box p {
			font-size: 14px;
			color: #555;
			margin-top: 2px;
			line-height: 1.2;
		}


		/*  ======== MAIN CONTENT  ========== */

		.main {
			flex: 1;
			padding: 40px;
		}

		.page-header {
			margin-bottom: 30px;
		}

		.page-header h1 {
			font-size: 42px;
			margin-bottom: 10px;
		}

		.page-header p {
			color: #666;
			font-size: 17px;
		}


		/*  ======== STATS CARDS  ========== */

		.stats {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 18px;
			margin-bottom: 30px;
		}

		.stat-card {
			background: white;
			border-radius: 22px;
			padding: 22px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 8px 25px rgba(0,0,0,0.04);
		}

		.stat-card h3 {
			font-size: 16px;
			color: #666;
			margin-bottom: 8px;
		}

		.stat-card strong {
			font-size: 34px;
			color: #4f63e8;
		}


		/*  ======== NOTIFICATIONS LIST  ========== */

		.notification-list {
			display: flex;
			flex-direction: column;
			gap: 18px;
		}

		.notification {
			position: relative;
			background: white;
			border-radius: 24px;
			padding: 22px 22px 22px 42px;
			border: 1px solid #e5e7eb;
			display: grid;
			grid-template-columns: 55px 1fr auto;
			gap: 18px;
			align-items: center;
			box-shadow: 0 8px 25px rgba(0,0,0,0.04);
			cursor: pointer;
			transition: 0.2s;
		}

		.notification:hover {
			transform: translateY(-2px);
		}

		.notification.unread::before {
			content: "";
			position: absolute;
			left: 18px;
			top: 50%;
			transform: translateY(-50%);
			width: 10px;
			height: 10px;
			background: #22c55e;
			border-radius: 50%;
		}

		.notification.read {
			opacity: 0.6;
			background: #f1f1f1;
			border-color: #dddddd;
		}

		.notification.read .icon {
			background: #e5e5e5;
		}

		.notification.read h2,
		.notification.read p,
		.notification.read .date {
			color: #888;
		}

		.icon {
			width: 52px;
			height: 52px;
			border-radius: 16px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 24px;
		}

		/* Icon color types */
		.success .icon { background: #dcfce7; }
		.warning .icon { background: #fef3c7; }
		.info .icon { background: #dbeafe; }
		.member .icon { background: #ede9fe; }

		.notification h2 {
			font-size: 21px;
			margin-bottom: 6px;
		}

		.notification p {
			color: #555;
			line-height: 1.5;
		}

		.date {
			color: #777;
			font-size: 14px;
			font-weight: bold;
		}


		/*  ======== ACTION BUTTONS  ========== */

		.actions {
			margin-top: 28px;
			display: flex;
			gap: 14px;
		}

		.btn {
			border: none;
			padding: 13px 18px;
			border-radius: 14px;
			font-weight: bold;
			cursor: pointer;
		}

		.primary {
			background: #4f63e8;
			color: white;
		}

		.secondary {
			background: white;
			color: #4f63e8;
			border: 1px solid #dbe0ff;
		}
		
		.actions{
			display:flex;
			gap:20px;
			margin-top:30px;
			align-items:center;
		}

		.actions form{
			margin:0;
		}

		.actions button{
			border:none;
			border-radius:18px;
			padding:16px 28px;
			font-size:15px;
			font-weight:600;
			cursor:pointer;
			transition:0.2s ease;
		}

		.actions form:first-child button{
			background:#6366f1;
			color:white;
			box-shadow:0 6px 18px rgba(99,102,241,0.25);
		}

		.actions form:first-child button:hover{
			transform:translateY(-2px);
			background:#5558e8;
		}

		.actions form:last-child button{
			background:white;
			color:#6366f1;
			border:2px solid #d7d8ff;
		}

		.actions form:last-child button:hover{
			background:#f5f5ff;
		}


		/*  ======== EMPTY STATE  ========== */

		.empty {
			background: white;
			padding: 25px;
			border-radius: 22px;
			color: #666;
			text-align: center;
		}


</style>
</head>

<body>

<div class="container">

    <?php include "sidebar.php"; ?>

    <main class="main">

        <section class="page-header">
            <h1>Notifications</h1>
            <p>Retrouvez vos inscriptions, rappels, réservations et informations importantes.</p>
        </section>

        <section class="stats">
            <div class="stat-card">
                <h3>Total</h3>
                <strong id="totalCount"><?php echo count($notifications); ?></strong>
            </div>

            <div class="stat-card">
                <h3>Non lues</h3>
                <strong id="unreadCount">
                    <?php echo count(array_filter($notifications, fn($n) => !$n["is_read"])); ?>
                </strong>
            </div>
        </section>

        <section class="notification-list" id="notificationList">

			<?php if (isset($profileNotification)) : ?>

				<article class="notification warning unread" onclick="window.location.href='profil.php'">
					<div class="icon">👤</div>

					<div>
						<h2>Finalisez votre profil</h2>
						<p>Complétez vos informations personnelles pour profiter pleinement d’OffCampus.</p>

						<a href="profil.php" class="profile-link">
							Compléter mon profil
						</a>
					</div>

					<div class="date">Important</div>
				</article>

			<?php endif; ?>

			<?php if (empty($notifications)) : ?>

				<div class="empty">
					Aucune notification pour le moment.
				</div>

			<?php else : ?>

				<?php foreach ($notifications as $notif) : ?>

					<article class="notification <?php echo htmlspecialchars($notif["type"]); ?> <?php echo $notif["is_read"] ? "read" : "unread"; ?>"
							 data-id="<?php echo $notif["id"]; ?>"
							 onclick="markAsRead(this)">

						<div class="icon">🔔</div>

						<div>
							<h2><?php echo htmlspecialchars($notif["title"]); ?></h2>
							<p><?php echo htmlspecialchars($notif["message"]); ?></p>
						</div>

						<div class="date">
							<?php echo htmlspecialchars($notif["created_at"]); ?>
						</div>

					</article>

				<?php endforeach; ?>

			<?php endif; ?>

		</section>

        <div class="actions">

			<form method="POST">
				<button type="submit" name="mark_all_read">
					Tout marquer comme lu
				</button>
			</form>

			<form method="POST">
				<button type="submit" name="delete_read">
					Supprimer les notifications lues
				</button>
			</form>

		</div>

    </main>

</div>

<script>
function updateCounters() {
    const total = document.querySelectorAll(".notification").length;
    const unread = document.querySelectorAll(".notification.unread").length;

    document.getElementById("totalCount").textContent = total;
    document.getElementById("unreadCount").textContent = unread;
}

function markAllRead() {
    document.querySelectorAll(".notification").forEach(notification => {
        markAsRead(notification);
    });
}

function markAsRead(notification) {

    if (notification.classList.contains("read")) {
        return;
    }

    const id = notification.dataset.id;

    notification.classList.remove("unread");
    notification.classList.add("read");

    updateCounters();

    fetch("mark-notification-read.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + encodeURIComponent(id)
    });
}

function deleteRead() {
    document.querySelectorAll(".notification.read").forEach(notification => {
        notification.remove();
    });

    updateCounters();
}
</script>

</body>
</html>