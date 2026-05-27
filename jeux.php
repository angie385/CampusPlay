<?php
require_once "auth.php";
require_once "db.php";
require_once "notification_helper.php";

$isConnected = isConnected();
$role = getRole();

$name = $_SESSION["name"] ?? "Utilisateur";
$surname = $_SESSION["surname"] ?? "";
$initial = strtoupper(substr($surname ?: $name, 0, 1));

$message = "";

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["reserve_game"])
) {

    if (!$isConnected) {

        header("Location: connexion.php");
        exit;
    }

    $gameId = intval($_POST["game_id"]);
    $userId = $_SESSION["user_id"];

    $stmt = $pdo->prepare("
        SELECT * FROM games
        WHERE id = ?
    ");

    $stmt->execute([$gameId]);

    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($game && $game["available"] > 0) {

        $returnDate = date(
            "Y-m-d",
            strtotime("+3 days")
        );

        $stmt = $pdo->prepare("
			INSERT INTO game_reservations
			(user_id, game_id, return_date)
			VALUES (?, ?, ?)
		");

        $stmt->execute([
            $userId,
            $gameId,
            $returnDate
        ]);
		
		addNotification(
			$pdo,
			$userId,
			"Jeu emprunté",
			"Votre demande a bien été prise en compte. Le jeu " . $game["name"] . " est à rendre sous 3 jours sous peine de pénalité.",
			"warning",
			"jeux.php"
		);

        $stmt = $pdo->prepare("
            UPDATE games
            SET available = available - 1
            WHERE id = ?
        ");

        $stmt->execute([$gameId]);

        $message =
            "Votre demande a bien été prise en compte. 
            Le jeu « " . htmlspecialchars($game["name"]) . " »
            est à rendre sous 3 jours sous peine de pénalité.";

    } else {

        $message =
            "Ce jeu n’est plus disponible.";
    }
}

$stmt = $pdo->query("
    SELECT *
    FROM games
    ORDER BY name ASC
");

$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Jeux à emprunter</title>

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

		.games-section {
			flex: 1;
		}


		/*  ======== TITLE & SORTING  ========== */

		h1 {
			font-size: 32px;
			margin-bottom: 25px;
		}

		p {
			font-style: italic;
			font-size: 22px;
			color: #333;
			margin-bottom: 25px
		}

		#sortSelect {
			padding: 12px 18px;
			border: none;
			border-radius: 12px;
			background: white;
			font-size: 14px;
			font-weight: 600;
			cursor: pointer;
			box-shadow: 0 2px 6px rgba(0,0,0,0.08);
			outline: none;
		}


		/*  ======== GAMES GRID  ========== */

		.games-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 20px;
		}

		.game-card {
			background: white;
			border-radius: 12px;
			overflow: hidden;
			border: 2px solid transparent;
			transition: 0.2s;
			cursor: pointer;
			box-shadow: 0 2px 6px rgba(0,0,0,0.08);
		}

		.game-card:hover {
			transform: translateY(-2px);
		}

		.game-card.active {
			border: 4px solid #ecce1f;
			transform: translateY(-2px);
		}

		.game-card img {
			width: 100%;
			height: 330px;
			object-fit: cover;
		}

		.game-info {
			padding: 14px;
		}

		.game-info h3 {
			margin-bottom: 10px;
			font-size: 24px;
		}

		.game-info p {
			margin-bottom: 8px;
			color: #444;
			font-size: 15px;
		}


		/*  ======== DETAILS PANEL  ========== */

		.details-panel {
			width: 320px;
			background: white;
			border-radius: 14px;
			overflow: hidden;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			height: fit-content;
			position: sticky;
			top: 10px;

			/* Animation */
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
			height: 320px;
			object-fit: cover;
		}

		.details-content {
			padding: 20px;
		}

		.details-content h2 {
			font-size: 36px;
			margin-bottom: 18px;
		}

		.details-content p {
			margin-bottom: 12px;
			color: #444;
			line-height: 1.5;
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

		.reserve-btn:active {
			transform: scale(0.98);
		}

		.unavailable {
			background: #d1d5db !important;
			cursor: not-allowed;
			opacity: 0.8;
		}


		/*  ======== MESSAGE JEU PRIS EN COMPTE ========== */

		.success-message {
			background: #adf8c7;
			color: #15803d;
			border: 1px solid #bbf7d0;
			padding: 18px;
			border-radius: 18px;
			margin-bottom: 25px;
			font-weight: bold;
			line-height: 1.5;
		}


		/*  ======== RESPONSIVE  ========== */

		@media(max-width:1200px) {
			.games-grid {
				grid-template-columns: repeat(3, 1fr);
			}
		}

		@media(max-width:900px) {
			.content {
				flex-direction: column;
			}

			.details-panel {
				width: 100%;
			}

			.games-grid {
				grid-template-columns: repeat(2, 1fr);
			}
		}

		@media(max-width:650px) {
			.sidebar {
				display: none;
			}

			.games-grid {
				grid-template-columns: 1fr;
			}

			.title {
				flex-direction: column;
				align-items: flex-start;
			}

			.title h1 {
				font-size: 34px;
			}

			.title p {
				font-size: 18px;
			}
		}
		
		/*  ======== STATUT DU JEU  ========== */
		
		.game-status{
			display:inline-block;
			margin-bottom:12px;
			padding:6px 12px;
			border-radius:20px;
			font-size:13px;
			font-weight:bold;
		}

		.available-status{
			background:#dcfce7;
			color:#15803d;
		}

		.unavailable-status{
			background:#fee2e2;
			color:#b91c1c;
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
			<option value="default">
				Trier par
			</option>

			<option value="az">
				A → Z
			</option>

			<option value="za">
				Z → A
			</option>

			<option value="dureemin">
				Temps minimum
			</option>

			<option value="persomin">
				Nombre de joueurs minimum
			</option>
		</select>

    </div>

	<h1>Jeux à emprunter</h1>
	
	<p> Ces jeux vous sont proposés par l'association CPGames </p>
	
    <div class="content">

      <section class="games-section">
		
		<?php if (!empty($message)) : ?>

			<div class="success-message">
				<?php echo $message; ?>
			</div>

		<?php endif; ?>

        <div class="games-grid" id="gamesGrid">

			<?php foreach ($games as $game) : ?>

				<div class="game-card"
					 data-id="<?php echo $game['id']; ?>"
					 data-available="<?php echo $game['available']; ?>"
					 data-title="<?php echo htmlspecialchars($game['name']); ?>"
					 data-dureemin="<?php echo htmlspecialchars($game['min_duration']); ?>"
					 data-persomin="<?php echo htmlspecialchars($game['min_players']); ?>"
					 data-image="images/<?php echo htmlspecialchars($game['image']); ?>"
					 data-description="<?php echo htmlspecialchars($game['description']); ?>">

					<img src="images/<?php echo htmlspecialchars($game['image']); ?>" 
						 alt="<?php echo htmlspecialchars($game['name']); ?>">

					<div class="game-info">
						<h3><?php echo htmlspecialchars($game['name']); ?></h3>
						
						<?php if ($game["available"] > 0) : ?>

							<span class="game-status available-status">
								Disponible
							</span>

						<?php else : ?>

							<span class="game-status unavailable-status">
								Indisponible
							</span>

						<?php endif; ?>
						<p><strong>Genre :</strong> <?php echo htmlspecialchars($game['genre']); ?></p>
						<p><strong>Joueurs :</strong> <?php echo htmlspecialchars($game['players']); ?></p>
						<p><strong>Durée :</strong> <?php echo htmlspecialchars($game['duration']); ?></p>
						<p><strong>Disponibles :</strong> <?php echo htmlspecialchars($game['available']); ?> / <?php echo htmlspecialchars($game['quantity']); ?></p>
					</div>
				</div>

			<?php endforeach; ?>

		</div>

      </section>

      <aside class="details-panel">

		  <img 
			id="detailsImage"
			src="images/monopoly.jpg" 
			alt="Jeu sélectionné"
		  >

		  <div class="details-content">

			<h2 id="detailsTitle">
			  Monopoly
			</h2>

			<p id="detailsDescription">
			  Le roi des classiques pour tester la solidité de vos colocations. 
			  Idéal pour libérer le requin de la finance qui sommeille en vous. 
			  Vous achetez, vous construisez, et surtout, vous encaissez les loyers 
			  de vos potes qui ont le malheur de tomber chez vous.
			  <br><br>
			  C’est le jeu parfait pour les longues soirées pluvieuses où vous avez 
			  envie de négocier fermement et de voir qui, parmi vos amis, est prêt 
			  à vous trahir pour une gare.
			</p>
			
			<?php if ($isConnected) : ?>

				<form method="POST" id="reserveForm">
					<input type="hidden" name="game_id" id="selectedGameId">
					<button type="submit" name="reserve_game" class="reserve-btn" id="reserveButton">
						Réserver
					</button>
				</form>

			<?php else : ?>

				<button class="reserve-btn" onclick="window.location.href='connexion.php'">
					Connectez-vous pour réserver
				</button>

			<?php endif; ?>

		  </div>

		</aside>

    </div>

  </main>

  <script>
	const searchInput = document.getElementById("searchInput");
	const gameCards = document.querySelectorAll(".game-card");

	const detailsTitle = document.getElementById("detailsTitle");
	const detailsDescription = document.getElementById("detailsDescription");
	const detailsImage = document.getElementById("detailsImage");
	const detailsPanel = document.querySelector(".details-panel");

	/* Recherche */

	searchInput.addEventListener("keyup", function () {

	  const value = this.value.toLowerCase();

	  gameCards.forEach(card => {

		const title = card.dataset.title.toLowerCase();

		if(title.includes(value)){
		  card.style.display = "block";
		} else {
		  card.style.display = "none";
		}

	  });

	});

	function resetSearch(){

	  searchInput.value = "";

	  gameCards.forEach(card => {
		card.style.display = "block";
	  });

	}

	gameCards.forEach(card => {

		card.addEventListener("click", () => {
		
		detailsPanel.classList.remove("show");
		gameCards.forEach(c => c.classList.remove("active"));

		card.classList.add("active");

		const title = card.dataset.title;
		
		detailsDescription.innerHTML = card.dataset.description;
		detailsImage.src = card.dataset.image;

		detailsTitle.textContent = title;

		updateReserveButton(card);

		setTimeout(() => {
		  detailsPanel.classList.add("show");
		}, 50);
	  });

	});

	gameCards[0].classList.add("active");
	
	detailsTitle.textContent = gameCards[0].dataset.title;
	detailsDescription.innerHTML = gameCards[0].dataset.description;
	detailsImage.src = gameCards[0].dataset.image;
	
	updateReserveButton(gameCards[0]);
	
	window.addEventListener("load", () => {

	  setTimeout(() => {
		detailsPanel.classList.add("show");
	  }, 200);

	});
	
	const sortSelect =
	  document.getElementById("sortSelect");

	const gamesGrid =
	  document.getElementById("gamesGrid");

	sortSelect.addEventListener("change", () => {

	  const cards =
		Array.from(document.querySelectorAll(".game-card"));

	  const value = sortSelect.value;

	  cards.sort((a, b) => {

		
		if(value === "az"){

		  return a.dataset.title.localeCompare(
			b.dataset.title
		  );

		}
		
		if(value === "za"){

		  return b.dataset.title.localeCompare(
			a.dataset.title
		  );

		}

		if(value === "dureemin"){

		  return Number(a.dataset.dureemin)
			- Number(b.dataset.dureemin);

		}

		if(value === "persomin"){

		  return Number(a.dataset.persomin)
			- Number(b.dataset.persomin);

		}

		return 0;

	  });

	  cards.forEach(card => {
		gamesGrid.appendChild(card);
	  });

	});
	
	function updateReserveButton(card) {
		const gameId = card.dataset.id;
		const available = Number(card.dataset.available);

		const selectedInput = document.getElementById("selectedGameId");
		const reserveButton = document.getElementById("reserveButton");

		if (selectedInput) {
			selectedInput.value = gameId;
		}

		if (reserveButton) {
			if (available <= 0) {
				reserveButton.textContent = "Indisponible";
				reserveButton.disabled = true;
				reserveButton.classList.add("unavailable");
			} else {
				reserveButton.textContent = "Réserver";
				reserveButton.disabled = false;
				reserveButton.classList.remove("unavailable");
			}
		}
	}

	</script>

</body>
</html>