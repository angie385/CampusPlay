<?php
require_once "auth.php";
requireMember();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OffCampus - Gestion des événements</title>

    <style>
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

/* SIDEBAR */

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
    display: block;
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

/* MAIN */

.main {
    flex: 1;
    padding: 28px 34px;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    margin-bottom: 26px;
}

.title h1 {
    font-size: 34px;
    color: #111827;
    margin-bottom: 8px;
}

.title p {
    color: #666;
    line-height: 1.5;
}

/* BUTTONS */

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
}

/* CONTENT */

.content {
    display: grid;
    grid-template-columns: 1fr 1.25fr;
    gap: 24px;
}

.panel {
    background: white;
    border-radius: 22px;
    padding: 24px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 5px 16px rgba(0,0,0,0.06);
}

.panel h2 {
    font-size: 24px;
    margin-bottom: 18px;
    color: #111827;
}

/* FORM */

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group.full {
    grid-column: 1 / -1;
}

label {
    display: block;
    font-weight: bold;
    color: #374151;
    margin-bottom: 8px;
    font-size: 14px;
}

input,
select,
textarea {
    width: 100%;
    padding: 13px 15px;
    border-radius: 14px;
    border: 1px solid #d1d5db;
    outline: none;
    font-size: 15px;
    background: white;
}

input[type="file"] {
    padding: 11px;
    cursor: pointer;
}

textarea {
    min-height: 110px;
    resize: vertical;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.image-preview {
    display: none;
    margin-top: 12px;
    width: 100%;
    max-height: 190px;
    object-fit: cover;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
}

.image-help {
    color: #666;
    font-size: 13px;
    margin-top: 7px;
    line-height: 1.4;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 10px;
    flex-wrap: wrap;
}

.info-box {
    margin-top: 18px;
    padding: 14px;
    border-radius: 16px;
    background: #e8f0ff;
    color: #1e40af;
    font-size: 14px;
    line-height: 1.5;
}

.success-box {
    display: none;
    margin-top: 18px;
    padding: 14px;
    border-radius: 16px;
    background: #dcfce7;
    color: #166534;
    font-size: 14px;
    font-weight: bold;
}

.error-box {
    display: none;
    margin-top: 18px;
    padding: 14px;
    border-radius: 16px;
    background: #fee2e2;
    color: #991b1b;
    font-size: 14px;
    font-weight: bold;
}

/* EVENTS LIST */

.events-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.event-card {
    display: grid;
    grid-template-columns: 70px 1fr;
    gap: 15px;
    padding: 16px;
    border-radius: 18px;
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
    font-size: 12px;
}

.date-box strong {
    font-size: 21px;
}

.event-content h3 {
    color: #111827;
    margin-bottom: 6px;
}

.event-content p {
    color: #666;
    font-size: 14px;
    margin-bottom: 5px;
    line-height: 1.4;
}

.event-thumb {
    width: 100%;
    max-width: 260px;
    height: 135px;
    object-fit: cover;
    border-radius: 16px;
    margin: 10px 0;
    border: 1px solid #e5e7eb;
}

.badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 10px 0;
}

.badge {
    padding: 7px 10px;
    border-radius: 18px;
    font-size: 12px;
    font-weight: bold;
}

.badge.green {
    background: #dcfce7;
    color: #166534;
}

.badge.orange {
    background: #ffedd5;
    color: #9a3412;
}

.badge.blue {
    background: #dbeafe;
    color: #1e40af;
}

.badge.purple {
    background: #f3e8ff;
    color: #6b21a8;
}

.event-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
}

.mini-btn {
    border: none;
    border-radius: 10px;
    padding: 8px 11px;
    font-size: 13px;
    font-weight: bold;
    cursor: pointer;
}

.edit {
    background: #e8f0ff;
    color: #2563eb;
}

.delete {
    background: #fee2e2;
    color: #991b1b;
}

.empty-message {
    text-align: center;
    color: #666;
    padding: 30px;
    background: #f9fafb;
    border-radius: 18px;
    border: 1px dashed #ccc;
}

/* RESPONSIVE */

@media screen and (max-width: 1050px) {
    .content {
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

    .top-bar {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media screen and (max-width: 600px) {
    .main {
        padding: 22px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .event-card {
        grid-template-columns: 1fr;
    }

    .title h1 {
        font-size: 27px;
    }

    .btn {
        width: 100%;
        text-align: center;
    }
}
    </style>
</head>

<body>

    <div class="container">

        <aside class="sidebar">
            <div>
                <div class="logo-box">
                    <a href="accueil.php">
                        <img src="images/logo.jpeg" alt="Logo Off Campus">
                    </a>
                    <div class="logo-text">Off<span>Campus</span></div>
                </div>

                <nav class="menu">
                    <a href="accueil.php">Accueil</a>
                    <a href="activite.php">Événements / Activités</a>
                    <a href="dashboard-membre.php">Tableau de bord</a>
                    <a href="evenement-membre.php" class="active">Créer un événement</a>
                    <a href="#">Réservations</a>
                    <a href="#">Notifications</a>
                    <a href="a-propos.html">À propos</a>
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

        <main class="main">

            <section class="top-bar">
                <div class="title">
                    <h1>Gestion des événements</h1>
                    <p>
                        Créez, modifiez ou supprimez les événements proposés par votre association.
                    </p>
                </div>

                <a href="dashboard-membre.php" class="btn btn-blue">Retour au tableau de bord</a>
            </section>

            <section class="content">

                <div class="panel">
                    <h2 id="formTitle">Créer un événement</h2>

                    <form id="eventForm" enctype="multipart/form-data">
                        <div class="form-grid">

                            <div class="form-group">
                                <label for="eventName">Nom de l’événement</label>
                                <input type="text" id="eventName" name="name" placeholder="Ex : Tournoi Mario Kart" required>
                            </div>

                            <div class="form-group">
                                <label for="eventCategory">Catégorie</label>
                                <select id="eventCategory" name="category" required>
                                    <option value="">Choisir une catégorie</option>
                                    <option value="Soirées jeux">Soirées jeux</option>
                                    <option value="Tournois">Tournois</option>
                                    <option value="Ateliers">Ateliers</option>
                                    <option value="Culture">Culture</option>
                                    <option value="Sport">Sport</option>
                                    <option value="Récurrents">Récurrents</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="eventDate">Date</label>
                                <input type="date" id="eventDate" name="event_date" required>
                            </div>

                            <div class="form-group">
                                <label for="eventTime">Heure</label>
                                <input type="time" id="eventTime" name="event_time" required>
                            </div>

                            <div class="form-group">
                                <label for="eventPlace">Lieu</label>
                                <input type="text" id="eventPlace" name="place" placeholder="Ex : Salle B12" required>
                            </div>

                            <div class="form-group">
                                <label for="eventCapacity">Capacité maximale</label>
                                <input type="number" id="eventCapacity" name="capacity" min="1" placeholder="Ex : 40" required>
                            </div>

                            <div class="form-group">
                                <label for="eventStatus">Statut</label>
                                <select id="eventStatus" name="status" required>
                                    <option value="Ouvert">Ouvert</option>
                                    <option value="Complet">Complet</option>
                                    <option value="Publié">Publié</option>
                                    <option value="Brouillon">Brouillon</option>
                                    <option value="À valider">À valider</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="eventResource">Ressource nécessaire</label>
                                <select id="eventResource" name="resource">
                                    <option value="Aucune">Aucune</option>
                                    <option value="Salle">Salle</option>
                                    <option value="Matériel audio">Matériel audio</option>
                                    <option value="Jeux de société">Jeux de société</option>
                                    <option value="Console">Console</option>
                                </select>
                            </div>

                            <div class="form-group full">
                                <label for="eventImage">Image de l’événement</label>
                                <input type="file" id="eventImage" name="image" accept="image/*">
                                <p class="image-help">
                                    Choisissez une image depuis votre PC. Formats conseillés : JPG, PNG ou WEBP.
                                </p>
                                <img id="imagePreview" class="image-preview" alt="Aperçu de l’image">
                            </div>

                            <div class="form-group full">
                                <label for="eventDescription">Description</label>
                                <textarea id="eventDescription" name="description" placeholder="Présentez l’événement, les conditions de participation et les informations utiles..." required></textarea>
                            </div>

                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-green" id="submitBtn">Ajouter l’événement</button>
                            <button type="button" class="btn btn-orange" onclick="resetForm()">Réinitialiser</button>
                        </div>
                    </form>

                    <div class="success-box" id="successBox"></div>
                    <div class="error-box" id="errorBox"></div>

                    <div class="info-box">
                        Les événements sont enregistrés dans MySQL via PHP. 
                        L’image choisie sera enregistrée dans le dossier <strong>images/</strong>.
                    </div>
                </div>

                <div class="panel">
                    <h2>Événements de l’association</h2>
                    <div class="events-list" id="eventsList"></div>
                </div>

            </section>

        </main>

    </div>

    <script>
        let events = [];
        let editingId = null;

        const API_URL = "events_api.php";

        const form = document.getElementById("eventForm");
        const eventsList = document.getElementById("eventsList");
        const formTitle = document.getElementById("formTitle");
        const submitBtn = document.getElementById("submitBtn");
        const successBox = document.getElementById("successBox");
        const errorBox = document.getElementById("errorBox");
        const eventImage = document.getElementById("eventImage");
        const imagePreview = document.getElementById("imagePreview");

        function showSuccess(message) {
            successBox.textContent = message;
            successBox.style.display = "block";
            errorBox.style.display = "none";

            setTimeout(() => {
                successBox.style.display = "none";
            }, 2500);
        }

        function showError(message) {
            errorBox.textContent = message;
            errorBox.style.display = "block";
            successBox.style.display = "none";
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const months = ["JAN", "FÉV", "MAR", "AVR", "MAI", "JUIN", "JUIL", "AOÛT", "SEP", "OCT", "NOV", "DÉC"];

            return {
                day: String(date.getDate()).padStart(2, "0"),
                month: months[date.getMonth()]
            };
        }

        function getStatusClass(status) {
            if (status === "Ouvert" || status === "Publié") return "green";
            if (status === "À valider") return "orange";
            return "blue";
        }

        function getEventImage(event) {
            if (event.image && event.image.trim() !== "") {
                return "images/" + event.image;
            }

            return "images/Jeu de societe.jpg";
        }

        eventImage.addEventListener("change", function() {
            const file = this.files[0];

            if (!file) {
                imagePreview.style.display = "none";
                imagePreview.src = "";
                return;
            }

            imagePreview.src = URL.createObjectURL(file);
            imagePreview.style.display = "block";
        });

        async function loadEvents() {
            try {
                const response = await fetch(API_URL);
                const data = await response.json();

                if (data.success) {
                    events = data.events;
                    renderEvents();
                } else {
                    showError("Impossible de charger les événements.");
                }
            } catch (error) {
                showError("Erreur : vérifie que WampServer est lancé et que tu ouvres la page avec localhost.");
            }
        }

        function renderEvents() {
            eventsList.innerHTML = "";

            if (events.length === 0) {
                eventsList.innerHTML = `
                    <div class="empty-message">
                        Aucun événement pour le moment. Créez votre premier événement avec le formulaire.
                    </div>
                `;
                return;
            }

            events.forEach(event => {
                const date = formatDate(event.event_date);

                const card = document.createElement("article");
                card.className = "event-card";

                card.innerHTML = `
                    <div class="date-box">
                        <span>${date.month}</span>
                        <strong>${date.day}</strong>
                    </div>

                    <div class="event-content">
                        <h3>${event.name}</h3>

                        <img class="event-thumb" src="${getEventImage(event)}" alt="${event.name}">

                        <p><strong>${event.event_date}</strong> à ${event.event_time} — ${event.place}</p>
                        <p>${event.description || ""}</p>

                        <div class="badges">
                            <span class="badge purple">${event.category || "Sans catégorie"}</span>
                            <span class="badge ${getStatusClass(event.status)}">${event.status || "Ouvert"}</span>
                            <span class="badge blue">${event.registered || 0} / ${event.capacity || 0} inscrits</span>
                            <span class="badge orange">${event.resource || "Aucune ressource"}</span>
                        </div>

                        <div class="event-actions">
                            <button class="mini-btn edit" onclick="editEvent(${event.id})">Modifier</button>
                            <button class="mini-btn delete" onclick="deleteEvent(${event.id})">Supprimer</button>
                        </div>
                    </div>
                `;

                eventsList.appendChild(card);
            });
        }

        form.addEventListener("submit", async function(e) {
            e.preventDefault();

            const formData = new FormData();

            formData.append("id", editingId || "");
            formData.append("name", document.getElementById("eventName").value);
            formData.append("category", document.getElementById("eventCategory").value);
            formData.append("event_date", document.getElementById("eventDate").value);
            formData.append("event_time", document.getElementById("eventTime").value);
            formData.append("place", document.getElementById("eventPlace").value);
            formData.append("capacity", document.getElementById("eventCapacity").value);
            formData.append("status", document.getElementById("eventStatus").value);
            formData.append("resource", document.getElementById("eventResource").value);
            formData.append("description", document.getElementById("eventDescription").value);

            if (eventImage.files.length > 0) {
                formData.append("image", eventImage.files[0]);
            }

            if (editingId) {
                formData.append("_method", "PUT");
            } else {
                formData.append("_method", "POST");
            }

            try {
                const response = await fetch(API_URL, {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(editingId ? "Événement modifié avec succès." : "Événement ajouté avec succès.");
                    resetForm();
                    loadEvents();
                } else {
                    showError(data.message || "Une erreur est survenue.");
                }
            } catch (error) {
                showError("Erreur de connexion avec le serveur PHP.");
            }
        });

        function editEvent(id) {
            const event = events.find(event => Number(event.id) === Number(id));

            if (!event) {
                showError("Événement introuvable.");
                return;
            }

            document.getElementById("eventName").value = event.name || "";
            document.getElementById("eventCategory").value = event.category || "";
            document.getElementById("eventDate").value = event.event_date || "";
            document.getElementById("eventTime").value = event.event_time || "";
            document.getElementById("eventPlace").value = event.place || "";
            document.getElementById("eventCapacity").value = event.capacity || "";
            document.getElementById("eventStatus").value = event.status || "Ouvert";
            document.getElementById("eventResource").value = event.resource || "Aucune";
            document.getElementById("eventDescription").value = event.description || "";

            if (event.image && event.image.trim() !== "") {
                imagePreview.src = "images/" + event.image;
                imagePreview.style.display = "block";
            } else {
                imagePreview.style.display = "none";
                imagePreview.src = "";
            }

            eventImage.value = "";
            editingId = event.id;
            formTitle.textContent = "Modifier un événement";
            submitBtn.textContent = "Enregistrer les modifications";

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }

        async function deleteEvent(id) {
            const confirmation = confirm("Voulez-vous vraiment supprimer cet événement ?");

            if (!confirmation) {
                return;
            }

            try {
                const response = await fetch(`${API_URL}?id=${id}`, {
                    method: "DELETE"
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess("Événement supprimé avec succès.");
                    loadEvents();

                    if (Number(editingId) === Number(id)) {
                        resetForm();
                    }
                } else {
                    showError(data.message || "Impossible de supprimer cet événement.");
                }
            } catch (error) {
                showError("Erreur de connexion avec le serveur PHP.");
            }
        }

        function resetForm() {
            form.reset();
            editingId = null;
            formTitle.textContent = "Créer un événement";
            submitBtn.textContent = "Ajouter l’événement";
            imagePreview.style.display = "none";
            imagePreview.src = "";
        }

        loadEvents();
    </script>

</body>
</html>