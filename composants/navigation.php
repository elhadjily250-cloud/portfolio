<?php $page = page_courante(); ?>
<header class="nav">
  <div class="container nav__inner">

    <a href="index.php" class="nav__logo">[El<span>.</span>LY]</a>

    <nav class="nav__links" aria-label="Navigation principale">
      <a href="index.php" <?= $page === 'index.php' ? 'class="active"' : '' ?>>Accueil</a>
      <a href="index.php#competences">Compétences</a>
      <a href="index.php#experience">Expérience</a>
      <a href="projets.php" <?= $page === 'projets.php' ? 'class="active"' : '' ?>>Projets</a>
      <a href="contact.php" class="nav__cta<?= $page === 'contact.php' ? ' active' : '' ?>">Me contacter</a>

      <button class="theme-toggle" id="theme-toggle" aria-label="Basculer le thème clair/sombre">
        <span class="theme-toggle__lune"   aria-hidden="true">🌙</span>
        <span class="theme-toggle__soleil" aria-hidden="true">☀️</span>
      </button>
    </nav>

  </div>
</header>

<script>
(function () {
  const html   = document.documentElement;
  const bouton = document.getElementById('theme-toggle');
  const CLE    = 'theme-portfolio';

  function appliquerTheme(theme) {
    html.classList.toggle('theme-clair', theme === 'clair');
  }

  const sauvegarde = localStorage.getItem(CLE);
  if (sauvegarde) {
    appliquerTheme(sauvegarde);
  } else if (window.matchMedia('(prefers-color-scheme: light)').matches) {
    appliquerTheme('clair');
  }

  bouton.addEventListener('click', function () {
    const nouveauTheme = html.classList.contains('theme-clair') ? 'sombre' : 'clair';
    appliquerTheme(nouveauTheme);
    localStorage.setItem(CLE, nouveauTheme);
  });
})();
</script>
