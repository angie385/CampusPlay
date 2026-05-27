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

		<title>Réservations</title>

		<style>

		/*  ======== RESET GLOBAL & BASE  ========== */

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: Arial, Helvetica, sans-serif;
		}

		body {
			background: #f5f5f7;
			display: flex;
			height: 100vh;
			overflow: hidden;
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
			padding: 25px;
			overflow-y: auto;
		}

		.topbar {
			display: flex;
			gap: 15px;
			margin-bottom: 25px;
		}

		/* ======== BARRE DE RECHERCHE & FILTRES ========== */

		.top-bar {
			display: flex;
			gap: 20px;
			margin-bottom: 30px;
		}

		.search-bar {
			flex: 4;
		}

		.search-bar input {
			width: 100%;
			height: 58px;
			border: 1px solid #e1e4ef;
			border-radius: 18px;
			padding: 0 22px;
			font-size: 16px;
			background: white;
			outline: none;
		}

		.filter-btn {
			height: 58px;
			padding: 0 28px;
			border: 1px solid #e1e4ef;
			border-radius: 18px;
			background: white;
			color: #15162b;
			font-weight: bold;
			font-size: 16px;
			cursor: pointer;
		}

		/*  ======== CONTENT LAYOUT  ========== */

		.content {
			display: flex;
			gap: 30px;
		}

		.left {
			flex: 1;
		}


		/*  ======== TITLE & FILTERS  ========== */

		.title {
			font-size: 56px;
			margin-bottom: 20px;
		}

		.filters{
			display:flex;
			gap:16px;
			margin:30px 0;
			flex-wrap:wrap;
		}

		.filter-btn{
			border:none;
			background:#ffffff;
			color:#1f1f39;
			padding:16px 28px;
			border-radius:22px;
			font-size:20px;
			font-weight:600;
			cursor:pointer;
			transition:0.25s ease;
			box-shadow:0 2px 8px rgba(0,0,0,0.04);
		}

		.filter-btn:hover{
			transform:translateY(-2px);
			background:#f5f5ff;
		}

		.filter-btn.active{
			background:#e8e9ff;
			color:#6366f1;
		}

		/* Filter colors */
		.all {
			background: #ffe5bf;
			border: 1px solid #ff9f1c;
		}

		.salles {
			background: #ffd6d6;
			border: 1px solid #ff7c7c;
		}

		.equipements {
			background: #dcffd8;
			border: 1px solid #57cc4d;
		}

		.outils {
			background: #eed9ff;
			border: 1px solid #9d58d8;
		}

		.vehicules {
			background: #dbe8ff;
			border: 1px solid #4b79ff;
		}
		
		.filters .category {
			border: none;
			padding: 10px 16px;
			border-radius: 20px;
			font-weight: 600;
			font-size: 14px;
			cursor: pointer;
			transition: .2s;
		}

		.filters .category:hover {
			transform: translateY(-2px);
			opacity: .9;
		}

		.filters .category.active {
			outline: 3px solid rgba(79, 99, 232, .25);
		}

		.filters .green { background: #e9f9ef; color: #1f8f4d; }
		.filters .blue { background: #4f63e8; color: white; }
		.filters .yellow { background: #fff7d6; color: #b7791f; }
		.filters .pink { background: #ffe8f1; color: #c02660; }
		.filters .purple { background: #f1e8ff; color: #7c3aed; }

		/*  ======== CARDS GRID  ========== */

		.cards-grid {
			display: grid;
			grid-template-columns: repeat(5, 1fr);
			gap: 18px;
		}

		.card {
			background: white;
			border-radius: 12px;
			overflow: hidden;
			border: 2px solid transparent;
			transition: 0.25s;
			cursor: pointer;
			box-shadow: 0 2px 6px rgba(0,0,0,0.08);
		}

		.card:hover {
			transform: translateY(-3px);
		}

		.card.active {
			border: 4px solid #b10f79;
		}

		.card img {
			width: 100%;
			height: 170px;
			object-fit: cover;
		}

		.card-content {
			padding: 12px;
		}

		.badge {
			display: inline-block;
			padding: 7px 12px;
			border-radius: 14px;
			font-size: 12px;
			font-weight: 600;
			text-align: center;
			margin-bottom: 12px;
			border: none;
		}

		.badge.salles {
			background: #e9f9ef;
			color: #1f8f4d;
		}

		.badge.equipements {
			background: #fff7d6;
			color: #b7791f;
		}

		.badge.outils {
			background: #ffe8f1;
			color: #c02660;
		}

		.badge.vehicules {
			background: #f1e8ff;
			color: #7c3aed;
		}

		.card-content h3 {
			font-size: 18px;
			line-height: 1.3;
		}


		/*  ======== DETAILS PANEL  ========== */

		.details-panel {
			width: 340px;
			background: white;
			border-radius: 14px;
			overflow: hidden;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			height: fit-content;
			position: sticky;
			top: 220px;

			transform: translateX(80px);
			opacity: 0;

			transition: transform 0.45s ease, opacity 0.45s ease;
		}

		.details-panel.show {
			transform: translateX(0);
			opacity: 1;
		}

		.details-panel img {
			width: 100%;
			height: 240px;
			object-fit: cover;
		}

		.details-content {
			padding: 20px;
		}

		.details-content h2 {
			font-size: 36px;
			margin-bottom: 20px;
		}

		.details-content p {
			line-height: 1.6;
			color: #444;
		}

		.reserve-btn {
			width: 100%;
			margin-top: 25px;
			padding: 15px;
			border: none;
			border-radius: 12px;
			background: #6a5cff;
			color: white;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			transition: 0.2s;
		}

		.reserve-btn:hover {
			background: #5848f5;
			transform: translateY(-2px);
		}


		/*  ======== MODAL (OVERLAY + CONTENT)  ========== */

		.modal-overlay {
			position: fixed;
			inset: 0;
			background: rgba(0,0,0,0.45);
			backdrop-filter: blur(6px);
			display: flex;
			justify-content: center;
			align-items: center;
			opacity: 0;
			visibility: hidden;
			transition: 0.3s;
			z-index: 999;
		}

		.modal-overlay.show {
			opacity: 1;
			visibility: visible;
		}

		.modal {
			width: 500px;
			background: white;
			border-radius: 24px;
			padding: 35px;
			position: relative;
			transform: translateY(30px);
			transition: 0.3s;
			box-shadow: 0 20px 50px rgba(0,0,0,0.2);
		}

		.modal-overlay.show .modal {
			transform: translateY(0);
		}

		.close-modal {
			position: absolute;
			top: 18px;
			right: 18px;
			width: 38px;
			height: 38px;
			border: none;
			border-radius: 50%;
			background: #f3f4f6;
			cursor: pointer;
			font-size: 18px;
			transition: 0.2s;
		}

		.close-modal:hover {
			background: #e5e7eb;
			transform: rotate(90deg);
		}

		.modal h2 {
			font-size: 34px;
			margin-bottom: 8px;
		}

		.modal-subtitle {
			color: #6b7280;
			margin-bottom: 30px;
		}


		/*  ======== RESERVATION FORM  ========== */

		.reservation-form {
			display: flex;
			flex-direction: column;
			gap: 20px;
		}

		.form-group {
			display: flex;
			flex-direction: column;
			gap: 8px;
		}

		.form-group label {
			font-weight: 600;
			color: #374151;
		}

		.form-group input,
		.form-group textarea {
			padding: 14px 16px;
			border: none;
			border-radius: 14px;
			background: #f3f4f6;
			font-size: 15px;
			outline: none;
			transition: 0.2s;
		}

		.form-group input:focus,
		.form-group textarea:focus {
			background: white;
			box-shadow: 0 0 0 3px rgba(106,92,255,0.2);
		}

		.confirm-btn {
			margin-top: 10px;
			border: none;
			background: #6a5cff;
			color: white;
			padding: 16px;
			border-radius: 16px;
			font-size: 16px;
			font-weight: 700;
			cursor: pointer;
			transition: 0.2s;
		}

		.confirm-btn:hover {
			background: #5848f5;
			transform: translateY(-2px);
		}


		/*  ======== RESPONSIVE  ========== */

		@media(max-width:1400px) {
			.cards-grid {
				grid-template-columns: repeat(4, 1fr);
			}
		}

		@media(max-width:900px) {
			.content {
				flex-direction: column;
			}

			.details-panel {
				width: 100%;
			}

			.cards-grid {
				grid-template-columns: repeat(2, 1fr);
			}
		}

		@media(max-width:650px) {
			.sidebar {
				display: none;
			}

			.cards-grid {
				grid-template-columns: 1fr;
			}

			.title {
				font-size: 34px;
			}

			.filters {
				flex-direction: column;
				align-items: flex-start;
			}
		}

		</style>
	</head>

	<body>

		<?php include "sidebar.php"; ?>


		<main class="main">
			    
		<div class="top-bar">
			<div class="search-bar">
				<input id="searchInput" type="text" placeholder="🔍 Rechercher un événement, une activité, un lieu...">
			</div>
			<select class="filter-btn" id="sortSelect" onchange="sortEvents()">
				<option value="">Trier</option>
				<option value="theme">Par thème</option>
				<option value="az">A → Z</option>
				<option value="za">Z → A</option>
			</select>

		</div>

		  <div class="content">

			<section class="left">

			  <h1 class="title">Réservations</h1>

			<div class="filters">
				<button class="category blue active" data-filter="all">⌘ Toutes</button>
				<button class="category green" data-filter="salles">🏠 Salles</button>
				<button class="category yellow" data-filter="equipements">🏆 Gros équipements</button>
				<button class="category pink" data-filter="outils">🛠️ Outils / Matériel</button>
				<button class="category purple" data-filter="vehicules">🚐 Véhicules</button>
			</div>

			  <div class="cards-grid">

				<div class="card" data-category="salles" data-title="Salle polyvalente sport">
				  <img src="images/salle1.jpg">
				  <div class="card-content">
					<span class="badge salles">Salles</span>
					<h3>Salle polyvalente sport</h3>
				  </div>
				</div>

				<div class="card" data-category="outils" data-title="Micros">
				  <img src="images/micro.jpg">
				  <div class="card-content">
					<span class="badge outils">Outils / Matériel</span>
					<h3>Micros</h3>
				  </div>
				</div>

				<div class="card" data-category="salles" data-title="Studio de musique">
				  <img src="images/studio.jpg">
				  <div class="card-content">
					<span class="badge salles">Salles</span>
					<h3>Studio de musique</h3>
				  </div>
				</div>

				<div class="card" data-category="equipements" data-title="Baby-foot">
				  <img src="images/babyfoot.jpg">
				  <div class="card-content">
					<span class="badge equipements">Gros équipements</span>
					<h3>Baby-foot</h3>
				  </div>
				</div>

				<div class="card" data-category="vehicules" data-title="Remorque">
				  <img src="images/remorque.jpg">
				  <div class="card-content">
					<span class="badge vehicules">Véhicules</span>
					<h3>Remorque</h3>
				  </div>
				</div>

				<div class="card" data-category="vehicules" data-title="Minibus 9 places">
				  <img src="images/minibus.jpg">
				  <div class="card-content">
					<span class="badge vehicules">Véhicules</span>
					<h3>Minibus 9 places</h3>
				  </div>
				</div>

				<div class="card" data-category="equipements" data-title="Set-up Gaming">
				  <img src="images/gaming.jpg">
				  <div class="card-content">
					<span class="badge equipements">Gros équipements</span>
					<h3>Set-up Gaming</h3>
				  </div>
				</div>

				<div class="card" data-category="salles" data-title="Salle de danse">
				  <img src="images/danse.jpg">
				  <div class="card-content">
					<span class="badge salles">Salles</span>
					<h3>Salle de danse</h3>
				  </div>
				</div>

				<div class="card" data-category="salles" data-title="Studio Photo">
				  <img src="images/photo.jpg">
				  <div class="card-content">
					<span class="badge salles">Salles</span>
					<h3>Studio Photo</h3>
				  </div>
				</div>

				<div class="card" data-category="equipements" data-title="Billard">
				  <img src="images/billard.jpg">
				  <div class="card-content">
					<span class="badge equipements">Gros équipements</span>
					<h3>Billard</h3>
				  </div>
				</div>

				<div class="card" data-category="outils" data-title="Crêpière XXL">
				  <img src="images/crepiere.jpg">
				  <div class="card-content">
					<span class="badge outils">Outils / Matériel</span>
					<h3>Crêpière XXL</h3>
				  </div>
				</div>

				<div class="card" data-category="outils" data-title="Pack Vidéo">
				  <img src="images/video.jpg">
				  <div class="card-content">
					<span class="badge outils">Outils / Matériel</span>
					<h3>Pack Vidéo</h3>
				  </div>
				</div>

				<div class="card" data-category="vehicules" data-title="Flotte Vélos Electrique">
				  <img src="images/velo.jpg">
				  <div class="card-content">
					<span class="badge vehicules">Véhicules</span>
					<h3>Flotte Vélos Electrique</h3>
				  </div>
				</div>

				<div class="card" data-category="salles" data-title="Salle d'escalade">
				  <img src="images/escalade.jpg">
				  <div class="card-content">
					<span class="badge salles">Salles</span>
					<h3>Salle d'escalade</h3>
				  </div>
				</div>
				
				<div class="card" data-category="equipements" data-title="Table de ping-pong">
				  <img src="images/pingpong.jpg">
				  <div class="card-content">
					<span class="badge equipements">
					  Gros équipements
					</span>
					<h3>Table de ping-pong</h3>
				  </div>
				</div>

			  </div>

			</section>

			<aside class="details-panel">

			  <img 
				id="detailsImage"
				src="images/salle1.jpg"
			  >

			  <div class="details-content">

				<h2 id="detailsTitle">
				  Salle polyvalente sport
				</h2>

				<p id="detailsDescription">
				  Grande salle idéale pour les événements étudiants,
				  les entraînements sportifs ou les soirées associatives.
				  Disponible toute la semaine avec matériel inclus.
				</p>

				<button class="reserve-btn">
				  Réserver
				</button>

			  </div>

			</aside>

		  </div>

		</main>
		
		<div class="modal-overlay" id="reservationModal">

		  <div class="modal">

			<button class="close-modal" id="closeModal">
			  ✕
			</button>

			<h2>
			  Réserver
			</h2>

			<p class="modal-subtitle" id="modalResource">
			  Salle polyvalente sport
			</p>

			<div id="dynamicForm"></div>
		  </div>
		</div>

		<script>

		const detailsData = {

		  "Salle polyvalente sport":{
			image:"images/salle1.jpg",
			description:"Grande salle parfaite pour les événements étudiants, tournois sportifs et soirées associatives."
		  },

		  "Micros":{
			image:"images/micro.jpg",
			description:"Pack de micros sans fil pour conférences, karaokés ou événements."
		  },

		  "Studio de musique":{
			image:"images/studio.jpg",
			description:"Studio équipé pour répétitions, enregistrements et sessions musicales."
		  },

		  "Baby-foot":{
			image:"images/babyfoot.jpg",
			description:"Baby-foot professionnel pour animer vos soirées et événements."
		  },

		  "Remorque":{
			image:"images/remorque.jpg",
			description:"Remorque utilitaire idéale pour transporter du matériel."
		  },

		  "Minibus 9 places":{
			image:"images/minibus.jpg",
			description:"Parfait pour déplacements associatifs et sorties étudiantes."
		  },

		  "Set-up Gaming":{
			image:"images/gaming.jpg",
			description:"PC gaming complet avec écrans et périphériques."
		  },

		  "Salle de danse":{
			image:"images/danse.jpg",
			description:"Salle avec miroirs et sono pour répétitions et cours."
		  },

		  "Studio Photo":{
			image:"images/photo.jpg",
			description:"Studio photo avec éclairages et fond professionnel."
		  },

		  "Billard":{
			image:"images/billard.jpg",
			description:"Table de billard pour animations et détente."
		  },

		  "Crêpière XXL":{
			image:"images/crepiere.jpg",
			description:"Crêpière grand format idéale pour événements étudiants."
		  },

		  "Pack Vidéo":{
			image:"images/video.jpg",
			description:"Caméra, trépied et éclairages pour captations vidéo."
		  },

		  "Flotte Vélos Electrique":{
			image:"images/velo.jpg",
			description:"Vélos électriques disponibles pour déplacements campus."
		  },

		  "Salle d'escalade":{
			image:"images/escalade.jpg",
			description:"Mur d'escalade intérieur pour activités sportives."
		  },
		  
		  "Table de ping-pong":{
			image:"images/pingpong.jpg",
			description:"Table de ping-pong professionnelle idéale pour animations étudiantes, tournois et moments détente."
		  },

		};

		const cards = document.querySelectorAll(".card");
		const filterButtons = document.querySelectorAll(".filters .category");
		const searchInput = document.getElementById("searchInput");

		const detailsTitle = document.getElementById("detailsTitle");
		const detailsDescription = document.getElementById("detailsDescription");
		const detailsImage = document.getElementById("detailsImage");
		const detailsPanel = document.querySelector(".details-panel");

		filterButtons.forEach(button => {

		  button.addEventListener("click", () => {

			filterButtons.forEach(btn =>
			  btn.classList.remove("active")
			);

			button.classList.add("active");

			const filter = button.dataset.filter;

			cards.forEach(card => {

			  if(
				filter === "all" ||
				card.dataset.category === filter
			  ){
				card.style.display = "block";
			  }else{
				card.style.display = "none";
			  }

			});

		  });

		});

		searchInput.addEventListener("keyup", function(){

		  const value = this.value.toLowerCase();

		  cards.forEach(card => {

			const title =
			  card.dataset.title.toLowerCase();

			if(title.includes(value)){
			  card.style.display = "block";
			}else{
			  card.style.display = "none";
			}

		  });

		});

		cards.forEach(card => {

		  card.addEventListener("click", () => {

			cards.forEach(c =>
			  c.classList.remove("active")
			);

			card.classList.add("active");

			const title = card.dataset.title;
			currentCategory = card.dataset.category;

			detailsPanel.classList.remove("show");

			setTimeout(() => {

			  detailsTitle.textContent = title;

			  detailsDescription.innerHTML =
				detailsData[title].description;

			  detailsImage.src =
				detailsData[title].image;

			  detailsPanel.classList.add("show");

			}, 150);

		  });

		});

		function resetAll(){

		  searchInput.value = "";

		  cards.forEach(card => {
			card.style.display = "block";
		  });

		  filterButtons.forEach(btn =>
			btn.classList.remove("active")
		  );

		  filterButtons[0].classList.add("active");

		}

		cards[0].classList.add("active");

		window.addEventListener("load", () => {

		  setTimeout(() => {
			detailsPanel.classList.add("show");
		  }, 200);

		});
		
		const reservationModal =
		  document.getElementById("reservationModal");

		const reserveButton =
		  document.querySelector(".reserve-btn");

		const closeModal =
		  document.getElementById("closeModal");

		const modalResource =
		  document.getElementById("modalResource");
		  
		const dynamicForm =
		  document.getElementById("dynamicForm");

		let currentCategory = "salles";
	
		function generateForm(category){

		  if(category === "salles"){

			dynamicForm.innerHTML = `

			  <form class="reservation-form">

				<div class="form-group">
				  <label>Date</label>
				  <input type="date" required>
				</div>

				<div class="form-group">
				  <label>Heure de début</label>
				  <input type="time" required>
				</div>

				<div class="form-group">
				  <label>Heure de fin</label>
				  <input type="time" required>
				</div>

				<div class="form-group">
				  <label>Nom du responsable</label>
				  <input
					type="text"
					placeholder="Nom complet"
					required
				  >
				</div>

				<div class="form-group">
				  <label>Nombre de participants</label>
				  <input
					type="number"
					min="1"
					max="300"
					value="1"
				  >
				</div>

				<button type="submit" class="confirm-btn">
				  Confirmer la réservation
				</button>

			  </form>

			`;
		  }

		  else if(
			category === "outils" ||
			category === "equipements"
		  ){

			dynamicForm.innerHTML = `

			  <form class="reservation-form">

				<div class="form-group">
				  <label>Date de retrait</label>
				  <input type="date" required>
				</div>

				<div class="form-group">
				  <label>Date de retour</label>
				  <input type="date" required>
				</div>

				<div class="form-group">
				  <label>Nom emprunteur</label>
				  <input
					type="text"
					placeholder="Nom complet"
					required
				  >
				</div>

				<div class="form-group">
				  <label>Quantité</label>
				  <input
					type="number"
					min="1"
					value="1"
				  >
				</div>

				<div class="form-group">
				  <label>Utilisation prévue</label>

				  <textarea
					rows="4"
					placeholder="Ex : événement BDE, soirée étudiante..."
				  ></textarea>

				</div>

				<button type="submit" class="confirm-btn">
				  Confirmer la réservation
				</button>

			  </form>

			`;
		  }

		  else if(category === "vehicules"){

			dynamicForm.innerHTML = `

			  <form class="reservation-form">

				<div class="form-group">
				  <label>Date de départ</label>
				  <input type="date" required>
				</div>

				<div class="form-group">
				  <label>Date de retour</label>
				  <input type="date" required>
				</div>

				<div class="form-group">
				  <label>Heure de départ</label>
				  <input type="time" required>
				</div>

				<div class="form-group">
				  <label>Nom titulaire du permis</label>
				  <input
					type="text"
					placeholder="Nom complet"
					required
				  >
				</div>

				<div class="form-group">
				  <label>Numéro de permis</label>
				  <input
					type="text"
					placeholder="N° permis"
					required
				  >
				</div>

				<div class="form-group">
				  <label>Destination / trajet</label>

				  <textarea
					rows="3"
					placeholder="Ex : déplacement tournoi Lille"
				  ></textarea>

				</div>

				<button type="submit" class="confirm-btn">
				  Confirmer la réservation
				</button>

			  </form>

			`;
		  }

		  document
			.querySelector(".reservation-form")

			.addEventListener("submit", (e) => {

			  e.preventDefault();

			  alert("Réservation confirmée ✅");

			  reservationModal.classList.remove("show");

			});

		}

		reserveButton.addEventListener("click", () => {

		  modalResource.textContent =
			detailsTitle.textContent;

		  generateForm(currentCategory);

		  reservationModal.classList.add("show");

		});


		closeModal.addEventListener("click", () => {

		  reservationModal.classList.remove("show");

		});


		reservationModal.addEventListener("click", (e) => {

		  if(e.target === reservationModal){

			reservationModal.classList.remove("show");
		  }

		});


		</script>
		
	</body>
</html>