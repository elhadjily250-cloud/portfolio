<?php
require '../fonctions.php';
require '../config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erreur = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_jeton_csrf();

    $email      = nettoyer($_POST['email']      ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

    if (!champ_requis($email) || !champ_requis($mot_de_passe)) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM administrateurs WHERE email = ?');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']     = $admin['id'];
            $_SESSION['admin_prenom'] = $admin['prenom'];
            $_SESSION['admin_nom']    = $admin['nom'];
            header('Location: dashboard.php');
            exit;
        } else {
            $erreur = 'Email ou mot de passe incorrect.';
        }
    }
}

$jeton_csrf = generer_jeton_csrf();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion — Administration</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .login-card { width: 100%; max-width: 420px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: var(--sp-md); }
    .login-titre { font-family: var(--font-display); font-size: 1.8rem; margin-bottom: 0.25rem; }
    .login-sous-titre { color: var(--muted); font-size: 0.9rem; margin-bottom: var(--sp-md); }
    .alerte-erreur { background: #fdf3f2; border: 1px solid #f0b8b8; color: #c0392b; border-radius: var(--radius); padding: 0.85rem 1rem; font-size: 0.88rem; margin-bottom: var(--sp-sm); }
    .form-group input.erreur { border-color: #e74c3c; }
  </style>
</head>
<body>

  <div class="login-card">
    <p class="login-titre">[El<span style="color:var(--accent)">.</span>LY]</p>
    <p class="login-sous-titre">Espace d'administration — connexion requise</p>

    <?php if ($erreur !== '') : ?>
      <div class="alerte-erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" action="connexion.php" novalidate>
      <input type="hidden" name="jeton_csrf" value="<?= htmlspecialchars($jeton_csrf) ?>">

      <div class="form-group">
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email"
          value="<?= htmlspecialchars($email) ?>"
          placeholder="admin@exemple.com"
          autocomplete="email" autofocus>
      </div>

      <div class="form-group">
        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe"
          placeholder="••••••••"
          autocomplete="current-password">
      </div>

      <button type="submit" class="btn btn--primary" style="width:100%; margin-top:0.5rem;">
        Se connecter →
      </button>
    </form>

    <p style="margin-top:var(--sp-sm); text-align:center;">
      <a href="../index.php" style="font-size:0.82rem; color:var(--muted);">← Retour au site</a>
    </p>
  </div>

</body>
</html>