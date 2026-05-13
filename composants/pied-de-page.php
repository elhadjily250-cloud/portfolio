<?php

$annee = date('Y');
?>
<footer class="footer">
  <div class="container">
    <div class="footer__inner">

      <div>
        <div class="footer__logo">[El<span>.</span>LY]</div>
        <p class="footer__about">
          Développeur web en formation, toujours curieux et prêt à relever
          de nouveaux défis numériques.
        </p>
      </div>

      <div class="footer__col">
        <h4>Navigation</h4>
        <ul>
          <li><a href="index.php">Accueil</a></li>
          <li><a href="projets.php">Projets</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h4>Liens</h4>
        <ul>
          <li>
            <a href="https://github.com/elhadjily250-cloud"
               target="_blank" rel="noopener">GitHub</a>
          </li>
          <li>
            <a href="cv.pdf" target="_blank">Télécharger mon CV</a>
          </li>
        </ul>
      </div>

    </div>

    <div class="footer__bottom">
      <span class="footer__copy">
        &copy; <?= $annee ?> El Hadji Moussa LY. Tous droits réservés.
      </span>
      <nav class="footer__socials" aria-label="Réseaux sociaux">
        <a href="https://github.com/elhadjily250-cloud"
           target="_blank" rel="noopener" aria-label="GitHub">GH</a>
      </nav>
    </div>

  </div>
</footer>
