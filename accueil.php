<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusPlay - Accueil</title>

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

        .page {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        header {
            text-align: center;
            margin-bottom: 25px;
        }

        header h1 {
            font-size: 36px;
            color: #2563eb;
        }

        header span {
            color: #16a34a;
        }

        nav {
            display: flex;
            justify-content: center;
            border: 1px solid #ddd;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 35px;
            background: #fff;
        }

        nav a {
            flex: 1;
            text-align: center;
            padding: 15px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            border-right: 1px solid #ddd;
        }

        nav a:last-child {
            border-right: none;
        }

        nav a:hover,
        nav a.active {
            background: #e8f0ff;
            color: #2563eb;
        }

        .cards {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.06);
        }

        .card h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #2563eb;
        }

        .card ul {
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .card li {
            margin-bottom: 8px;
            color: #555;
        }

        .card button {
            padding: 10px 16px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .card button:hover {
            background: #1d4ed8;
        }

        .main-section {
            border: 1px solid #ddd;
            border-radius: 22px;
            padding: 25px;
            background: #fafafa;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-header h2 {
            font-size: 28px;
        }

        .section-header button {
            padding: 11px 18px;
            border: none;
            border-radius: 12px;
            background: #16a34a;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .events {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .event {
            background: white;
            padding: 18px;
            border-radius: 16px;
            border: 1px solid #ddd;
        }

        .event h3 {
            color: #2563eb;
            margin-bottom: 10px;
        }

        .event p {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        .places {
            margin-top: 12px;
            font-weight: bold;
            color: #16a34a;
        }

        @media screen and (max-width: 900px) {
            .cards {
                grid-template-columns: 1fr;
            }

            .events {
                grid-template-columns: 1fr 1fr;
            }

            nav {
                flex-wrap: wrap;
            }

            nav a {
                flex: 50%;
                border-bottom: 1px solid #ddd;
            }
        }

        @media screen and (max-width: 600px) {
            .page {
                width: 95%;
                padding: 20px;
            }

            header h1 {
                font-size: 28px;
            }

            .events {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="page">

        <header>
            <h1>Campus<span>Play</span></h1>
        </header>

        <nav>
            <a href="accueil.php" class="active">Accueil</a>
            <a href="activite.php">Événements / Activités</a>
            <a href="#">Jeux</a>
            <a href="#">Réservations</a>
            <a href="#">Communauté</a>
            <a href="#">Compte</a>
        </nav>

        <section class="cards">

            <div class="card">
                <h2>Notifications</h2>
                <ul>
                    <li>Rappel : soirée jeux demain</li>
                    <li>Réservation confirmée</li>
                    <li>Nouvel événement disponible</li>
                </ul>
                <button>Voir tout</button>
            </div>

            <div class="card">
                <h2>S'inscrire à un événement</h2>
                <ul>
                    <li>Voir les événements disponibles</li>
                    <li>Consulter le nombre de places</li>
                    <li>Rejoindre une liste d'attente</li>
                </ul>
                <button>S'inscrire</button>
            </div>

            <div class="card">
                <h2>Contacts</h2>
                <ul>
                    <li>Bureau de l'association</li>
                    <li>Responsables des activités</li>
                    <li>Support CampusPlay</li>
                </ul>
                <button>Voir tout</button>
            </div>

        </section>

        <section class="main-section">

            <div class="section-header">
                <h2>Événements à venir</h2>
                <button>Voir le calendrier</button>
            </div>

            <div class="events">

                <div class="event">
                    <h3>Soirée jeux</h3>
                    <p>Mercredi 29 mai</p>
                    <p>Salle B12</p>
                    <p>Découverte de jeux de société.</p>
                    <div class="places">3/10 places</div>
                </div>

                <div class="event">
                    <h3>Tournoi Mario Kart</h3>
                    <p>Vendredi 31 mai</p>
                    <p>Espace détente</p>
                    <p>Tournoi entre étudiants.</p>
                    <div class="places">7/10 places</div>
                </div>

                <div class="event">
                    <h3>Atelier peinture</h3>
                    <p>Lundi 3 juin</p>
                    <p>Salle créativité</p>
                    <p>Activité artistique encadrée.</p>
                    <div class="places">5/12 places</div>
                </div>

                <div class="event">
                    <h3>Festival culturel</h3>
                    <p>Jeudi 6 juin</p>
                    <p>Hall principal</p>
                    <p>Animations et stands étudiants.</p>
                    <div class="places">20/50 places</div>
                </div>

            </div>

        </section>

    </div>

</body>
</html>