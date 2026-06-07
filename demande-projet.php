<?php
require 'fonctions.php';
require 'config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

enregistrer_visite($pdo);

$erreurs     = [];
$succes      = false;
$demande     = [];
$nom         = '';
$email       = '';
$type        = '';
$budget      = '';
$delai       = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_jeton_csrf();

    $nom         = nettoyer($_POST['nom']         ?? '');
    $email       = nettoyer($_POST['email']       ?? '');
    $type        = nettoyer($_POST['type']        ?? '');
    $budget      = nettoyer($_POST['budget']      ?? '');
    $delai       = nettoyer($_POST['delai']       ?? '');
    $description = nettoyer($_POST['description'] ?? '');

    if (!champ_requis($nom))         $erreurs['nom']         = 'Le nom est obligatoire.';
    if (!email_valide($email))       $erreurs['email']       = 'L\'adresse e-mail est invalide.';
    if (!champ_requis($type))        $erreurs['type']        = 'Veuillez choisir un type de projet.';
    if (!champ_requis($description)) $erreurs['description'] = 'La description est obligatoire.';

    if (empty($erreurs)) {
        sauvegarder_demande($pdo, $nom, $email, $type, $description, $budget ?: 'Non précisé');
        $demande = [
            'nom'         => $nom,
            'email'       => $email,
            'type'        => $type,
            'budget'      => $budget ?: 'Non précisé',
            'delai'       => $delai  ?: 'Non précisé',
            'description' => $description,
        ];
        $succes = true;
        $nom = $email = $type = $budget = $delai = $description = '';
    }
}

$jeton_csrf = generer_jeton_csrf();

$types_projet = [
    'site-vitrine' => 'Site vitrine',
    'portfolio'    => 'Portfolio',
    'e-commerce'   => 'Site Web / E-commerce',
    'blog'         => 'Blog',
    'autre'        => 'Autre',
];

$budgets = [
    'moins-300000' => 'Moins de 300 000 FCFA',
    '300-500k'     => '300 000 – 500 000 FCFA',
    '500k-1m'      => '500 000 – 1 000 000 FCFA',
    'plus-1m'      => 'Plus de 1 000 000 FCFA',
    'a-definir'    => 'À définir ensemble',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Soumettre une demande de projet à El Hadji Moussa LY." />
  <title>Demande de projet — [El Hadji Moussa LY]</title>
  <link rel="stylesheet" href="/Portfolio/style.css" />
  <style>
    .alerte { border-radius: var(--radius); padding: 0.9rem 1.1rem; margin-bottom: var(--sp-sm); font-size: 0.9rem; display: flex; gap: 0.6rem; align-items: flex-start; }
    .alerte--succes { background: #eafaf1; border: 1px solid #a3e4c1; color: #1e7e47; }
    .alerte--erreur { background: #fdf3f2; border: 1px solid #f0b8b8; color: #c0392b; }
    .form-group input.erreur,
    .form-group textarea.erreur,
    .form-group select.erreur { border-color: #e74c3c; }
    .champ-erreur { font-size: 0.8rem; color: #e74c3c; margin-top: 0.25rem; display: block; }
    .recapitulatif { background: var(--tag-bg); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: var(--sp-md); margin-top: var(--sp-sm); }
    .recapitulatif__titre { font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--accent); margin-bottom: var(--sp-sm); }
    .recapitulatif__ligne { display: flex; gap: var(--sp-sm); padding: 0.65rem 0; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
    .recapitulatif__ligne:last-child { border-bottom: none; }
    .recapitulatif__cle { color: var(--muted); min-width: 140px; font-weight: 600; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.04em; flex-shrink: 0; }
  </style>
</head>
<body>

  <a href="#contenu-principal" class="skip-link">Aller au contenu</a>

  <?php require 'composants/navigation.php'; ?>

  <main id="contenu-principal">

    <div class="page-hero" style="background: var(--surface); border-bottom: 1px solid var(--border);">
      <div class="container">
        <span class="section-label anim">Travaillons ensemble</span>
        <h1 class="anim anim--d1">Demande de projet</h1>
        <p class="anim anim--d2" style="margin-top:0.75rem; max-width:520px;">
          Tu as un projet en tête ? Remplis ce formulaire et je te répondrai avec une estimation sous 48 heures.
        </p>
      </div>
    </div>

    <section>
      <div class="container">

        <p style="margin-bottom: var(--sp-md);">
          <a href="contact.php" style="font-size:0.88rem; color:var(--muted);">← Retour au contact</a>
        </p>

        <?php if ($succes && !empty($demande)) : ?>

          <div class="alerte alerte--succes" style="max-width:680px;">
            <span>✓</span>
            <div>
              <strong>Demande envoyée et enregistrée !</strong><br>
              Merci <?= htmlspecialchars($demande['nom']) ?>.
              Je t'enverrai un retour à <strong><?= htmlspecialchars($demande['email']) ?></strong> très bientôt.
            </div>
          </div>

          <div class="recapitulatif" style="max-width:680px;">
            <div class="recapitulatif__titre">📋 Récapitulatif de ta demande</div>
            <div class="recapitulatif__ligne">
              <span class="recapitulatif__cle">Nom complet</span>
              <span><?= htmlspecialchars($demande['nom']) ?></span>
            </div>
            <div class="recapitulatif__ligne">
              <span class="recapitulatif__cle">Email</span>
              <span><?= htmlspecialchars($demande['email']) ?></span>
            </div>
            <div class="recapitulatif__ligne">
              <span class="recapitulatif__cle">Type de projet</span>
              <span><?= htmlspecialchars($demande['type']) ?></span>
            </div>
            <div class="recapitulatif__ligne">
              <span class="recapitulatif__cle">Budget</span>
              <span><?= htmlspecialchars($demande['budget']) ?></span>
            </div>
            <div class="recapitulatif__ligne">
              <span class="recapitulatif__cle">Délai souhaité</span>
              <span><?= htmlspecialchars($demande['delai']) ?></span>
            </div>
            <div class="recapitulatif__ligne">
              <span class="recapitulatif__cle">Description</span>
              <span><?= htmlspecialchars($demande['description']) ?></span>
            </div>
          </div>

          <div style="margin-top: var(--sp-md);">
            <a href="demande-projet.php" class="btn btn--outline">Soumettre une nouvelle demande</a>
          </div>

        <?php else : ?>

          <?php if (!empty($erreurs)) : ?>
            <div class="alerte alerte--erreur" style="max-width:680px;">
              <span>!</span>
              <div><strong>Formulaire incomplet</strong> — corrige les <?= count($erreurs) ?> erreur<?= count($erreurs) > 1 ? 's' : '' ?> ci-dessous.</div>
            </div>
          <?php endif; ?>

          <div class="form-card" style="max-width:680px;">
            <p class="form-card__title">Décris ton projet</p>
            <p class="form-card__intro">Plus tu es précis, plus je pourrai te faire une estimation juste.</p>

            <form method="POST" action="demande-projet.php" novalidate>

              <input type="hidden" name="jeton_csrf" value="<?= htmlspecialchars($jeton_csrf) ?>">

              <div class="form-group">
                <label for="nom">Nom complet <abbr title="champ obligatoire">*</abbr></label>
                <input type="text" id="nom" name="nom"
                  value="<?= htmlspecialchars($nom) ?>" placeholder="Ton nom complet"
                  autocomplete="name"
                  class="<?= isset($erreurs['nom']) ? 'erreur' : '' ?>">
                <?php if (isset($erreurs['nom'])) : ?>
                  <span class="champ-erreur"><?= htmlspecialchars($erreurs['nom']) ?></span>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label for="email">Adresse e-mail <abbr title="champ obligatoire">*</abbr></label>
                <input type="email" id="email" name="email"
                  value="<?= htmlspecialchars($email) ?>" placeholder="ton@email.com"
                  autocomplete="email"
                  class="<?= isset($erreurs['email']) ? 'erreur' : '' ?>">
                <?php if (isset($erreurs['email'])) : ?>
                  <span class="champ-erreur"><?= htmlspecialchars($erreurs['email']) ?></span>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label for="type">Type de projet <abbr title="champ obligatoire">*</abbr></label>
                <select id="type" name="type" class="<?= isset($erreurs['type']) ? 'erreur' : '' ?>">
                  <option value="" disabled <?= $type === '' ? 'selected' : '' ?>>Sélectionne un type</option>
                  <?php foreach ($types_projet as $val => $libelle) : ?>
                    <option value="<?= htmlspecialchars($val) ?>" <?= $type === $val ? 'selected' : '' ?>>
                      <?= htmlspecialchars($libelle) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php if (isset($erreurs['type'])) : ?>
                  <span class="champ-erreur"><?= htmlspecialchars($erreurs['type']) ?></span>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label for="budget">Budget approximatif</label>
                <select id="budget" name="budget">
                  <option value="" disabled <?= $budget === '' ? 'selected' : '' ?>>Sélectionne une fourchette</option>
                  <?php foreach ($budgets as $val => $libelle) : ?>
                    <option value="<?= htmlspecialchars($val) ?>" <?= $budget === $val ? 'selected' : '' ?>>
                      <?= htmlspecialchars($libelle) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="delai">Délai souhaité</label>
                <input type="text" id="delai" name="delai"
                  value="<?= htmlspecialchars($delai) ?>"
                  placeholder="Ex : dans 1 mois, avant juillet…">
              </div>

              <div class="form-group">
                <label for="description">Description du projet <abbr title="champ obligatoire">*</abbr></label>
                <textarea id="description" name="description"
                  placeholder="Décris ton projet : objectif, public cible, fonctionnalités…"
                  style="min-height:160px;"
                  class="<?= isset($erreurs['description']) ? 'erreur' : '' ?>"
                ><?= htmlspecialchars($description) ?></textarea>
                <?php if (isset($erreurs['description'])) : ?>
                  <span class="champ-erreur"><?= htmlspecialchars($erreurs['description']) ?></span>
                <?php endif; ?>
              </div>

              <button type="submit" class="btn btn--primary" style="width:100%;">Envoyer ma demande →</button>
              <p style="font-size:0.78rem; color:var(--muted); margin-top:0.75rem; text-align:center;">Les champs marqués * sont obligatoires.</p>

            </form>
          </div>

        <?php endif; ?>

      </div>
    </section>

  </main>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>
