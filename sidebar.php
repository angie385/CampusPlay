<?php
$currentPage = basename($_SERVER["PHP_SELF"]);

$name = $_SESSION["name"] ?? "Utilisateur";
$surname = $_SESSION["surname"] ?? "";
$role = $_SESSION["role"] ?? "etudiant";

$initial = strtoupper(substr($surname ?: $name, 0, 1));
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<style>
			.user-box{
				position: relative;
				cursor: pointer;
			}

			.logout-popup{
				position:absolute;
				bottom:90px;
				left:20px;

				background:white;

				padding:14px 18px;

				border-radius:16px;

				box-shadow:0 10px 30px rgba(0,0,0,0.12);

				z-index:999;
			}

			.logout-popup a{
				text-decoration:none;
				color:#e53935;
				font-weight:600;
				font-size:15px;
			}
			
			</style>
</head>
		<aside class="sidebar">

			<div>

				<div class="logo">
					<a href="accueil.php">
						<img src="images/logo.jpeg" alt="Logo Off Campus">
					</a>
				</div>

				<nav class="menu">

					<a href="accueil.php"
					   class="<?= $currentPage === 'accueil.php' ? 'active' : '' ?>">
						Accueil
					</a>

					<a href="activite.php"
					   class="<?= $currentPage === 'activite.php' ? 'active' : '' ?>">
						Événements / Activités
					</a>

					<?php if ($role === "membre") : ?>

						<a href="dashboard-membre.php"
						   class="<?= $currentPage === 'dashboard-membre.php' ? 'active' : '' ?>">
							Tableau de bord
						</a>

						<a href="evenement-membre.php"
						   class="<?= $currentPage === 'evenement-membre.php' ? 'active' : '' ?>">
							Créer un événement
						</a>

					<?php else : ?>

						<a href="jeux.php"
						   class="<?= $currentPage === 'jeux.php' ? 'active' : '' ?>">
							Jeux
						</a>

					<?php endif; ?>

					<a href="reservations.php"
					   class="<?= $currentPage === 'reservations.php' ? 'active' : '' ?>">
						Réservations
					</a>

					<a href="notifications.php"
					   class="<?= $currentPage === 'notifications.php' ? 'active' : '' ?>">
						Notifications
					</a>

					<a href="a-propos.php"
					   class="<?= $currentPage === 'a-propos.php' ? 'active' : '' ?>">
						À propos
					</a>

					<a href="profil.php"
					   class="<?= $currentPage === 'profil.php' ? 'active' : '' ?>">
						Mon compte
					</a>

				</nav>

			</div>

			<!-- USER BOX -->
			<div class="user-box" id="userBox">

				<div class="avatar">
					<?= $initial ?>
				</div>

				<div class="user-info">
					<strong><?= htmlspecialchars($surname ?: $name) ?></strong>

					<p>
						<?= $role === "membre"
							? "Membre association"
							: "Étudiant" ?>
					</p>
				</div>

			</div>

		</aside>

		<script>

		const userBox = document.getElementById("userBox");

		userBox.addEventListener("click", () => {

			const currentPage =
				window.location.pathname.split("/").pop();

			// SI PAS SUR PROFIL
			if (currentPage !== "profil.php") {

				window.location.href = "profil.php";
				return;
			}

			// SI DÉJÀ OUVERT
			let existingPopup =
				document.querySelector(".logout-popup");

			if (existingPopup) {
				existingPopup.remove();
				return;
			}

			// CRÉATION POPUP
			const popup = document.createElement("div");

			popup.classList.add("logout-popup");

			popup.innerHTML = `
				<a href="deconnexion.php">
					Déconnexion
				</a>
			`;

			userBox.appendChild(popup);

		});

		</script>