<?php
require_once "auth.php";
requireLogin();

$role = getRole();
$isConnected = isConnected();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OffCampus - À propos</title>

    <style>
	
        /*  ======== RESET GLOBAL & BASE  ========== */

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: Arial, sans-serif;
		}

		body {
			background: #f4f6fb;
			color: #222;
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
			padding: 28px 34px;
		}


		/*  ======== HERO SECTION  ========== */

		.hero {
			background: linear-gradient(135deg, #2563eb, #16a34a);
			border-radius: 28px;
			padding: 42px;
			color: white;
			position: relative;
			overflow: hidden;
			margin-bottom: 28px;
			box-shadow: 0 8px 24px rgba(0,0,0,0.10);
		}

		.hero::before {
			content: "";
			position: absolute;
			width: 280px;
			height: 280px;
			border-radius: 50%;
			background: rgba(255,255,255,0.13);
			top: -90px;
			right: -80px;
		}

		.hero::after {
			content: "";
			position: absolute;
			width: 180px;
			height: 180px;
			border-radius: 50%;
			background: rgba(255,255,255,0.10);
			bottom: -60px;
			left: -50px;
		}

		.hero-content {
			position: relative;
			z-index: 2;
			max-width: 760px;
		}

		.hero img {
			width: 145px;
			background: white;
			border-radius: 22px;
			padding: 10px;
			margin-bottom: 22px;
			box-shadow: 0 6px 18px rgba(0,0,0,0.12);
		}

		.hero h1 {
			font-size: 42px;
			margin-bottom: 14px;
		}

		.hero h1 span {
			color: #facc15;
		}

		.hero p {
			font-size: 18px;
			line-height: 1.6;
			opacity: 0.96;
		}


		/*  ======== QUICK ACTION BUTTONS  ========== */

		.quick-actions {
			display: flex;
			gap: 12px;
			margin-top: 26px;
			flex-wrap: wrap;
		}

		.btn {
			border: none;
			border-radius: 14px;
			padding: 12px 18px;
			font-weight: bold;
			cursor: pointer;
			text-decoration: none;
			display: inline-block;
			transition: 0.2s;
		}

		.btn-white {
			background: white;
			color: #2563eb;
		}

		.btn-white:hover {
			transform: translateY(-1px);
			box-shadow: 0 6px 16px rgba(0,0,0,0.12);
		}

		.btn-orange {
			background: #f97316;
			color: white;
		}

		.btn-orange:hover {
			background: #ea580c;
			transform: translateY(-1px);
		}


		/*  ======== SECTION GRID (CARDS)  ========== */

		.section-grid {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 22px;
			margin-bottom: 28px;
		}

		.card {
			background: white;
			border-radius: 22px;
			padding: 24px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 5px 16px rgba(0,0,0,0.06);
		}

		.card-icon {
			width: 48px;
			height: 48px;
			border-radius: 16px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 23px;
			margin-bottom: 16px;
		}

		/* Soft color backgrounds */
		.blue-soft { background: #e8f0ff; }
		.green-soft { background: #ecfdf5; }
		.orange-soft { background: #fff7ed; }
		.purple-soft { background: #f3e8ff; }
		.red-soft { background: #fee2e2; }
		.yellow-soft { background: #fef9c3; }

		.card h2 {
			font-size: 22px;
			color: #111827;
			margin-bottom: 10px;
		}

		.card p {
			color: #666;
			line-height: 1.55;
			font-size: 15px;
		}


		/*  ======== LARGE SECTION (PANELS + ROLES)  ========== */

		.large-section {
			display: grid;
			grid-template-columns: 1.3fr 1fr;
			gap: 24px;
			margin-bottom: 28px;
		}

		.panel {
			background: white;
			border-radius: 24px;
			padding: 26px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 5px 16px rgba(0,0,0,0.06);
		}

		.panel h2 {
			font-size: 26px;
			color: #111827;
			margin-bottom: 18px;
		}

		.panel p {
			color: #666;
			line-height: 1.65;
			margin-bottom: 14px;
		}

		.steps {
			display: flex;
			flex-direction: column;
			gap: 16px;
		}

		.step {
			display: flex;
			gap: 14px;
			align-items: flex-start;
			padding: 16px;
			background: #f9fafb;
			border: 1px solid #edf0f5;
			border-radius: 18px;
		}

		.step-number {
			width: 34px;
			height: 34px;
			border-radius: 12px;
			background: #2563eb;
			color: white;
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: bold;
			flex-shrink: 0;
		}

		.step h3 {
			color: #111827;
			margin-bottom: 5px;
			font-size: 17px;
		}

		.step p {
			margin-bottom: 0;
			font-size: 14px;
		}

		.role-list {
			display: flex;
			flex-direction: column;
			gap: 14px;
		}

		.role {
			padding: 16px;
			border-radius: 18px;
			background: #f9fafb;
			border: 1px solid #edf0f5;
		}

		.role h3 {
			font-size: 18px;
			margin-bottom: 7px;
			color: #111827;
		}

		.role p {
			font-size: 14px;
			margin-bottom: 0;
		}


		/*  ======== FINAL BANNER  ========== */

		.final-banner {
			background: white;
			border-radius: 24px;
			padding: 28px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 5px 16px rgba(0,0,0,0.06);
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 20px;
		}

		.final-banner h2 {
			color: #111827;
			margin-bottom: 8px;
		}

		.final-banner p {
			color: #666;
			line-height: 1.5;
		}

		.rainbow-line {
			height: 6px;
			border-radius: 20px;
			background: linear-gradient(90deg, #ef4444, #f97316, #facc15, #16a34a, #0ea5e9, #6d28d9);
			margin-top: 18px;
		}


		/*  ======== RESPONSIVE  ========== */

		@media screen and (max-width: 1050px) {
			.section-grid {
				grid-template-columns: repeat(2, 1fr);
			}

			.large-section {
				grid-template-columns: 1fr;
			}
		}

		@media screen and (max-width: 850px) {
			.container {
				flex-direction: column;
			}

			.sidebar {
				width: 100%;
			}

			.menu {
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
			}

			.menu a {
				margin-bottom: 0;
			}

			.final-banner {
				flex-direction: column;
				align-items: flex-start;
			}
		}

    </style>
</head>

<body>

    <div class="container">

        <!-- MENU GAUCHE -->
		
        <?php include "sidebar.php"; ?>

        <!-- CONTENU PRINCIPAL -->
        <main class="main">

            <section class="hero">
                <div class="hero-content">
                    <img src="images/logo.jpeg" alt="Logo OffCampus">

                    <h1>À propos de <span>OffCampus</span></h1>

                    <p>
                        OffCampus est une plateforme pensée pour simplifier la vie associative du campus.
                        Elle permet aux étudiants de découvrir les activités proposées, de s’inscrire aux événements
                        et aux membres associatifs de gérer leur organisation plus facilement.
                    </p>

                    <div class="quick-actions">
                        <a href="activite.php" class="btn btn-white">Voir les activités</a>
                        <a href="connexion.php" class="btn btn-orange">Se connecter</a>
                    </div>
                </div>
            </section>

            <section class="section-grid">

                <div class="card">
                    <div class="card-icon blue-soft">📅</div>
                    <h2>Événements</h2>
                    <p>
                        Les associations peuvent créer, modifier et suivre leurs événements :
                        soirées, tournois, ateliers, activités culturelles ou sportives.
                    </p>
                </div>

                <div class="card">
                    <div class="card-icon green-soft">✅</div>
                    <h2>Inscriptions</h2>
                    <p>
                        Les étudiants peuvent consulter les activités disponibles et participer
                        aux événements organisés sur le campus.
                    </p>
                </div>

                <div class="card">
                    <div class="card-icon orange-soft">🎲</div>
                    <h2>Ressources</h2>
                    <p>
                        La plateforme peut aussi servir à suivre les salles, jeux, matériels
                        et ressources nécessaires aux activités étudiantes.
                    </p>
                </div>

                <div class="card">
                    <div class="card-icon purple-soft">👥</div>
                    <h2>Communauté</h2>
                    <p>
                        OffCampus centralise les informations pour faciliter les échanges
                        entre étudiants, membres associatifs et responsables.
                    </p>
                </div>

                <div class="card">
                    <div class="card-icon red-soft">🔔</div>
                    <h2>Notifications</h2>
                    <p>
                        Les utilisateurs peuvent être informés des nouveautés, validations,
                        rappels et modifications liées aux activités.
                    </p>
                </div>

                <div class="card">
                    <div class="card-icon yellow-soft">📊</div>
                    <h2>Suivi associatif</h2>
                    <p>
                        Les membres disposent d’un tableau de bord pour visualiser les événements,
                        les demandes et l’activité de leur association.
                    </p>
                </div>

            </section>

            <section class="large-section">

                <div class="panel">
                    <h2>Notre objectif</h2>

                    <p>
                        Le but d’OffCampus est de rendre la vie associative plus claire, plus accessible
                        et mieux organisée. Au lieu de disperser les informations entre plusieurs groupes,
                        messages ou affiches, la plateforme rassemble les activités du campus dans un espace unique.
                    </p>

                    <p>
                        Les étudiants peuvent ainsi découvrir rapidement ce qui se passe sur le campus,
                        tandis que les membres associatifs disposent d’outils simples pour publier,
                        gérer et suivre leurs événements.
                    </p>

                    <div class="rainbow-line"></div>
                </div>

                <div class="panel">
                    <h2>Fonctionnement</h2>

                    <div class="steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <div>
                                <h3>Découvrir</h3>
                                <p>Les étudiants consultent les événements et activités disponibles.</p>
                            </div>
                        </div>

                        <div class="step">
                            <div class="step-number">2</div>
                            <div>
                                <h3>Participer</h3>
                                <p>Ils peuvent s’inscrire ou suivre les informations importantes.</p>
                            </div>
                        </div>

                        <div class="step">
                            <div class="step-number">3</div>
                            <div>
                                <h3>Gérer</h3>
                                <p>Les membres associatifs créent, modifient et supervisent les événements.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <section class="large-section">

                <div class="panel">
                    <h2>Rôles utilisateurs</h2>

                    <div class="role-list">
                        <div class="role">
                            <h3>Étudiant</h3>
                            <p>Il consulte les activités, découvre les événements et peut participer à la vie du campus.</p>
                        </div>

                        <div class="role">
                            <h3>Membre associatif</h3>
                            <p>Il gère les événements, suit les demandes et utilise le tableau de bord associatif.</p>
                        </div>

                        <div class="role">
                            <h3>Administrateur</h3>
                            <p>Il supervise la plateforme, les contenus, les validations et la cohérence globale du système.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <h2>Une plateforme évolutive</h2>

                    <p>
                        OffCampus a été conçu comme une base évolutive. Les fonctionnalités peuvent être enrichies
                        progressivement avec la gestion des réservations, les notifications, les inscriptions
                        détaillées et les statistiques avancées.
                    </p>

                    <p>
                        L’objectif est de garder une interface simple, moderne et adaptée aux besoins réels
                        des associations étudiantes.
                    </p>
                </div>

            </section>

            <section class="final-banner">
                <div>
                    <h2>Les associations, l’université autrement.</h2>
                    <p>
                        OffCampus aide à rendre la vie étudiante plus visible, plus organisée et plus dynamique.
                    </p>
                </div>

                <a href="activite.php" class="btn btn-green">Explorer les activités</a>
            </section>

        </main>

    </div>

</body>
</html>