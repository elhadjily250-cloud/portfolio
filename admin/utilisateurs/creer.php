<?php
require '../../fonctions.php';
require '../../config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

verifier_admin();

$erreurs      = [];
$prenom       = '';
$nom          = '';
$email        = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_jeton_csrf();

    $prenom       = nettoyer($_POST['prenom']       ?? '');
    $nom          = nettoyer($_POST['nom']          ?? '');
    $email        = nettoyer($_POST['email']        ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe']     ?? '');
    $confirmation = trim($_POST['confirmation']     ?? '');

    if (!champ_requis($prenom))        $erreurs['prenom']       = 'Le prénom est obligatoire.';
    if (!champ_requis($nom))           $erreurs['nom']          = 'Le nom est obligatoire.';
    if (!email_valide($email))         $erreurs['email']        = 'L\'adresse e-mail est invalide.';
    if (strlen($mot_de_passe) < 8)     $erreurs['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caractères.';
    if ($mot_de_passe !== $confirmation) $erreurs['confirmation'] = 'Les mots de passe ne correspondent pas.';

    if (empty($erreurs)) {
        $stmt = $pdo->prepare('SELECT id FROM administrateurs WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreurs['email'] = 'Cette adresse e-mail est déjà utilisée.';
        }
    }

    if (empty($erreurs)) {
        $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare(
            'INSERT INTO administrateurs (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$prenom, $nom, $email, $hash]);
        header('Location: index.php?succes=' . urlencode('Administrateur ajouté avec succès.'));
        exit;
    }
}

$jeton_csrf = generer_jeton_csrf();
$titre_page = 'Ajouter un administrateur';
require '../composant-nav.php';
?>

  <div class="admin-entete">
    <h1>Ajouter un administrateur</h1>
    <a href="index.php" class="btn btn--outline">← Retour</a>
  </div>

  <?php if (!empty($erreurs)) : ?>
    <div class="alerte alerte--erreur">Corrige les erreurs ci-dessous.</div>
  <?php endif; ?>

  <div style="max-width:520px; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); padding:var(--sp-md);">
    <form method="POST" action="creer.php" novalidate>
      <input type="hidden" name="jeton_csrf" value="<?= htmlspecialchars($jeton_csrf) ?>">

      <div class="form-group">
        <label for="prenom">Prénom <abbr title="obligatoire">*</abbr></label>
        <input type="text" id="prenom" name="prenom"
          value="<?= htmlspecialchars($prenom) ?>"
          class="<?= isset($erreurs['prenom']) ? 'erreur' : '' ?>">
        <?php if (isset($erreurs['prenom'])) : ?>
          <span class="champ-erreur"><?= htmlspecialchars($erreurs['prenom']) ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="nom">Nom <abbr title="obligatoire">*</abbr></label>
        <input type="text" id="nom" name="nom"
          value="<?= htmlspecialchars($nom) ?>"
          class="<?= isset($erreurs['nom']) ? 'erreur' : '' ?>">
        <?php if (isset($erreurs['nom'])) : ?>
          <span class="champ-erreur"><?= htmlspecialchars($erreurs['nom']) ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="email">Adresse e-mail <abbr title="obligatoire">*</abbr></label>
        <input type="email" id="email" name="email"
          value="<?= htmlspecialchars($email) ?>"
          class="<?= isset($erreurs['email']) ? 'erreur' : '' ?>">
        <?php if (isset($erreurs['email'])) : ?>
          <span class="champ-erreur"><?= htmlspecialchars($erreurs['email']) ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="mot_de_passe">Mot de passe <abbr title="obligatoire">*</abbr></label>
        <input type="password" id="mot_de_passe" name="mot_de_passe"
          placeholder="Minimum 8 caractères"
          class="<?= isset($erreurs['mot_de_passe']) ? 'erreur' : '' ?>">
        <?php if (isset($erreurs['mot_de_passe'])) : ?>
          <span class="champ-erreur"><?= htmlspecialchars($erreurs['mot_de_passe']) ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="confirmation">Confirmer le mot de passe <abbr title="obligatoire">*</abbr></label>
        <input type="password" id="confirmation" name="confirmation"
          placeholder="Répète le mot de passe"
          class="<?= isset($erreurs['confirmation']) ? 'erreur' : '' ?>">
        <?php if (isset($erreurs['confirmation'])) : ?>
          <span class="champ-erreur"><?= htmlspecialchars($erreurs['confirmation']) ?></span>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn--primary">Créer l'administrateur</button>
    </form>
  </div>

</main>
</body>
</html>