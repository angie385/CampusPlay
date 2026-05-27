<?php
require_once "auth.php";
requireMember();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OffCampus - Tableau de bord membre</title>

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

.logo-box {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 40px;
    width: 100%;
}

.logo-box img {
    width: 110px;
    height: 110px;
    object-fit: contain;
}

.logo-text {
    display: none;
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


/*  ======== MAIN  ========== */

.main {
    flex: 1;
    padding: 28px 34px;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    margin-bottom: 28px;
}

.welcome h1 {
    font-size: 34px;
    color: #111827;
    margin-bottom: 7px;
}

.welcome p {
    color: #666;
    line-height: 1.5;
}

.top-actions {
    display: flex;
    gap: 12px;
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

.btn-blue {
    background: #2563eb;
    color: white;
}

.btn-blue:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

.btn-green {
    background: #16a34a;
    color: white;
}

.btn-green:hover {
    background: #15803d;
    transform: translateY(-1px);
}

.btn-orange {
    background: #f97316;
    color: white;
}

.btn-orange:hover {
    background: #ea580c;
    transform: translateY(-1px);
}


/*  ======== STATS  ========== */

.stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 5px 16px rgba(0,0,0,0.06);
}

.stat-card .icon {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    font-size: 22px;
}

.blue-soft { background: #e8f0ff; }
.green-soft { background: #ecfdf5; }
.orange-soft { background: #fff7ed; }
.purple-soft { background: #f3e8ff; }

.stat-card h2 {
    font-size: 30px;
    margin-bottom: 6px;
    color: #111827;
}

.stat-card p {
    color: #666;
    font-size: 14px;
}


/*  ======== CONTENT PANELS  ========== */

.content {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 24px;
}

.panel {
    background: white;
    border-radius: 22px;
    padding: 22px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 5px 16px rgba(0,0,0,0.06);
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.panel-header h2 {
    font-size: 23px;
    color: #111827;
}

.small-link {
    text-decoration: none;
    color: #2563eb;
    font-weight: bold;
    font-size: 14px;
}


/*  ======== EVENTS LIST  ========== */

.event-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.event-row {
    display: grid;
    grid-template-columns: 70px 1fr auto;
    gap: 16px;
    align-items: center;
    padding: 14px;
    border-radius: 17px;
    background: #f9fafb;
    border: 1px solid #edf0f5;
}

.date-box {
    width: 65px;
    height: 65px;
    border-radius: 16px;
    background: #e8f0ff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #2563eb;
    font-weight: bold;
}

.date-box span {
    font-size: 13px;
}

.date-box strong {
    font-size: 22px;
}

.event-info h3 {
    margin-bottom: 6px;
    color: #111827;
}

.event-info p {
    color: #666;
    font-size: 14px;
    margin-bottom: 4px;
}

.status {
    padding: 8px 12px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 13px;
    white-space: nowrap;
}

.status.green {
    background: #dcfce7;
    color: #166534;
}

.status.orange {
    background: #ffedd5;
    color: #9a3412;
}

.status.blue {
    background: #dbeafe;
    color: #1e40af;
}

/* ======== DEMANDES EN ATTENTE ======== */

.request-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.request {
    padding: 16px;
    border-radius: 18px;
    background: #f9fafb;
    border: 1px solid #edf0f5;
}

.request h3 {
    font-size: 18px;
    color: #111827;
    margin-bottom: 6px;
}

.request p {
    color: #666;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 12px;
}

.request-actions {
    display: flex;
    gap: 10px;
}

.mini-btn {
    border: none;
    border-radius: 10px;
    padding: 9px 14px;
    font-size: 13px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.accept {
    background: #16a34a;
    color: white;
}

.accept:hover {
    background: #15803d;
}

.refuse {
    background: #fee2e2;
    color: #991b1b;
}

.refuse:hover {
    background: #fecaca;
}


</style>
</head>

<body>

    <div class="container">

        <!-- MENU GAUCHE -->
        <aside class="sidebar">
            <div>
                <div class="logo-box">
                    <a href = "accueil.php"> <img src="images/logo.jpeg" alt="Logo Off Campus"></a>
                    <div class="logo-text">Off<span>Campus</span></div>
                </div>

                <nav class="menu">
					<a href="accueil.html">Accueil</a>
					<a href="activite.php">Événements / Activités</a>
					<a href="dashboard-membre.php" class="active">Tableau de bord</a>
					<a href="evenement-membre.php">Créer un événement</a>
					<a href="reservations.html">Réservations</a>
					<a href="#">Notifications</a>
					<a href="#">À propos</a>
					<a href="profil.php">Mon compte</a>
				</nav>
            </div>

            <div class="user-box" onclick="window.location.href='profil.php'">
				<div class="avatar">
					<?php echo strtoupper(substr($_SESSION["surname"] ?? "M", 0, 1)); ?>
				</div>

				<div>
					<strong>
						<?php echo htmlspecialchars($_SESSION["surname"] ?? "Membre"); ?>
					</strong>

					<p>Membre association</p>
				</div>
			</div>
        </aside>

        <!-- CONTENU PRINCIPAL -->
        <main class="main">

            <section class="top-bar">
                <div class="welcome">
                    <h1>Tableau de bord associatif</h1>
                    <p>
                        Suivez les événements, les demandes et l’activité de votre association depuis un seul espace.
                    </p>
                </div>

                <div class="top-actions">
                    <a href="evenement-membre.php" class="btn btn-green">Créer un événement</a>
                    <a href="activite.php" class="btn btn-blue">Voir les activités</a>
                </div>
            </section>

            <!-- STATISTIQUES -->
            <section class="stats">
                <div class="stat-card">
                    <div class="icon blue-soft">📅</div>
                    <h2 id="totalEvents">0</h2>
                    <p>Événements créés</p>
                </div>

                <div class="stat-card">
                    <div class="icon green-soft">✅</div>
                    <h2 id="publishedEvents">0</h2>
                    <p>Événements publiés</p>
                </div>

                <div class="stat-card">
                    <div class="icon orange-soft">⏳</div>
                    <h2 id="pendingEvents">0</h2>
                    <p>Événements à valider</p>
                </div>

                <div class="stat-card">
                    <div class="icon purple-soft">🎲</div>
                    <h2 id="resourcesCount">0</h2>
                    <p>Ressources utilisées</p>
                </div>
            </section>

            <section class="content">

                <!-- EVENEMENTS -->
                <div class="panel">
                    <div class="panel-header">
                        <h2>Événements à venir</h2>
                        <a href="evenement-membre.php" class="small-link">Modifier / créer</a>
                    </div>

                    <div class="event-list" id="dashboardEventsList">
                        <div class="loading-message">Chargement des événements depuis la base de données...</div>
                    </div>
                </div>

                <!-- COLONNE DROITE -->
                <div class="right-column">

                    <div class="panel">
                        <div class="panel-header">
                            <h2>Demandes en attente</h2>
                            <a href="#" class="small-link">Tout voir</a>
                        </div>

                        <div class="request-list">

                            <div class="request">
                                <h3>Réservation salle polyvalente</h3>
                                <p>Demande liée aux prochains événements associatifs.</p>
                                <div class="request-actions">
                                    <button class="mini-btn accept">Valider</button>
                                    <button class="mini-btn refuse">Refuser</button>
                                </div>
                            </div>

                            <div class="request">
                                <h3>Emprunt matériel audio</h3>
                                <p>Micro + enceinte pour une activité culturelle.</p>
                                <div class="request-actions">
                                    <button class="mini-btn accept">Valider</button>
                                    <button class="mini-btn refuse">Refuser</button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-header">
                            <h2>Activité récente</h2>
                        </div>

                        <div class="activity-list" id="activityList">
                            <div class="activity">
                                <div class="dot"></div>
                                <p>Chargement de l’activité récente...</p>
                            </div>
                        </div>
                    </div>

                </div>

            </section>

        </main>

    </div>

    <script>
        const API_URL = "events_api.php";

        const dashboardEventsList = document.getElementById("dashboardEventsList");
        const activityList = document.getElementById("activityList");

        const totalEvents = document.getElementById("totalEvents");
        const publishedEvents = document.getElementById("publishedEvents");
        const pendingEvents = document.getElementById("pendingEvents");
        const resourcesCount = document.getElementById("resourcesCount");

        function formatDashboardDate(dateString) {
            const date = new Date(dateString);

            const months = [
                "JAN", "FÉV", "MAR", "AVR", "MAI", "JUIN",
                "JUIL", "AOÛT", "SEP", "OCT", "NOV", "DÉC"
            ];

            return {
                day: String(date.getDate()).padStart(2, "0"),
                month: months[date.getMonth()]
            };
        }

        function getDashboardStatusClass(status) {
            if (status === "Publié") {
                return "green";
            }

            if (status === "À valider") {
                return "orange";
            }

            return "blue";
        }

        function updateStats(events) {
            const published = events.filter(event => event.status === "Publié").length;
            const pending = events.filter(event => event.status === "À valider").length;

            const resources = events
                .map(event => event.resource)
                .filter(resource => resource && resource !== "Aucune");

            totalEvents.textContent = events.length;
            publishedEvents.textContent = published;
            pendingEvents.textContent = pending;
            resourcesCount.textContent = resources.length;
        }

        function renderEvents(events) {
            dashboardEventsList.innerHTML = "";

            if (events.length === 0) {
                dashboardEventsList.innerHTML = `
                    <div class="empty-message">
                        Aucun événement enregistré pour le moment.
                        Créez un événement depuis la page de gestion des événements.
                    </div>
                `;
                return;
            }

            events.forEach(event => {
                const date = formatDashboardDate(event.event_date);

                const eventRow = document.createElement("article");
                eventRow.className = "event-row";

                eventRow.innerHTML = `
                    <div class="date-box">
                        <span>${date.month}</span>
                        <strong>${date.day}</strong>
                    </div>

                    <div class="event-info">
                        <h3>${event.name}</h3>
                        <p>${event.place} — ${event.event_time}</p>
                        <p>${event.capacity} places — ${event.category}</p>
                    </div>

                    <div class="status ${getDashboardStatusClass(event.status)}">
                        ${event.status}
                    </div>
                `;

                dashboardEventsList.appendChild(eventRow);
            });
        }

        function renderActivity(events) {
            activityList.innerHTML = "";

            if (events.length === 0) {
                activityList.innerHTML = `
                    <div class="activity">
                        <div class="dot"></div>
                        <p>Aucune activité récente pour le moment.</p>
                    </div>
                `;
                return;
            }

            const recentEvents = events.slice(0, 3);

            recentEvents.forEach(event => {
                let dotClass = "";

                if (event.status === "Publié") {
                    dotClass = "green";
                } else if (event.status === "À valider") {
                    dotClass = "orange";
                }

                const activity = document.createElement("div");
                activity.className = "activity";

                activity.innerHTML = `
                    <div class="dot ${dotClass}"></div>
                    <p>
                        <strong>${event.name}</strong> est enregistré avec le statut
                        <strong>${event.status}</strong>.
                    </p>
                `;

                activityList.appendChild(activity);
            });
        }

        async function loadDashboardEvents() {
            try {
                const response = await fetch(API_URL);
                const data = await response.json();

                if (!data.success) {
                    dashboardEventsList.innerHTML = `
                        <div class="error-message">
                            Impossible de récupérer les événements depuis la base de données.
                        </div>
                    `;
                    return;
                }

                const events = data.events;

                updateStats(events);
                renderEvents(events);
                renderActivity(events);

            } catch (error) {
                dashboardEventsList.innerHTML = `
                    <div class="error-message">
                        Erreur serveur. Vérifie que WampServer est lancé et que tu ouvres la page avec localhost.
                    </div>
                `;

                activityList.innerHTML = `
                    <div class="activity">
                        <div class="dot orange"></div>
                        <p>Impossible de charger l’activité récente.</p>
                    </div>
                `;
            }
        }

        loadDashboardEvents();
    </script>

</body>
</html>