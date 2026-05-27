<?php
require_once "auth.php";
requireLogin();
require_once "db.php";

$userId = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $surname = trim($_POST["surname"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $gender = trim($_POST["gender"]);

    $stmt = $pdo->prepare("
        UPDATE users
        SET name = ?, surname = ?, email = ?, phone = ?, gender = ?
        WHERE id = ?
    ");

    $stmt->execute([$name, $surname, $email, $phone, $gender, $userId]);

    $_SESSION["name"] = $name;
    $_SESSION["surname"] = $surname;
    $_SESSION["email"] = $email;
    $_SESSION["phone"] = $phone;
    $_SESSION["gender"] = $gender;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$name = $user["name"];
$surname = $user["surname"];
$email = $user["email"];
$role = $user["role"];
$association = $user["association"];
$phone = $user["phone"] ?? "";
$gender = $user["gender"] ?? "";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>OffCampus - Profil</title>

<style>
/*  ======== RESET GLOBAL & BASE  ========== */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

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
    display: block;
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

.user-box .avatar,
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

.logout-link {
    display: none;
    margin-top: 12px;
    text-decoration: none;
    color: #ef4444;
    font-weight: bold;
    padding: 10px 12px;
    border-radius: 12px;
}

.logout-link.show {
    display: block;
}

.logout-link:hover {
    background: #fee2e2;
}


/*  ======== MAIN  ========== */

.main {
    flex: 1;
    padding: 40px;
}

.profile-header {
    background: white;
    border-radius: 28px;
    padding: 35px;
    display: flex;
    align-items: center;
    gap: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    border: 1px solid #e5e7eb;
    margin-bottom: 35px;
}

.avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f63e8, #16a34a);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.profile-info h1 {
    font-size: 36px;
    margin-bottom: 10px;
}

.role-badge {
    display: inline-block;
    background: #e8f0ff;
    color: #4f63e8;
    padding: 8px 16px;
    border-radius: 18px;
    font-weight: bold;
    margin-bottom: 12px;
}

.profile-info p {
    color: #666;
    font-size: 16px;
}


/*  ======== SECTIONS  ========== */

.section {
    background: white;
    border-radius: 26px;
    padding: 28px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 8px 25px rgba(0,0,0,0.04);
    margin-bottom: 35px;
}

.section h2 {
    font-size: 28px;
    margin-bottom: 20px;
}

.section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title h2 {
    margin-bottom: 0;
}

.edit-main-btn {
    border: none;
    background: #e8f0ff;
    color: #4f63e8;
    padding: 10px 16px;
    border-radius: 14px;
    font-weight: bold;
    cursor: pointer;
}


/*  ======== INFOS (CARDS + GRID)  ========== */

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.info-card {
    background: #f7f8fc;
    padding: 18px;
    border-radius: 18px;
}

.info-card strong {
    display: block;
    margin-bottom: 8px;
    color: #15162b;
}

.info-card span {
    color: #555;
}


/*  ======== RÉSERVATIONS  ========== */

.reservations {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.reservation-card {
    background: #f7f8fc;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.reservation-card img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.reservation-content {
    padding: 16px;
}

.reservation-content h3 {
    font-size: 18px;
    margin-bottom: 8px;
}

.reservation-content p {
    color: #666;
    font-size: 14px;
    margin-bottom: 6px;
}

.status {
    display: inline-block;
    margin-top: 10px;
    background: #dcfce7;
    color: #166534;
    padding: 6px 12px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: bold;
}


/*  ======== FORMULAIRE PROFIL  ========== */

.profile-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.info-line {
    display: grid;
    grid-template-columns: 110px 1fr 34px;
    align-items: center;
    gap: 8px;
    background: #f7f8fc;
    padding: 11px 12px;
    border-radius: 14px;
}

.info-line label {
    font-weight: bold;
    color: #4f63e8;
    font-size: 13px;
}

.info-line input {
    border: none;
    background: transparent;
    font-size: 14px;
    color: #333;
    outline: none;
    min-width: 0;
}

.info-line input:not([readonly]) {
    background: white;
    border: 1px solid #4f63e8;
    border-radius: 10px;
    padding: 7px;
}

.edit-pencil {
    display: none;
    border: none;
    background: #e8f0ff;
    color: #4f63e8;
    border-radius: 10px;
    padding: 7px;
    cursor: pointer;
}

.profile-form.editing .edit-pencil {
    display: block;
}

.save-btn {
    display: none;
    grid-column: 1 / -1;
    margin-top: 10px;
    padding: 13px;
    border: none;
    border-radius: 14px;
    background: #4f63e8;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

.profile-form.editing .save-btn {
    display: block;
}


/*  ======== SIDEBAR (EXTRA)  ========== */

.small-avatar {
    width: 42px;
    height: 42px;
    font-size: 18px;
    background: #4f63e8;
}

.user-box {
    display: flex;
    align-items: center;
    gap: 12px;
    border-top: 1px solid #e5e7eb;
    padding-top: 22px;
}

.logout-link {
    display: none;
    text-decoration: none;
    color: #ef4444;
    font-weight: bold;
    padding: 12px 15px;
    border-radius: 14px;
    margin-top: 10px;
}

.logout-link.show {
    display: block;
}

.logout-link:hover {
    background: #fee2e2;
}


/*  ======== RESPONSIVE  ========== */

@media(max-width: 1000px) {
    .reservations {
        grid-template-columns: repeat(2, 1fr);
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 750px) {
    .container {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        height: auto;
        position: static;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
    }

    .reservations {
        grid-template-columns: 1fr;
    }
}



</style>
</head>

<body>

	<div class="container">

		<?php include "sidebar.php"; ?>

		<main class="main">

			<section class="profile-header">
				<div class="avatar">
					<?php echo strtoupper(substr($surname, 0, 1)); ?>
				</div>

				<div class="profile-info">
					<h1><?php echo htmlspecialchars($surname . " " . $name); ?></h1>
					<div class="role-badge">
						<?php echo $role === "membre" ? "Membre association" : "Étudiant"; ?>
					</div>
					<p>Bienvenue dans votre espace personnel OffCampus.</p>
				</div>
			</section>

			<section class="section">
				<div class="section-title">
					<h2>Mes informations personnelles</h2>
					<button type="button" class="edit-main-btn" id="editBtn" onclick="enableProfileEdit()">
						Modifier
					</button>
				</div>

				<form method="POST" class="profile-form" id="profileForm">

					<div class="info-line">
						<label>NOM :</label>
						<input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly>
						<button type="button" class="edit-pencil" onclick="editField(this)">✏️</button>
					</div>

					<div class="info-line">
						<label>PRÉNOM :</label>
						<input type="text" name="surname" value="<?php echo htmlspecialchars($surname); ?>" readonly>
						<button type="button" class="edit-pencil" onclick="editField(this)">✏️</button>
					</div>

					<div class="info-line">
						<label>MAIL :</label>
						<input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
						<button type="button" class="edit-pencil" onclick="editField(this)">✏️</button>
					</div>

					<div class="info-line">
						<label>TÉLÉPHONE :</label>
						<input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" readonly>
						<button type="button" class="edit-pencil" onclick="editField(this)">✏️</button>
					</div>

					<div class="info-line">
						<label>SEXE :</label>
						<input type="text" name="gender" value="<?php echo htmlspecialchars($gender); ?>" readonly>
						<button type="button" class="edit-pencil" onclick="editField(this)">✏️</button>
					</div>

					<div class="info-line">
						<label>RÔLE :</label>
						<input type="text" value="<?php echo htmlspecialchars($role); ?>" readonly>
					</div>

					<div class="info-line">
						<label>ASSOCIATION :</label>
						<input type="text" value="<?php echo htmlspecialchars($association ?: 'Aucune'); ?>" readonly>
					</div>

					<button type="submit" class="save-btn" id="saveBtn">
						Enregistrer les modifications
					</button>
				</form>
			</section>
			
			<section class="section">
				<h2>Mes réservations</h2>

				<div class="reservations">

					<article class="reservation-card">
						<img src="images/Jeu de societe.jpg" alt="Soirée jeux">
						<div class="reservation-content">
							<h3>Soirée jeux</h3>
							<p>16 mai 2026</p>
							<p>Salle polyvalente</p>
							<span class="status">Confirmée</span>
						</div>
					</article>

					<article class="reservation-card">
						<img src="images/Mario.jpg" alt="Mario Kart">
						<div class="reservation-content">
							<h3>Mario Kart</h3>
							<p>24 mai 2026</p>
							<p>Espace détente</p>
							<span class="status">Liste d’attente</span>
						</div>
					</article>

					<article class="reservation-card">
						<img src="images/Peinture.jpg" alt="Peinture">
						<div class="reservation-content">
							<h3>Atelier peinture</h3>
							<p>28 mai 2026</p>
							<p>Salle créativité</p>
							<span class="status">Confirmée</span>
						</div>
					</article>

					<article class="reservation-card">
						<img src="images/Cinema.png" alt="Cinéma">
						<div class="reservation-content">
							<h3>Cinéma plein air</h3>
							<p>14 juin 2026</p>
							<p>Cour centrale</p>
							<span class="status">Confirmée</span>
						</div>
					</article>

				</div>
			</section>

		</main>

	</div>

	<script>
		function enableProfileEdit() {
			const form = document.getElementById("profileForm");
			form.classList.add("editing");

			document.getElementById("editBtn").style.display = "none";
		}

		function editField(button) {
			const input = button.parentElement.querySelector("input");

			if (input.hasAttribute("name")) {
				input.removeAttribute("readonly");
				input.focus();
			}
		}
		
		function toggleLogout() {
			document.getElementById("logoutLink").classList.toggle("show");
		}

	</script>

</body>
</html>