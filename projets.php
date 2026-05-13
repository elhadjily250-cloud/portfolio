<?php
require 'fonctions.php';

$mot_cle = nettoyer($_GET['q'] ?? '');

$tous_les_projets = obtenir_projets();

if ($mot_cle !== '') {
    $resultats = [];
    foreach ($tous_les_projets as $projet) {
        if (stripos($projet['titre'],       $mot_cle) !== false ||
            stripos($projet['description'], $mot_cle) !== false) {
            $resultats[] = $projet;
        }
    }
} else {
    $resultats = $tous_les_projets;
}

$technologies_filtres = ['HTML', 'CSS', 'PHP', 'MySQL', 'C'];
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

    .recherche-info {
      font-size: 0.88rem;
      color: var(--muted);
      margin-bottom: var(--sp-md);
    }
    .recherche-info strong { color: var(--text); }

    .aucun-resultat {
      text-align: center;
      padding: var(--sp-lg) 0;
      color: var(--muted);
    }
    .aucun-resultat__emoji { font-size: 3rem; margin-bottom: var(--sp-sm); }

    /* Champ de recherche avec erreur */
    .search-input.erreur {
      border-color: #e74c3c;
    }
    .champ-erreur {
      font-size: 0.82rem;
      color: #e74c3c;
      margin-top: 0.3rem;
      display: block;
    }
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
          Recherche par mot-clé ou filtre par technologie.
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
            <?php foreach ($resultats as $index => $projet) :
              $delai = ($index % 5) + 1;
            ?>
              <article class="project-card anim anim--d<?= $delai ?>"
                       aria-label="Projet : <?= htmlspecialchars($projet['titre']) ?>">

                <div class="project-card__thumb">
                  <?php if (!empty($projet['image'])) : ?>
                    <img src="<?= htmlspecialchars($projet['image']) ?>"
                         alt="<?= htmlspecialchars($projet['titre']) ?>"
                         loading="lazy">
                  <?php else : ?>
                    <?= $projet['emoji'] ?>
                  <?php endif; ?>
                </div>

                <div class="project-card__body">
                  <div class="project-card__tags">
                    <?php foreach ($projet['technologies'] as $tech) : ?>
                      <span class="tag"><?= htmlspecialchars($tech) ?></span>
                    <?php endforeach; ?>
                  </div>
                  <h2 class="project-card__title"><?= htmlspecialchars($projet['titre']) ?></h2>
                  <p class="project-card__desc"><?= htmlspecialchars($projet['description']) ?></p>
                </div>

              </article>
            <?php endforeach; ?>
          </div>

        <?php else : ?>
          <div class="aucun-resultat">
            <div class="aucun-resultat__emoji">🔍</div>
            <p>Aucun projet ne correspond à
              &laquo;&nbsp;<strong><?= htmlspecialchars($mot_cle) ?></strong>&nbsp;&raquo;.
            </p>
            <a href="projets.php" class="btn btn--outline" style="margin-top: var(--sp-sm);">
              Voir tous les projets
            </a>
          </div>
        <?php endif; ?>

      </div>
    </section>

  </main>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>
