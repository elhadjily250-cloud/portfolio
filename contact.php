<?php
require 'fonctions.php';
require 'config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

enregistrer_visite($pdo);

$erreurs = [];
$succes  = false;
$nom     = '';
$email   = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_jeton_csrf();

    $nom     = nettoyer($_POST['nom']     ?? '');
    $email   = nettoyer($_POST['email']   ?? '');
    $message = nettoyer($_POST['message'] ?? '');

    if (!champ_requis($nom))     $erreurs['nom']     = 'Le nom est obligatoire.';
    if (!email_valide($email))   $erreurs['email']   = 'L\'adresse e-mail est invalide.';
    if (!champ_requis($message)) $erreurs['message'] = 'Le message ne peut pas être vide.';

    if (empty($erreurs)) {
        sauvegarder_message($pdo, $nom, $email, $message);
        $succes  = true;
        $nom = $email = $message = '';
    }
}

$jeton_csrf = generer_jeton_csrf();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Contacte El Hadji Moussa LY — Développeur web." />
  <title>Contact — [El Hadji Moussa LY]</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .alerte { border-radius: var(--radius); padding: 0.9rem 1.1rem; margin-bottom: var(--sp-sm); font-size: 0.9rem; display: flex; gap: 0.6rem; align-items: flex-start; }
    .alerte--succes { background: #eafaf1; border: 1px solid #a3e4c1; color: #1e7e47; }
    .alerte--erreur { background: #fdf3f2; border: 1px solid #f0b8b8; color: #c0392b; }
    .form-group input.erreur,
    .form-group textarea.erreur { border-color: #e74c3c; }
    .champ-erreur { font-size: 0.8rem; color: #e74c3c; margin-top: 0.25rem; display: block; }
  </style>
</head>
<body>

  <a href="#contenu-principal" class="skip-link">Aller au contenu</a>

  <?php require 'composants/navigation.php'; ?>

  <main id="contenu-principal">
    <section class="contact-page" style="padding-top: calc(var(--sp-xl) + 4rem);">
      <div class="container">
        <div class="contact-layout">

          <div>
            <span class="section-label anim">Restons en contact</span>
            <h1 class="anim anim--d1" style="font-size:clamp(2rem,4vw,3.2rem); margin-bottom:0.75rem;">
              Parlons de<br>
              <em style="color:var(--accent); font-style:italic;">ton projet.</em>
            </h1>
            <p class="anim anim--d2">
              Tu as une question, une idée, ou tu veux qu'on travaille ensemble ?
              Je suis disponible et je réponds rapidement.
            </p>

            <div style="margin-top: var(--sp-md);">
              <div class="contact-info__item anim anim--d2">
                <div class="contact-info__icon" aria-hidden="true">✉️</div>
                <div>
                  <div class="contact-info__label">Email</div>
                  <a href="mailto:elhadjily250@gmail.com" class="contact-info__val">elhadjily250@gmail.com</a>
                </div>
              </div>
              <div class="contact-info__item anim anim--d3">
                <div class="contact-info__icon" aria-hidden="true">📍</div>
                <div>
                  <div class="contact-info__label">Localisation</div>
                  <span class="contact-info__val">Dakar, Sénégal</span>
                </div>
              </div>
              <div class="contact-info__item anim anim--d4">
                <div class="contact-info__icon" aria-hidden="true">⏱️</div>
                <div>
                  <div class="contact-info__label">Disponibilité</div>
                  <span class="contact-info__val">Réponse sous 24–48h</span>
                </div>
              </div>
            </div>

            <div style="margin-top: var(--sp-md);" class="anim anim--d5">
              <a href="https://github.com/elhadjily250-cloud" target="_blank" rel="noopener"
                 class="btn btn--outline" style="padding:0.55rem 1.1rem; font-size:0.88rem;">
                GitHub
              </a>
            </div>

            <div style="margin-top: var(--sp-md); padding-top: var(--sp-md); border-top: 1px solid var(--border);" class="anim anim--d5">
              <p style="font-size:0.9rem; color:var(--muted); margin-bottom:0.75rem;">Tu as un projet précis en tête ?</p>
              <a href="demande-projet.php" class="btn btn--primary">Soumettre une demande →</a>
            </div>
          </div>

          <div class="form-card anim anim--d1">

            <?php if ($succes) : ?>
              <div class="alerte alerte--succes">
                <span>✓</span>
                <div><strong>Message envoyé !</strong><br>Merci pour ton message. Je te répondrai très bientôt.</div>
              </div>
            <?php endif; ?>

            <?php if (!empty($erreurs)) : ?>
              <div class="alerte alerte--erreur">
                <span>!</span>
                <div><strong>Formulaire incomplet</strong> — corrige les <?= count($erreurs) ?> erreur<?= count($erreurs) > 1 ? 's' : '' ?> ci-dessous.</div>
              </div>
            <?php endif; ?>

            <p class="form-card__title">Envoie-moi un message</p>
            <p class="form-card__intro">Une question, une opportunité, un bonjour ? Écris-moi !</p>

            <form method="POST" action="contact.php" novalidate>

              <!-- Jeton CSRF — protège le formulaire contre les attaques -->
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
                <label for="message">Message <abbr title="champ obligatoire">*</abbr></label>
                <textarea id="message" name="message" placeholder="Ton message ici…"
                  class="<?= isset($erreurs['message']) ? 'erreur' : '' ?>"
                ><?= htmlspecialchars($message) ?></textarea>
                <?php if (isset($erreurs['message'])) : ?>
                  <span class="champ-erreur"><?= htmlspecialchars($erreurs['message']) ?></span>
                <?php endif; ?>
              </div>

              <button type="submit" class="btn btn--primary" style="width:100%;">Envoyer le message →</button>
              <p style="font-size:0.78rem; color:var(--muted); margin-top:0.75rem; text-align:center;">Les champs marqués * sont obligatoires.</p>

            </form>
          </div>

        </div>
      </div>
    </section>
  </main>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>
