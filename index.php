<?php
require 'fonctions.php';
require 'config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

enregistrer_visite($pdo);

$projets_recents = array_slice(obtenir_projets($pdo), 0, 3);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Portfolio de El Hadji Moussa LY — Développeur web passionné par le HTML, CSS, JavaScript et PHP." />
  <title>[EL Hadji Moussa LY] — Développeur Web</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <a href="#contenu-principal" class="skip-link">Aller au contenu</a>

  <?php require 'composants/navigation.php'; ?>

  <main id="contenu-principal">

    <section class="hero">
      <div class="container">
        <div class="hero__inner">

          <div>
            <div class="hero__badge anim">Disponible pour des projets</div>

            <h1 class="hero__title anim anim--d1">
              Bonjour, je suis<br>
              <em>[EL Hadji Moussa LY]</em>
            </h1>

            <p class="hero__desc anim anim--d2">
              Développeur web, je conçois et développe des sites web modernes, performants
              et centrés sur l'expérience utilisateur.<br>
              J'utilise des technologies telles que HTML, CSS, JavaScript, PHP et MySQL
              pour créer des interfaces dynamiques et des sites web fonctionnels.<br>
              Toujours en apprentissage, je développe des solutions efficaces, évolutives
              et adaptées aux besoins des utilisateurs.
            </p>

            <div class="hero__actions anim anim--d3">
              <a href="projets.php" class="btn btn--primary">Voir mes projets →</a>
              <a href="contact.php" class="btn btn--outline">Me contacter</a>
            </div>

            <div class="hero__stats anim anim--d4">
              <div>
                <span class="hero__stat-num">3+</span>
                <span class="hero__stat-lbl">Projets réalisés</span>
              </div>
              <div>
                <span class="hero__stat-num">4+</span>
                <span class="hero__stat-lbl">Langages appris</span>
              </div>
              <div>
                <span class="hero__stat-num">2</span>
                <span class="hero__stat-lbl">Ans de formation en cours</span>
              </div>
            </div>
          </div>

          <div class="hero__photo" aria-label="Photo de profil">
            <img src="Image-profil.jpg" alt="Photo de El Hadji Moussa LY">
          </div>

        </div>
      </div>
    </section>

    <section class="skills" id="competences">
      <div class="container">

        <div style="margin-bottom: var(--sp-lg);">
          <span class="section-label">Ce que je sais faire</span>
          <h2>Compétences techniques</h2>
          <p style="margin-top:0.75rem; max-width:480px;">
            Les langages et outils que j'utilise au quotidien pour donner vie à mes projets.
          </p>
        </div>

        <?php
        $competences = [
            ['icone' => '🌐', 'nom' => 'HTML5',               'niveau' => 'Avancé',           'pct' => 85],
            ['icone' => '🎨', 'nom' => 'CSS3 / Flexbox / Grid','niveau' => 'Intermédiaire',    'pct' => 70],
            ['icone' => '⚡', 'nom' => 'JavaScript',           'niveau' => 'Débutant avancé',  'pct' => 50],
            ['icone' => '🐘', 'nom' => 'PHP',                  'niveau' => 'En apprentissage', 'pct' => 35],
            ['icone' => '🗄️','nom' => 'MySQL',                 'niveau' => 'En apprentissage', 'pct' => 30],
            ['icone' => '🔧', 'nom' => 'Git & GitHub',         'niveau' => 'Intermédiaire',    'pct' => 60],
        ];
        ?>

        <div class="skills__grid">
          <?php foreach ($competences as $index => $comp) : ?>
            <div class="skill-card anim anim--d<?= ($index % 5) + 1 ?>">
              <div class="skill-card__icon"><?= $comp['icone'] ?></div>
              <div class="skill-card__name"><?= htmlspecialchars($comp['nom']) ?></div>
              <div class="skill-card__level"><?= htmlspecialchars($comp['niveau']) ?></div>
              <div class="skill-bar">
                <div class="skill-bar__fill" style="width: <?= $comp['pct'] ?>%"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </section>

    <section id="experience">
      <div class="container">

        <div style="margin-bottom: var(--sp-lg);">
          <span class="section-label">Mon parcours</span>
          <h2>Expériences &amp; Formation</h2>
          <p style="margin-top:0.75rem; max-width:480px;">
            Ce que j'ai vécu avant et pendant ma formation de développeur web.
          </p>
        </div>

        <div>
          <div class="timeline__item anim anim--d1">
            <div class="timeline__date">2024 – Auj.</div>
            <div>
              <div class="timeline__role">Formation en Génie Logiciel et Administration réseau</div>
              <div class="timeline__company">ESTM (École Supérieure de Technologie et de Management)</div>
              <p class="timeline__desc">
                * Apprentissage développement Web : HTML, CSS, JavaScript, PHP.<br>
                * Gestion de bases de données : MySQL.<br>
                * Réalisation de projets concrets dont ce portfolio.
              </p>
            </div>
          </div>

          <div class="timeline__item anim anim--d2">
            <div class="timeline__date">2022 – Auj.</div>
            <div>
              <div class="timeline__role">Président associatif</div>
              <div class="timeline__company">Association</div>
              <p class="timeline__desc">
                * Coordination des activités et gestion de l'équipe.<br>
                * Organisation d'événements (réunions…).<br>
                * Prise de décision stratégique et gestion des ressources.<br>
                * Développement du leadership et du travail en équipe.
              </p>
            </div>
          </div>

          <div class="timeline__item anim anim--d3">
            <div class="timeline__date">2025 – 2026</div>
            <div>
              <div class="timeline__role">Gestionnaire d'un Répertoire Téléphonique (C &amp; MySQL)</div>
              <div class="timeline__company">ESTM</div>
              <p class="timeline__desc">
                * Mise en œuvre de la persistance de données via l'interfaçage entre le langage C et MySQL.<br>
                * Développement des fonctions (Saisie, Modification, Recherche, Suppression) pour la gestion des contacts.<br>
                * Utilisation de la bibliothèque mysql.h, gestion des requêtes SQL et des structures de données en C.
              </p>
            </div>
          </div>
        </div>

      </div>
    </section>

    <section style="background: var(--surface);">
      <div class="container">

        <div style="margin-bottom: var(--sp-lg);">
          <span class="section-label">Mes réalisations</span>
          <h2>Projets récents</h2>
          <p style="margin-top:0.75rem; max-width:480px;">
            Un aperçu de ce que j'ai construit. Tous mes projets sont sur la page dédiée.
          </p>
        </div>

        <?php if (!empty($projets_recents)) : ?>
          <div class="projects-grid">
            <?php foreach ($projets_recents as $index => $projet) : ?>
              <article class="project-card anim anim--d<?= $index + 1 ?>">
                <div class="project-card__thumb">
                  <?php if (!empty($projet['image'])) : ?>
                    <img src="<?= htmlspecialchars($projet['image']) ?>"
                         alt="<?= htmlspecialchars($projet['titre']) ?>">
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
                  <h3 class="project-card__title"><?= htmlspecialchars($projet['titre']) ?></h3>
                  <p class="project-card__desc"><?= htmlspecialchars($projet['description']) ?></p>
                  <a href="projets.php" class="project-card__link">Voir le projet →</a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else : ?>
          <p style="color:var(--muted);">Les projets seront affichés ici dès qu'ils seront ajoutés via l'administration.</p>
        <?php endif; ?>

        <div style="text-align:center; margin-top:var(--sp-md);">
          <a href="projets.php" class="btn btn--outline anim anim--d4">Voir tous mes projets →</a>
        </div>

      </div>
    </section>

  </main>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>
