<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = $_POST["role"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["surname"] = $user["surname"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["association"] = $user["association"];
		$_SESSION["phone"] = $user["phone"];
		$_SESSION["gender"] = $user["gender"];

        if ($user["role"] === "membre") {
            header("Location: dashboard-membre.php");
        } else {
            header("Location: activite.php");
        }
        exit;
    } else {
        $message = "Le mot de passe ou l’identifiant est incorrect. Réessayez.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OffCampus - Connexion</title>

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
    min-height: 100vh;
}

.page {
    min-height: 100vh;
    display: flex;
}


/*  ======== PARTIE GAUCHE (LEFT PANEL)  ========== */

.left-panel {
    width: 45%;
    background: linear-gradient(135deg, #2563eb, #16a34a);
    color: white;
    padding: 45px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.left-panel::before {
    content: "";
    position: absolute;
    width: 350px;
    height: 350px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    top: -100px;
    right: -120px;
}

.left-panel::after {
    content: "";
    position: absolute;
    width: 250px;
    height: 250px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.10);
    bottom: -80px;
    left: -70px;
}

.brand {
    position: relative;
    z-index: 2;
}

.brand img {
    width: 170px;
    background: white;
    border-radius: 22px;
    padding: 12px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.12);
    margin: 0 auto 30px auto;
    display: block;
}

.brand h1 {
    font-size: 42px;
    margin-bottom: 15px;
}

.brand h1 span {
    color: #facc15;
}

.brand p {
    font-size: 18px;
    line-height: 1.6;
    max-width: 470px;
    opacity: 0.95;
}

.features {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-top: 35px;
}

.feature {
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 18px;
    padding: 16px;
    backdrop-filter: blur(4px);
}

.feature strong {
    display: block;
    margin-bottom: 6px;
    font-size: 15px;
}

.feature p {
    font-size: 13px;
    line-height: 1.4;
    opacity: 0.9;
}

.footer-left {
    position: relative;
    z-index: 2;
    font-size: 14px;
    opacity: 0.9;
}


/*  ======== PARTIE DROITE (RIGHT PANEL)  ========== */

.right-panel {
    width: 55%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
}

.login-card {
    width: 100%;
    max-width: 470px;
    background: white;
    border-radius: 26px;
    padding: 34px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
}


/*  ======== TOP LINKS  ========== */

.top-links {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    margin-bottom: 25px;
}

.top-links a {
    text-decoration: none;
    color: #555;
    font-weight: bold;
    font-size: 14px;
}

.top-links a:hover {
    color: #2563eb;
}


/*  ======== TITRES & SOUS-TITRES  ========== */

.login-card h2 {
    font-size: 32px;
    color: #111827;
    margin-bottom: 8px;
}

.subtitle {
    color: #666;
    margin-bottom: 28px;
    line-height: 1.5;
}


/*  ======== ROLE SELECTION  ========== */

.role-selection {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 24px;
}

.role {
    border: 1px solid #ddd;
    background: #f9fafb;
    border-radius: 16px;
    padding: 14px 10px;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    color: #444;
    transition: 0.2s ease;
}

.role:hover {
    transform: translateY(-3px);
}

.role.active {
    background: #e8f0ff;
    color: #2563eb;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.role.member.active {
    background: #f1e8ff;
    color: #7c3aed;
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
}

.role.student.active {
    background: #e8f0ff;
    color: #2563eb;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}


/*  ======== FORMULAIRE  ========== */

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: bold;
    color: #374151;
}

.form-group input {
    width: 100%;
    padding: 14px 16px;
    border-radius: 14px;
    border: 1px solid #d1d5db;
    font-size: 15px;
    outline: none;
    background: #fff;
}

.form-group input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}


/*  ======== OPTIONS (Remember + Forgot)  ========== */

.options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 10px 0 24px 0;
    font-size: 14px;
    gap: 15px;
}

.remember {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #555;
}

.options a {
    text-decoration: none;
    color: #2563eb;
    font-weight: bold;
}


/*  ======== BOUTON LOGIN  ========== */

.btn-login {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 16px;
    background: #2563eb;
    color: white;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.btn-login:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}


/*  ======== SEPARATOR  ========== */

.separator {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 25px 0;
    color: #999;
    font-size: 14px;
}

.separator::before,
.separator::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #e5e7eb;
}


/*  ======== QUICK ACCESS  ========== */

.quick-access {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.quick-access a {
    text-align: center;
    text-decoration: none;
    padding: 12px;
    border-radius: 14px;
    font-weight: bold;
    font-size: 14px;
    border: 1px solid #ddd;
    color: #374151;
    background: #f9fafb;
}

.quick-access a:hover {
    background: #ecfdf5;
    border-color: #16a34a;
    color: #16a34a;
}


/*  ======== SIGNUP & SECURITY NOTE  ========== */

.signup {
    text-align: center;
    margin-top: 24px;
    color: #666;
    font-size: 14px;
}

.signup a {
    color: #f97316;
    text-decoration: none;
    font-weight: bold;
}

.security-note {
    margin-top: 20px;
    padding: 13px;
    border-radius: 14px;
    background: #fff7ed;
    color: #9a3412;
    font-size: 13px;
    line-height: 1.5;
    border: 1px solid #fed7aa;
}


/*  ======== MESSAGES (SUCCESS / ERROR)  ========== */

.message {
    display: none;
    margin-bottom: 18px;
    padding: 12px 14px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: bold;
}

.message.error {
    display: block;
    background: #fff1f2;
    color: #be123c;
    border: 1px solid #fecdd3;
}

.message.success {
    display: block;
    background: #ecfdf5;
    color: #15803d;
    border: 1px solid #bbf7d0;
}


/*  ======== MEMBER FIELDS  ========== */

.member-fields {
    display: none;
}

.member-fields.active {
    display: block;
}


/*  ======== FORGOT PASSWORD BOX  ========== */

.forgot-box {
    display: none;
    margin-top: 20px;
    padding: 18px;
    border-radius: 18px;
    background: #f7f8fc;
    border: 1px solid #e5e7eb;
}

.forgot-box h3 {
    font-size: 20px;
    margin-bottom: 8px;
}

.forgot-box p {
    color: #666;
    font-size: 14px;
    margin-bottom: 14px;
    line-height: 1.5;
}

.forgot-box input {
    width: 100%;
    padding: 13px 15px;
    border-radius: 14px;
    border: 1px solid #d1d5db;
    margin-bottom: 12px;
}

.forgot-box button {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 14px;
    background: #2563eb;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

#recoveryMessage {
    margin-top: 12px;
    font-weight: bold;
    color: #16a34a;
}


/*  ======== RESPONSIVE  ========== */

@media screen and (max-width: 950px) {
    .page {
        flex-direction: column;
    }

    .left-panel,
    .right-panel {
        width: 100%;
    }

    .left-panel {
        padding: 30px;
    }

    .features {
        grid-template-columns: 1fr;
    }

    .brand h1 {
        font-size: 34px;
    }
}

@media screen and (max-width: 600px) {
    .right-panel {
        padding: 20px;
    }

    .login-card {
        padding: 24px;
    }

    .role-selection {
        grid-template-columns: 1fr;
    }

    .quick-access {
        grid-template-columns: 1fr;
    }

    .options {
        flex-direction: column;
        align-items: flex-start;
    }

    .brand img {
        width: 150px;
    }
}

		
    </style>
</head>


<body>

    <div class="page">

        <section class="left-panel">
            <div class="brand">
                <img src="images/logo.jpeg" alt="Logo OffCampus">

                <h1>Bienvenue sur <span>OffCampus</span></h1>

                <p>
                    La plateforme qui rassemble les associations, les événements,
                    les activités et les ressources du campus dans un seul espace simple,
                    clair et dynamique.
                </p>

                <div class="features">
                    <div class="feature">
                        <strong>Événements</strong>
                        <p>Inscription aux soirées, tournois, ateliers et activités étudiantes.</p>
                    </div>

                    <div class="feature">
                        <strong>Réservations</strong>
                        <p>Gestion des salles, jeux et matériels disponibles sur le campus.</p>
                    </div>

                    <div class="feature">
                        <strong>Membres</strong>
                        <p>Création, modification et validation des événements associatifs.</p>
                    </div>

                    <div class="feature">
                        <strong>Notifications</strong>
                        <p>Suivi des inscriptions, rappels et demandes en attente.</p>
                    </div>
                </div>
            </div>

            <p class="footer-left">
                OffCampus — Les associations, l’université autrement.
            </p>
        </section>

        <section class="right-panel">

            <div class="login-card">

                <div class="top-links">
                    <a href="accueil.php">Accueil</a>
                    <a href="activite.php">Activités</a>
                    <a href="a-propos.php">À propos</a>
                </div>

                <h2>Connexion</h2>
                <p class="subtitle">
                    Connectez-vous pour accéder à votre espace personnel selon votre rôle sur la plateforme.
                </p>

                <div class="role-selection">
					<button type="button"
							class="role student active"
							data-role="etudiant">
						Étudiant
					</button>

					<button type="button"
							class="role member"
							data-role="membre">
						Membre
					</button>
				</div>
				
				<div id="loginMessage" class="message"></div>
				
				<?php if (!empty($message)) : ?>
					<div class="message error">
						<?php echo htmlspecialchars($message); ?>
					</div>
				<?php endif; ?>

                <form method="POST">
					<input type="hidden" id="roleInput" name="role" value="etudiant">
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" placeholder="prenom.nom@campus.fr">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="Votre mot de passe">
                    </div>
					
					<div id="memberFields" class="member-fields">
						<div class="form-group">
							<label for="accessKey">Clé d'accès membre</label>
							<input 
								type="password" 
								id="accessKey" 
								name="accessKey"
								placeholder="Clé fournie par l'association">
						</div>
					</div>

                    <div class="options">
                        <label class="remember">
                            <input type="checkbox">
                            Se souvenir de moi
                        </label>

                        <a href="#" onclick="showForgotPassword(); return false;">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn-login">
						Se connecter
					</button>
                </form>
				
				<div id="forgotBox" class="forgot-box">
					<h3>Récupération du mot de passe</h3>
					<p>Entrez votre adresse email de récupération. Un lien de réinitialisation vous sera envoyé.</p>

					<input type="email" id="recoveryEmail" placeholder="prenom.nom@campus.fr">

					<button type="button" onclick="sendRecoveryEmail()">Envoyer le lien</button>

					<p id="recoveryMessage"></p>
				</div>

                <div class="separator">ou accès rapide</div>

                <div class="quick-access">
                    <a href="accueil.php">Voir l’accueil</a>
                    <a href="activite.php">Voir les activités</a>
                </div>

                <p class="signup">
                    Pas encore inscrit ?
                    <a href="inscription.php">Créer un compte</a>
                </p>

               

            </div>

        </section>

    </div>

	<script>
		let selectedRole = "etudiant";

		const roles = document.querySelectorAll(".role");
		const memberFields = document.getElementById("memberFields");

		roles.forEach(role => {

			role.addEventListener("click", () => {

				roles.forEach(btn => {
					btn.classList.remove("active");
				});

				role.classList.add("active");

				selectedRole = role.dataset.role;
				
				document.getElementById("roleInput").value = selectedRole;

				if (selectedRole === "membre") {
					memberFields.classList.add("active");
				} else {
					memberFields.classList.remove("active");
				}
			});

		});

		function loginUser() {
			const email = document.getElementById("email").value.trim();
			const password = document.getElementById("password").value.trim();
			const accessKey = document.getElementById("accessKey").value.trim();
			const message = document.getElementById("loginMessage");

			message.className = "message";
			message.textContent = "";

			if (email === "" || password === "") {
				message.classList.add("error");
				message.textContent = "Veuillez remplir votre email et votre mot de passe.";
				return;
			}

			if (!email.includes("@")) {
				message.classList.add("error");
				message.textContent = "Veuillez entrer une adresse email valide.";
				return;
			}

			if (selectedRole === "membre") {
				if (accessKey === "") {
					message.classList.add("error");
					message.textContent = "Veuillez renseigner votre clé d'accès membre.";
					return;
				}

				if (accessKey !== "OFFCAMPUS2026") {
					message.classList.add("error");
					message.textContent = "Clé d'accès membre incorrecte.";
					return;
				}
			}

			message.classList.add("success");
			message.textContent = "Connexion réussie. Redirection en cours...";

			setTimeout(() => {
				if (selectedRole === "membre") {
					window.location.href = "dashboard-membre.php";
				} else {
					window.location.href = "activite.php";
				}
			}, 700);
		}
		
		function showForgotPassword() {
			document.getElementById("forgotBox").style.display = "block";
		}

		function sendRecoveryEmail() {
			const email = document.getElementById("recoveryEmail").value.trim();
			const message = document.getElementById("recoveryMessage");

			if (email === "" || !email.includes("@")) {
				message.style.color = "#be123c";
				message.textContent = "Veuillez entrer une adresse email valide.";
				return;
			}

			message.style.color = "#16a34a";
			message.textContent = "Si un compte existe avec cette adresse, un email de récupération sera envoyé.";
		}
	</script>
</body>
</html>