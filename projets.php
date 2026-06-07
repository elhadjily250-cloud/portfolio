<?php
require 'fonctions.php';
require 'config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

enregistrer_visite($pdo);

$mot_cle  = nettoyer($_GET['q'] ?? '');
$resultats = $mot_cle !== ''
    ? rechercher_projets($pdo, $mot_cle)
    : obtenir_projets($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Tous les projets de El Hadji Moussa LY — Développeur web." />
  <title>Projets — [El Hadji Moussa LY]</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .recherche-info { font-size: 0.88rem; color: var(--muted); margin-bottom: var(--sp-md); }
    .recherche-info strong { color: var(--text); }
    .aucun-resultat { text-align: center; padding: var(--sp-lg) 0; color: var(--muted); }
    .aucun-resultat__emoji { font-size: 3rem; margin-bottom: var(--sp-sm); }
  </style>
</head>
<body>

  <a href="#contenu-principal" class="skip-link">Aller au contenu</a>

  <?php require 'composants/navigation.php'; ?>

  <main id="contenu-principal">

    <div class="page-hero" style="background: var(--surface); border-bottom: 1px solid var(--border);">
      <div class="container">
        <span class="section-label anim">Portfolio</span>
        <h1 class="anim anim--d1">Mes projets</h1>
        <p class="anim anim--d2" style="margin-top:0.75rem; max-width:520px;">
          Tout ce que j'ai construit — des projets de cours aux expérimentations personnelles.
        </p>
      </div>
    </div>

    <section>
      <div class="container">

        <form method="GET" action="projets.php" role="search" style="margin-bottom: var(--sp-sm);">
          <div class="search-wrap">
            <input
              type="search"
              name="q"
              id="q"
              class="search-input"
              placeholder="Rechercher un projet (ex: PHP, restaurant, ESP32…)"
              value="<?= htmlspecialchars($mot_cle) ?>"
              aria-label="Rechercher un projet"
            >
            <button type="submit" class="btn btn--primary">Rechercher</button>
            <?php if ($mot_cle !== '') : ?>
              <a href="projets.php" class="btn btn--outline">✕ Effacer</a>
            <?php endif; ?>
          </div>
        </form>

        <?php if ($mot_cle !== '') : ?>
          <p class="recherche-info">
            <?= count($resultats) ?> résultat<?= count($resultats) > 1 ? 's' : '' ?>
            pour &laquo;&nbsp;<strong><?= htmlspecialchars($mot_cle) ?></strong>&nbsp;&raquo;
          </p>
        <?php endif; ?>

        <?php if (!empty($resultats)) : ?>
          <div class="projects-grid">
            <?php foreach ($resultats as $index => $projet) : ?>
              <article class="project-card anim anim--d<?= ($index % 5) + 1 ?>"
                       aria-label="Projet : <?= htmlspecialchars($projet['titre']) ?>">
                <div class="project-card__thumb">
                  <?php if (!empty($projet['image'])) : ?>
                    <img src="<?= htmlspecialchars($projet['image']) ?>"
                         alt="<?= htmlspecialchars($projet['titre']) ?>"
                         loading="lazy">
                  <?php else : ?>
                    🖥️
                  <?php endif; ?>
                </div>
                <div class="project-card__body">
                  <div class="project-card__tags">
                    <?php foreach (explode(',', $projet['technologies']) as $tech) : ?>
                      <span class="tag"><?= htmlspecialchars(trim($tech)) ?></span>
                    <?php endforeach; ?>
                  </div>
                  <h2 class="project-card__title"><?= htmlspecialchars($projet['titre']) ?></h2>
                  <p class="project-card__desc"><?= htmlspecialchars($projet['description']) ?></p>
                  <?php if (!empty($projet['lien'])) : ?>
                    <a href="<?= htmlspecialchars($projet['lien']) ?>" class="project-card__link" target="_blank" rel="noopener">
                      Voir le projet →
                    </a>
                  <?php endif; ?>
                </div>
              </article>
            <?php endforeach; ?>
          </div>

        <?php else : ?>
          <div class="aucun-resultat">
            <div class="aucun-resultat__emoji">🔍</div>
            <?php if ($mot_cle !== '') : ?>
              <p>Aucun projet ne correspond à &laquo;&nbsp;<strong><?= htmlspecialchars($mot_cle) ?></strong>&nbsp;&raquo;.</p>
              <a href="projets.php" class="btn btn--outline" style="margin-top: var(--sp-sm);">Voir tous les projets</a>
            <?php else : ?>
              <p>Aucun projet pour l'instant. Reviens bientôt !</p>
            <?php endif; ?>
          </div>
        <?php endif; ?>

      </div>
    </section>

  </main>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>
