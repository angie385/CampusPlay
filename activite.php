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
<title>Off Campus - Activités</title>

<style>
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

.main {
    flex: 1;
    padding: 40px;
    min-width: 0;
}

.logo {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 40px;
    width: 100%;
}

.logo img {
    width: 110px;
    height: 110px;
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

h1 {
    font-size: 32px;
    margin-bottom: 25px;
}

.categories {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.category {
    border: none;
    padding: 10px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: .2s;
}

.category:hover {
    transform: translateY(-2px);
    opacity: .9;
}

.category.active {
    outline: 3px solid rgba(79, 99, 232, .25);
}

.green { background: #e9f9ef; color: #1f8f4d; }
.blue { background: #4f63e8; color: white; }
.yellow { background: #fff7d6; color: #b7791f; }
.pink { background: #ffe8f1; color: #c02660; }
.purple { background: #f1e8ff; color: #7c3aed; }
.blue-light { background: #e8f2ff; color: #2563eb; }

.content {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 350px;
    gap: 35px;
    align-items: start;
}

.events-list {
    display: flex;
    flex-direction: column;
    gap: 25px;
    min-width: 0;
}

.event-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    padding: 22px;
    display: grid;
    grid-template-columns: 210px minmax(0, 1fr) 170px;
    gap: 25px;
    align-items: center;
    box-shadow: 0 8px 25px rgba(0,0,0,.04);
}

.event-image {
    width: 100%;
    height: 200px;
    border-radius: 18px;
    object-fit: cover;
}

.event-info h2 {
    font-size: 24px;
    margin-bottom: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.description {
    color: #555;
    font-size: 15px;
    margin-bottom: 16px;
    line-height: 1.5;
}

.meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    color: #666;
    font-size: 14px;
    margin-bottom: 10px;
}

.event-side {
    text-align: right;
}

.places {
    display: inline-block;
    background: #eaf9ef;
    color: #229954;
    padding: 9px 16px;
    border-radius: 20px;
    font-weight: bold;
    margin-bottom: 14px;
}

.event-btn,
.secondary-btn,
.danger-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 14px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    margin-top: 8px;
}

.event-btn { background: #4f63e8; }
.secondary-btn { background: #16a34a; }
.danger-btn { background: #ef4444; }
.full { background: #fff1f2; color: #be123c; }

.right-panel {
    position: sticky;
    top: 30px;
}

.calendar,
.notifications,
.details {
    background: white;
    border-radius: 24px;
    padding: 24px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 8px 25px rgba(0,0,0,.04);
    margin-bottom: 22px;
}

.notifications {
    padding: 22px;
}

.notifications h3 {
    font-size: 24px;
    margin-bottom: 18px;
}

#notificationsList {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.notification {
    position: relative;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    padding: 12px 14px 12px 34px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.4;
    color: #444;
    cursor: pointer;
}

.notification::before {
    content: "";
    position: absolute;
    left: 14px;
    top: 17px;
    width: 9px;
    height: 9px;
    background: #22c55e;
    border-radius: 50%;
}

.notification.read {
    background: #f1f1f1;
    color: #777;
}

.notification.read::before {
    display: none;
}

.container.detail-mode .sidebar {
    display: none;
}

.container.detail-mode .main {
    padding: 28px;
}

.container.detail-mode .content {
    grid-template-columns: minmax(0, 1fr) 300px 360px;
    gap: 22px;
}

.container.detail-mode .right-panel {
    width: 300px;
}

.container.detail-mode .details {
    display: block;
}

.container.detail-mode .notifications {
    display: none;
}

.side-detail-image {
    width: 100%;
    height: 170px;
    object-fit: cover;
    border-radius: 18px 18px 0 0;
}

.details {
    position: relative;
    padding: 0 0 22px 0;
    overflow: hidden;
}

.details h3 {
    font-size: 24px;
    margin: 18px 22px 10px;
    white-space: nowrap;
}

.side-description,
.side-detail-info {
    margin: 0 22px 18px;
    color: #555;
    line-height: 1.5;
    font-size: 15px;
}

.side-detail-info p {
    margin-bottom: 12px;
}

.close-detail {
    position: absolute;
    top: 12px;
    right: 16px;
    z-index: 5;
    border: none;
    background: rgba(255,255,255,0.85);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 22px;
    cursor: pointer;
}

.detail-back-btn {
    position: fixed;
    top: 25px;
    left: 25px;
    z-index: 1000;
    border: none;
    background: white;
    color: #4f63e8;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-size: 26px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.detail-tabs {
    display: flex;
    justify-content: space-around;
    border-top: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 18px;
}

.detail-tabs span {
    padding: 13px 6px;
    font-size: 14px;
    font-weight: bold;
    color: #555;
}

.detail-tabs .active-tab {
    color: #4f63e8;
    border-bottom: 3px solid #4f63e8;
}

.tags {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 0 22px 20px;
}

.tags span {
    background: #edf0ff;
    color: #4f63e8;
    padding: 7px 12px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: bold;
}

.details .secondary-btn,
.details .danger-btn,
.details .event-btn {
    width: calc(100% - 44px);
    margin: 10px 22px 20px;
}

.calendar-top,
.month {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.month {
    font-size: 20px;
    font-weight: bold;
}

.arrow {
    font-size: 25px;
    cursor: pointer;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    text-align: center;
    font-size: 14px;
}

.calendar-grid strong {
    color: #696b80;
}

.calendar-grid button {
    position: relative;
    border: none;
    background: transparent;
    padding: 10px 0;
    border-radius: 12px;
    cursor: pointer;
}

.calendar-grid button.selected-day {
    background: #e8f0ff;
    color: #4f63e8;
    border: 2px solid #4f63e8;
    font-weight: bold;
}

.calendar-grid button.event-dot::after {
    content: "";
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 5px;
    height: 5px;
    background: #22c55e;
    border-radius: 50%;
}

.calendar-grid button.reserved-day {
    background: #f0ffe8;
    border: 2px solid #47cf00;
    color: #47cf00;
    font-weight: bold;
}

.calendar-grid button.today-day {
    background: #ececec;
    color: #15162b;
    font-weight: bold;
}

.empty {
    background: white;
    padding: 25px;
    border-radius: 20px;
    color: #666;
    text-align: center;
}

@media (max-width: 1200px) {
    .container.detail-mode .content {
        grid-template-columns: minmax(0, 1fr) 280px 330px;
    }

    .container.detail-mode .right-panel {
        width: 280px;
    }

    .container.detail-mode .event-card {
        grid-template-columns: 160px minmax(0, 1fr) 130px;
        gap: 16px;
        padding: 16px;
    }

    .container.detail-mode .event-image {
        height: 110px;
    }

    .container.detail-mode .event-info h2 {
        font-size: 20px;
    }

    .container.detail-mode .description,
    .container.detail-mode .meta {
        font-size: 13px;
    }
}

@media (max-width: 1100px) {
    .content,
    .container.detail-mode .content {
        grid-template-columns: 1fr;
    }

    .right-panel,
    .container.detail-mode .right-panel {
        position: static;
        width: auto;
    }

    .details {
        position: static;
    }
}

@media (max-width: 850px) {
    .container {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        height: auto;
        position: static;
    }

    .menu {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .menu a {
        margin-bottom: 0;
    }

    .top-bar {
        flex-direction: column;
    }

    .event-card {
        grid-template-columns: 1fr;
    }

    .event-side {
        text-align: left;
    }
}
</style>
</head>

<body>
<div class="container">

<aside class="sidebar">
    <div>
        <div class="logo">
            <a href="accueil.php">
                <img src="images/logo.jpeg" alt="Logo Off Campus">
            </a>
        </div>

        <nav class="menu">
            <a href="accueil.php">Accueil</a>

            <a href="activite.php" class="active">
                Événements / Activités
            </a>

            <?php if ($role === "membre") : ?>
                <a href="dashboard-membre.php">Tableau de bord</a>
                <a href="evenement-membre.php">Créer un événement</a>
            <?php else : ?>
                <a href="jeux.php">Jeux</a>
            <?php endif; ?>

            <a href="reservation.html">Réservations</a>
            <a href="#">Notifications</a>
            <a href="a-propos.html">À propos</a>
            <a href="profil.php">Mon compte</a>

            <?php if ($role === "membre") : ?>
                <a href="deconnexion.php">Déconnexion</a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="user-box" onclick="window.location.href='profil.php'">
        <div class="avatar">
            <?php echo strtoupper(substr($_SESSION["surname"] ?? "N", 0, 1)); ?>
        </div>

        <div>
            <strong>
                <?php echo htmlspecialchars($_SESSION["surname"] ?? "Nina"); ?>
            </strong>

            <p id="roleText">
                <?php
                    echo ($_SESSION["role"] ?? "etudiant") === "membre"
                        ? "Membre association"
                        : "Étudiant";
                ?>
            </p>
        </div>
    </div>
</aside>

<main class="main">

    <div class="top-bar">
        <div class="search-bar">
            <input id="searchInput" type="text" placeholder="🔍 Rechercher un événement, une activité, un lieu...">
        </div>

        <select class="filter-btn" id="sortSelect" onchange="sortEvents()">
            <option value="">Trier</option>
            <option value="theme">Par thème</option>
            <option value="date">Par date</option>
            <option value="places">Par places disponibles</option>
        </select>
    </div>

    <h1>Événements à venir</h1>

    <div class="categories">
        <button class="category blue active" data-filter="Tous">⌘ Tous</button>
        <button class="category green" data-filter="Soirées jeux">🎮 Soirées jeux</button>
        <button class="category yellow" data-filter="Tournois">🏆 Tournois</button>
        <button class="category pink" data-filter="Ateliers">🎨 Ateliers</button>
        <button class="category purple" data-filter="Culture">🎭 Culture</button>
        <button class="category blue-light" data-filter="Sport">⚽ Sport</button>
        <button class="category blue-light" data-filter="Récurrents">🔄 Récurrents</button>
    </div>

    <section class="content">
        <div class="events-list" id="eventsList"></div>

        <aside class="right-panel">
            <div class="calendar">
                <div class="calendar-top">
                    <h3>Calendrier</h3>
                    <a href="#" onclick="resetFilters()">Voir tout</a>
                </div>

                <div class="month">
                    <span class="arrow" onclick="previousMonth()">‹</span>
                    <span id="calendarTitle"></span>
                    <span class="arrow" onclick="nextMonth()">›</span>
                </div>

                <div class="calendar-grid" id="calendarGrid"></div>
            </div>

            <div class="notifications">
                <h3>Notifications</h3>

                <div id="notificationsList">
                    <div class="notification unread" onclick="markAsRead(this)">
                        <p>Bienvenue sur Off Campus.</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="details" id="detailsBox"></div>
    </section>

</main>
</div>

<script>
const USER_ROLE = "<?php echo $role; ?>";
const IS_CONNECTED = <?php echo $isConnected ? "true" : "false"; ?>;

let selectedFilter = "Tous";
let selectedDay = null;
let events = [];

const API_URL = "events_api.php";

document.getElementById("roleText").textContent =
    USER_ROLE === "membre" ? "Membre association" : "Étudiant";

function parseLocalDate(dateString) {
    if (!dateString) {
        return new Date();
    }

    const parts = dateString.split("-");
    return new Date(parts[0], parts[1] - 1, parts[2]);
}

function formatLongDate(dateString) {
    const date = parseLocalDate(dateString);

    const jours = [
        "Dimanche", "Lundi", "Mardi", "Mercredi",
        "Jeudi", "Vendredi", "Samedi"
    ];

    const mois = [
        "janvier", "février", "mars", "avril", "mai", "juin",
        "juillet", "août", "septembre", "octobre", "novembre", "décembre"
    ];

    return `${jours[date.getDay()]} ${date.getDate()} ${mois[date.getMonth()]} ${date.getFullYear()}`;
}

function getEventImage(event) {
    if (event.image && event.image.trim() !== "") {
        return event.image;
    }

    return "Jeu de societe.jpg";
}

function convertApiEvent(event) {
    const eventDate = parseLocalDate(event.event_date);

    return {
        id: Number(event.id),
        title: event.name || "Événement sans nom",
        category: event.category || "Autre",
        image: getEventImage(event),
        description: event.description || "",
        date: formatLongDate(event.event_date),
        day: eventDate.getDate(),
        fullDate: event.event_date,
        time: event.event_time || "Horaire non précisé",
        place: event.place || "Lieu non précisé",
        capacity: Number(event.capacity || 0),
        registered: Number(event.registered || 0),
        status: event.status || "Ouvert",
        joined: false,
        waiting: false
    };
}

async function loadEvents() {
    try {
        const response = await fetch(API_URL);
        const data = await response.json();

        if (!data.success) {
            document.getElementById("eventsList").innerHTML = `
                <div class="empty">Impossible de charger les événements.</div>
            `;
            return;
        }

        events = data.events.map(convertApiEvent);

        renderEvents();
        renderCalendar();

    } catch (error) {
        document.getElementById("eventsList").innerHTML = `
            <div class="empty">
                Erreur serveur. Vérifie que WampServer est lancé et que tu ouvres la page avec localhost.
            </div>
        `;
    }
}

function renderEvents() {
    const list = document.getElementById("eventsList");
    const search = document.getElementById("searchInput").value.toLowerCase();

    const filtered = events.filter(event => {
        const matchSearch =
            event.title.toLowerCase().includes(search) ||
            event.description.toLowerCase().includes(search) ||
            event.place.toLowerCase().includes(search);

        const matchCategory =
            selectedFilter === "Tous" || event.category === selectedFilter;

        const matchDay =
            selectedDay === null || event.day === selectedDay;

        return matchSearch && matchCategory && matchDay;
    });

    list.innerHTML = "";

    if (filtered.length === 0) {
        list.innerHTML = `<div class="empty">Aucun événement ne correspond à ta recherche.</div>`;
        return;
    }

    filtered.forEach(event => {
        const isFull = event.registered >= event.capacity && event.capacity > 0;

        const card = document.createElement("article");
        card.className = "event-card";

        card.innerHTML = `
            <img src="images/${event.image}" alt="${event.title}" class="event-image">

            <div class="event-info">
                <h2>${event.title}</h2>
                <p class="description">${event.description}</p>

                <div class="meta">
                    <span>📅 ${event.date}</span>
                    <span>🕒 ${event.time}</span>
                </div>

                <div class="meta">
                    <span>📍 ${event.place}</span>
                    <span>🏷️ ${event.category}</span>
                </div>
            </div>

            <div class="event-side">
                <div class="places ${isFull ? 'full' : ''}">
                    ${event.registered} / ${event.capacity} inscrits
                </div>

                <button class="event-btn" onclick="showDetails(${event.id})">
                    Voir détails
                </button>

                ${memberButton(event)}
            </div>
        `;

        list.appendChild(card);
    });
}

function studentButton(event, isFull) {
    if (USER_ROLE !== "etudiant") return "";

    if (event.joined) {
        return `<button class="danger-btn" onclick="leaveEvent(${event.id})">Se désinscrire</button>`;
    }

    if (event.waiting) {
        return `<button class="danger-btn" onclick="leaveWaitingList(${event.id})">Quitter liste d'attente</button>`;
    }

    if (isFull) {
        return `<button class="secondary-btn" onclick="joinWaitingList(${event.id})">Rejoindre liste d'attente</button>`;
    }

    return `<button class="secondary-btn" onclick="joinEvent(${event.id})">S'inscrire</button>`;
}

function memberButton(event) {
    if (USER_ROLE !== "membre") return "";

    return `
        <button class="secondary-btn" onclick="window.location.href='evenement-membre.php'">Gérer</button>
        <button class="danger-btn" onclick="cancelEvent(${event.id})">Annuler</button>
    `;
}

function showDetails(id) {
    document.querySelector(".container").classList.add("detail-mode");

    const event = events.find(e => e.id === id);

    if (!event) return;

    const isFull = event.registered >= event.capacity && event.capacity > 0;

    document.getElementById("detailsBox").innerHTML = `
        <button class="detail-back-btn" onclick="closeDetails()">←</button>
        <button class="close-detail" onclick="closeDetails()">×</button>

        <img src="images/${event.image}" alt="${event.title}" class="side-detail-image">

        <h3>${event.title}</h3>

        <p class="side-description">${event.description}</p>

        <div class="side-detail-info">
            <p>📅 ${event.date}</p>
            <p>🕒 ${event.time}</p>
            <p>📍 ${event.place}</p>
            <p>👥 ${event.registered} / ${event.capacity} inscrits</p>
            <p>💸 Gratuit</p>
        </div>

        <div class="detail-tabs">
            <span class="active-tab">À propos</span>
            <span>Participants</span>
            <span>Programme</span>
        </div>

        <p class="side-description">
            Rejoignez-nous pour un moment convivial autour de cette activité.
        </p>

        <div class="tags">
            <span>${event.category}</span>
            <span>Convivialité</span>
            <span>Détente</span>
        </div>

        ${studentButton(event, isFull)}
    `;
}

function closeDetails() {
    document.querySelector(".container").classList.remove("detail-mode");
    document.getElementById("detailsBox").innerHTML = "";
}

function joinEvent(id) {
    if (!IS_CONNECTED) {
        window.location.href = "connexion.php";
        return;
    }

    const event = events.find(e => e.id === id);

    if (event.registered < event.capacity && !event.joined) {
        event.registered++;
        event.joined = true;
        addNotification(`Inscription confirmée : ${event.title}`);
    }

    renderEvents();
    renderCalendar();
    showDetails(id);
}

function leaveEvent(id) {
    const event = events.find(e => e.id === id);

    if (event.joined) {
        event.registered--;
        event.joined = false;
        addNotification(`Désinscription effectuée : ${event.title}`);
    }

    renderEvents();
    renderCalendar();
    showDetails(id);
}

function joinWaitingList(id) {
    if (!IS_CONNECTED) {
        window.location.href = "connexion.php";
        return;
    }

    const event = events.find(e => e.id === id);
    event.waiting = true;
    addNotification(`Tu es sur liste d’attente pour : ${event.title}`);
    renderEvents();
    showDetails(id);
}

function leaveWaitingList(id) {
    const event = events.find(e => e.id === id);
    event.waiting = false;
    addNotification(`Tu as quitté la liste d’attente : ${event.title}`);
    renderEvents();
    showDetails(id);
}

async function cancelEvent(id) {
    const event = events.find(e => e.id === id);

    if (!event) return;

    if (!confirm("Voulez-vous vraiment annuler cet événement ?")) {
        return;
    }

    try {
        const response = await fetch(`${API_URL}?id=${id}`, {
            method: "DELETE"
        });

        const data = await response.json();

        if (data.success) {
            addNotification(`Événement annulé : ${event.title}`);
            await loadEvents();

            document.getElementById("detailsBox").innerHTML = `
                <h3>Détail événement</h3>
                <p>L'événement a été annulé.</p>
            `;
        } else {
            alert(data.message || "Impossible d'annuler cet événement.");
        }
    } catch (error) {
        alert("Erreur de connexion avec le serveur PHP.");
    }
}

function addNotification(message) {
    const list = document.getElementById("notificationsList");

    const notif = document.createElement("div");
    notif.className = "notification unread";
    notif.setAttribute("onclick", "markAsRead(this)");

    notif.innerHTML = `<p>${message}</p>`;

    list.prepend(notif);
}

function markAsRead(notification) {
    notification.classList.add("read");
}

let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

function renderCalendar() {
    const grid = document.getElementById("calendarGrid");

    const monthNames = [
        "Janvier", "Février", "Mars", "Avril",
        "Mai", "Juin", "Juillet", "Août",
        "Septembre", "Octobre", "Novembre", "Décembre"
    ];

    document.getElementById("calendarTitle").textContent =
        `${monthNames[currentMonth]} ${currentYear}`;

    grid.innerHTML = "";

    const daysHeader = ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"];

    daysHeader.forEach(day => {
        const strong = document.createElement("strong");
        strong.textContent = day;
        grid.appendChild(strong);
    });

    const firstDay = new Date(currentYear, currentMonth, 1);

    let startDay = firstDay.getDay();
    startDay = startDay === 0 ? 6 : startDay - 1;

    const daysInMonth =
        new Date(currentYear, currentMonth + 1, 0).getDate();

    for (let i = 0; i < startDay; i++) {
        const empty = document.createElement("div");
        grid.appendChild(empty);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const button = document.createElement("button");
        button.textContent = day;

        const today = new Date();

        const isToday =
            day === today.getDate() &&
            currentMonth === today.getMonth() &&
            currentYear === today.getFullYear();

        if (isToday) {
            button.classList.add("today-day");
        }

        const hasEvent = events.some(event => {
            const eventDate = parseLocalDate(event.fullDate);

            return (
                eventDate.getDate() === day &&
                eventDate.getMonth() === currentMonth &&
                eventDate.getFullYear() === currentYear
            );
        });

        const hasReservation = events.some(event => {
            const eventDate = parseLocalDate(event.fullDate);

            return (
                event.joined &&
                eventDate.getDate() === day &&
                eventDate.getMonth() === currentMonth &&
                eventDate.getFullYear() === currentYear
            );
        });

        if (hasEvent) {
            button.classList.add("event-dot");
        }

        if (hasReservation) {
            button.classList.add("reserved-day");
        }

        if (selectedDay === day) {
            button.classList.add("selected-day");
        }

        button.onclick = () => {
            selectedDay = day;
            renderCalendar();
            renderEvents();
        };

        grid.appendChild(button);
    }
}

function previousMonth() {
    currentMonth--;

    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }

    renderCalendar();
}

function nextMonth() {
    currentMonth++;

    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }

    renderCalendar();
}

document.querySelectorAll(".category").forEach(button => {
    button.addEventListener("click", () => {
        document.querySelectorAll(".category").forEach(btn => btn.classList.remove("active"));
        button.classList.add("active");
        selectedFilter = button.dataset.filter;
        renderEvents();
    });
});

document.getElementById("searchInput").addEventListener("input", renderEvents);

function resetFilters() {
    selectedFilter = "Tous";
    selectedDay = null;
    document.getElementById("searchInput").value = "";

    document.querySelectorAll(".category").forEach(btn => btn.classList.remove("active"));
    document.querySelector('[data-filter="Tous"]').classList.add("active");

    renderCalendar();
    renderEvents();
}

function sortEvents() {
    const sortValue = document.getElementById("sortSelect").value;

    if (sortValue === "theme") {
        events.sort((a, b) => a.category.localeCompare(b.category));
    }

    if (sortValue === "date") {
        events.sort((a, b) => parseLocalDate(a.fullDate) - parseLocalDate(b.fullDate));
    }

    if (sortValue === "places") {
        events.sort((a, b) => {
            const placesA = a.capacity - a.registered;
            const placesB = b.capacity - b.registered;
            return placesB - placesA;
        });
    }

    renderEvents();
    renderCalendar();
}

loadEvents();
</script>

</body>
</html>