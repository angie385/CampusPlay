<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $surname = trim($_POST["surname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $role = $_POST["role"];
    $association = $_POST["association"] ?? null;
    $accessKey = $_POST["accessKey"] ?? null;

    if ($password !== $confirmPassword) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif ($role === "membre" && $accessKey !== "OFFCAMPUS2026") {
        $message = "Clé membre incorrecte.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (name, surname, email, password, role, association)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([$name, $surname, $email, $hash, $role, $association]);

        header("Location: connexion.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>OffCampus - Inscription</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f4f6fb;
    min-height:100vh;
    color:#222;
}

.page{
    min-height:100vh;
    display:flex;
}

/* LEFT PANEL */

.left-panel{
    width:45%;
    background:linear-gradient(135deg,#2563eb,#16a34a);
    color:white;
    padding:45px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    position:relative;
    overflow:hidden;
}

.left-panel::before{
    content:"";
    position:absolute;
    width:350px;
    height:350px;
    border-radius:50%;
    background:rgba(255,255,255,.12);
    top:-100px;
    right:-120px;
}

.left-panel::after{
    content:"";
    position:absolute;
    width:250px;
    height:250px;
    border-radius:50%;
    background:rgba(255,255,255,.1);
    bottom:-80px;
    left:-70px;
}

.brand{
    position:relative;
    z-index:2;
}

.brand img{
    width:170px;
    background:white;
    border-radius:22px;
    padding:12px;
    box-shadow:0 8px 22px rgba(0,0,0,.12);
    margin:0 auto 30px auto;
    display:block;
}

.brand h1{
    font-size:42px;
    margin-bottom:15px;
}

.brand h1 span{
    color:#facc15;
}

.brand p{
    font-size:18px;
    line-height:1.6;
    max-width:470px;
    opacity:.95;
}

.features{
    position:relative;
    z-index:2;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
    margin-top:35px;
}

.feature{
    background:rgba(255,255,255,.16);
    border:1px solid rgba(255,255,255,.25);
    border-radius:18px;
    padding:16px;
    backdrop-filter:blur(4px);
}

.feature strong{
    display:block;
    margin-bottom:6px;
    font-size:15px;
}

.feature p{
    font-size:13px;
    line-height:1.4;
    opacity:.9;
}

.footer-left{
    position:relative;
    z-index:2;
    font-size:14px;
    opacity:.9;
}

/* RIGHT */

.right-panel{
    width:55%;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px;
}

.register-card{
    width:100%;
    max-width:520px;
    background:white;
    border-radius:26px;
    padding:34px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    border:1px solid #e5e7eb;
}

.top-links{
    display:flex;
    justify-content:flex-end;
    gap:16px;
    margin-bottom:25px;
}

.top-links a{
    text-decoration:none;
    color:#555;
    font-weight:bold;
    font-size:14px;
}

.top-links a:hover{
    color:#2563eb;
}

.register-card h2{
    font-size:32px;
    margin-bottom:8px;
    color:#111827;
}

.subtitle{
    color:#666;
    margin-bottom:28px;
    line-height:1.5;
}

/* ROLES */

.role-selection{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
    margin-bottom:24px;
}

.role{
    border:1px solid #ddd;
    background:#f9fafb;
    border-radius:16px;
    padding:14px 10px;
    text-align:center;
    font-size:14px;
    font-weight:bold;
    cursor:pointer;
    color:#444;
    transition:.2s;
}

.role:hover{
    transform:translateY(-3px);
}

.role.active{
    background:#e8f0ff;
    color:#2563eb;
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.12);
}

/* FORM */

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:bold;
    color:#374151;
}

.form-group input,
.form-group select{
    width:100%;
    padding:14px 16px;
    border-radius:14px;
    border:1px solid #d1d5db;
    font-size:15px;
    outline:none;
}

.form-group input:focus,
.form-group select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.12);
}

.member-fields{
    display:none;
}

.member-fields.active{
    display:block;
}

/* BUTTON */

.btn-register{
    width:100%;
    padding:15px;
    border:none;
    border-radius:16px;
    background:#2563eb;
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
    margin-top:8px;
}

.btn-register:hover{
    background:#1d4ed8;
    transform:translateY(-1px);
}

/* MESSAGE */

.message{
    display:none;
    margin-bottom:18px;
    padding:12px 14px;
    border-radius:14px;
    font-size:14px;
    font-weight:bold;
}

.message.error{
    display:block;
    background:#fff1f2;
    color:#be123c;
    border:1px solid #fecdd3;
}

.message.success{
    display:block;
    background:#ecfdf5;
    color:#15803d;
    border:1px solid #bbf7d0;
}

.login-link{
    text-align:center;
    margin-top:24px;
    color:#666;
    font-size:14px;
}

.login-link a{
    color:#2563eb;
    text-decoration:none;
    font-weight:bold;
}

/* RESPONSIVE */

@media screen and (max-width:950px){

    .page{
        flex-direction:column;
    }

    .left-panel,
    .right-panel{
        width:100%;
    }

    .features{
        grid-template-columns:1fr;
    }
}

@media screen and (max-width:600px){

    .right-panel{
        padding:20px;
    }

    .register-card{
        padding:24px;
    }

    .role-selection{
        grid-template-columns:1fr;
    }

}

</style>
</head>

<body>

<div class="page">

    <section class="left-panel">

        <div class="brand">

            <a href = "accueil.html"> <img src="images/logo.jpeg" alt="Logo Off Campus"></a>

            <h1>Rejoignez <span>OffCampus</span></h1>

            <p>
                Créez votre compte pour accéder aux événements,
                réservations et activités de votre campus.
            </p>

            <div class="features">

                <div class="feature">
                    <strong>Événements</strong>
                    <p>Découvrez toutes les activités étudiantes du campus.</p>
                </div>

                <div class="feature">
                    <strong>Associations</strong>
                    <p>Rejoignez et gérez vos associations étudiantes.</p>
                </div>

                <div class="feature">
                    <strong>Réservations</strong>
                    <p>Réservez des salles et du matériel rapidement.</p>
                </div>

                <div class="feature">
                    <strong>Communauté</strong>
                    <p>Restez connecté avec la vie du campus.</p>
                </div>

            </div>

        </div>

        <p class="footer-left">
            OffCampus — Les associations, l’université autrement.
        </p>

    </section>

    <section class="right-panel">

        <div class="register-card">

            <div class="top-links">
                <a href="accueil.php">Accueil</a>
                <a href="activite.php">Activités</a>
                <a href="connexion.php">Connexion</a>
            </div>

            <h2>Créer un compte</h2>

            <p class="subtitle">
                Inscrivez-vous pour accéder à votre espace OffCampus.
            </p>

            <div class="role-selection">

                <button type="button"
                        class="role active"
                        data-role="etudiant">
                    Étudiant
                </button>

                <button type="button"
                        class="role"
                        data-role="membre">
                    Membre
                </button>

            </div>

            <div id="registerMessage" class="message"></div>

            <form method="POST">

                <div class="form-group">
                    <label>Nom</label>
                    <input type="text"
					   id="name"
					   name="name"
					   placeholder="NOM">
                </div>
				
				<div class="form-group">
					<label>Prénom</label>
					<input type="text"
					   id="surname"
					   name="surname"
					   placeholder="Prénom">
				</div>

                <div class="form-group">
                    <label>Adresse email</label>
                    <input type="email"
					   id="email"
					   name="email"
					   placeholder="prenom.nom@campus.fr">
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password"
					   id="password"
					   name="password"
					   placeholder="Votre mot de passe">	
                </div>

                <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <input type="password"
					   id="confirmPassword"
					   name="confirmPassword"
					   placeholder="Confirmer le mot de passe">
                </div>

                <div id="memberFields" class="member-fields">

                    <div class="form-group">
                        <label>Association</label>

                        <select id="association" name="association">
                            <option value="">Choisir une association</option>
                            <option>BDE</option>
                            <option>BDS</option>
                            <option>Club Jeux</option>
                            <option>Association Art</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Clé d'accès membre</label>
                        <input type="password"
						   id="accessKey"
						   name="accessKey">
                    </div>

                </div>
				
				<input type="hidden"
				   id="roleInput"
				   name="role"
				   value="etudiant">

                <button type="submit"
					class="btn-register">
                    Créer mon compte
                </button>

            </form>

            <p class="login-link">
                Déjà inscrit ?
                <a href="connexion.php">Se connecter</a>
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

        if(selectedRole === "membre"){
            memberFields.classList.add("active");
        }
        else{
            memberFields.classList.remove("active");
        }

    });

});

</script>

</body>
</html>