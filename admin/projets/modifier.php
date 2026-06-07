<?php
require '../../fonctions.php';
require '../../config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

verifier_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM projets WHERE id = ?');
$stmt->execute([$id]);
$projet = $stmt->fetch();

if (!$projet) {
    header('Location: index.php');
    exit;
}

$erreurs      = [];
$titre        = $projet['titre'];
$description  = $projet['description'];
$technologies = $projet['technologies'];
$lien         = $projet['lien'] ?? '';
$image        = $projet['image'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_jeton_csrf();

    $titre        = nettoyer($_POST['titre']        ?? '');
    $description  = nettoyer($_POST['description']  ?? '');
    $technologies = nettoyer($_POST['technologies'] ?? '');
    $lien         = nettoyer($_POST['lien']         ?? '');

    if (!champ_requis($titre))        $erreurs['titre']        = 'Le titre est obligatoire.';
    if (!champ_requis($description))  $erreurs['description']  = 'La description est obligatoire.';
    if (!champ_requis($technologies)) $erreurs['technologies'] = 'Les technologies sont obligatoires.';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext_autorisees = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext            = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $ext_autorisees)) {
            $erreurs['image'] = 'Format non autorisé. Utilise jpg, png ou webp.';
        } else {
            $nom_fichier = uniqid('projet_') . '.' . $ext;
            $dossier     = '../../images/projets/';

            if (!is_dir($dossier)) {
                mkdir($dossier, 0755, true);
            }

            move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $nom_fichier);
            $image = $nom_fichier;
        }
    }

    if (empty($erreurs)) {
        $stmt = $pdo->prepare(
            'UPDATE projets SET titre = ?, description = ?, technologies = ?, image = ?, lien = ?
             WHERE id = ?'
        );
        $stmt->execute([$titre, $description, $technologies, $image, $lien, $id]);
        header('Location: index.php?succes=' . urlencode('Projet modifié avec succès.'));
        exit;
    }
}

$jeton_csrf = generer_jeton_csrf();
$titre_page = 'Modifier un projet';
require '../composant-nav.php';
?>

  <div class="admin-entete">
    <h1>Modifier : <?= htmlspecialchars($projet['titre']) ?></h1>
    <a href="index.php" class="btn btn--outline">← Retour</a>
  </div>

  <?php if (!empty($erreurs)) : ?>
    <div class="alerte alerte--erreur">Corrige les erreurs ci-dessous.</div>
  <?php endif; ?>

  <div style="max-width:680px; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); padding:var(--sp-md);">
    <form method="POST" action="modifier.php?id=<?= $id ?>" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="jeton_csrf" value="<?= htmlspecialchars($jeton_csrf) ?>">

      <div class="form-group">
        <label for="titre">Titre <abbr title="obligatoire">*</abbr></label>
        <input type="text" id="titre" name="titre"
          value="<?= htmlspecialchars($titre) ?>"
          class="<?= isset($erreurs['titre']) ? 'erreur' : '' ?>">
        <?php if (isset($erreurs['titre'])) : ?>
          <span class="champ-erreur"><?= htmlspecialchars($erreurs['titre']) ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="description">Description <abbr title="obligatoire">*</abbr></label>
        <textarea id="description" name="description"
          class="<?= isset($erreurs['description']) ? 'erreur' : '' ?>"
        ><?= htmlspecialchars($description) ?></textarea>
        <?php if (isset($erreurs['description'])) : ?>
          <span class="champ-erreur"><?= htmlspecialchars($erreurs['description']) ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="technologies">Technologies <abbr title="obligatoire">*</abbr></label>
        <input type="text" id="technologies" name="technologies"
          value="<?= htmlspecialchars($technologies) ?>"
          class="<?= isset($erreurs['technologies']) ? 'erreur' : '' ?>">
        <small style="color:var(--muted); font-size:0.78rem;">Sépare les technologies par des virgules.</small>
        <?php if (isset($erreurs['technologies'])) : ?>
          <span class="champ-erreur"><?= htmlspecialchars($erreurs['technologies']) ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="lien">Lien du projet</label>
        <input type="url" id="lien" name="lien" value="<?= htmlspecialchars($lien) ?>" placeholder="https://...">
      </div>

      <div class="form-group">
        <label for="image">Changer l'image</label>
        <?php if (!empty($image)) : ?>
          <p style="font-size:0.82rem; color:var(--muted); margin-bottom:0.4rem;">
            Image actuelle : <strong><?= htmlspecialchars($image) ?></strong>
          </p>
        <?php endif; ?>
        <input type="file" id="image" name="image" accept="image/*"
          class="<?= isset($erreurs['image']) ? 'erreur' : '' ?>">
        <?php if (isset($erreurs['image'])) : ?>
          <span class="champ-erreur"><?= htmlspecialchars($erreurs['image']) ?></span>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn--primary">Enregistrer les modifications</button>
    </form>
  </div>

</main>
</body>
</html>