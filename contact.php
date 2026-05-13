<?php
require 'fonctions.php';


$c_erreurs = [];
$c_succes  = false;
$c_prenom  = '';
$c_nom     = '';
$c_email   = '';
$c_sujet   = '';
$c_message = '';


$p_erreurs     = [];
$p_succes      = false;
$p_prenom      = '';
$p_nom         = '';
$p_email       = '';
$p_type        = '';
$p_budget      = '';
$p_delai       = '';
$p_description = '';
$p_demande     = []; 


$formulaire_soumis = $_POST['formulaire'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($formulaire_soumis === 'contact') {

        $c_prenom  = nettoyer($_POST['prenom']  ?? '');
        $c_nom     = nettoyer($_POST['nom']     ?? '');
        $c_email   = nettoyer($_POST['email']   ?? '');
        $c_sujet   = nettoyer($_POST['sujet']   ?? '');
        $c_message = nettoyer($_POST['message'] ?? '');

        if (!champ_requis($c_prenom))  $c_erreurs['prenom']  = 'Le prénom est obligatoire.';
        if (!champ_requis($c_nom))     $c_erreurs['nom']     = 'Le nom est obligatoire.';
        if (!email_valide($c_email))   $c_erreurs['email']   = 'L\'adresse e-mail est invalide.';
        if (!champ_requis($c_sujet))   $c_erreurs['sujet']   = 'Le sujet est obligatoire.';
        if (!champ_requis($c_message)) $c_erreurs['message'] = 'Le message ne peut pas être vide.';

        if (empty($c_erreurs)) {
            $c_succes = true;

            $c_prenom = $c_nom = $c_email = $c_sujet = $c_message = '';
        }

    } elseif ($formulaire_soumis === 'projet') {

        $p_prenom      = nettoyer($_POST['prenom']      ?? '');
        $p_nom         = nettoyer($_POST['nom']         ?? '');
        $p_email       = nettoyer($_POST['email']       ?? '');
        $p_type        = nettoyer($_POST['type']        ?? '');
        $p_budget      = nettoyer($_POST['budget']      ?? '');
        $p_delai       = nettoyer($_POST['delai']       ?? '');
        $p_description = nettoyer($_POST['description'] ?? '');

        if (!champ_requis($p_prenom))      $p_erreurs['prenom']      = 'Le prénom est obligatoire.';
        if (!champ_requis($p_nom))         $p_erreurs['nom']         = 'Le nom est obligatoire.';
        if (!email_valide($p_email))       $p_erreurs['email']       = 'L\'adresse e-mail est invalide.';
        if (!champ_requis($p_type))        $p_erreurs['type']        = 'Veuillez choisir un type de projet.';
        if (!champ_requis($p_description)) $p_erreurs['description'] = 'La description est obligatoire.';

        if (empty($p_erreurs)) {
            $p_demande = [
                'prenom'      => $p_prenom,
                'nom'         => $p_nom,
                'email'       => $p_email,
                'type'        => $p_type,
                'budget'      => $p_budget ?: 'Non précisé',
                'delai'       => $p_delai  ?: 'Non précisé',
                'description' => $p_description,
            ];
            $p_succes = true;

            $p_prenom = $p_nom = $p_email = $p_type = $p_budget = $p_delai = $p_description = '';
        }
    }
}


$onglet_actif = (!empty($p_erreurs) || $p_succes) ? 'projet' : 'contact';
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
    /* ---- Onglets ---- */
    .tab-pane { display: none; }
    .tab-pane.actif { display: block; }

    .tabs { display: flex; border-bottom: 1px solid var(--border); margin-bottom: var(--sp-md); }
    .tab-link {
      display: inline-block;
      padding: 0.75rem 1.25rem;
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--muted);
      border-bottom: 2px solid transparent;
      margin-bottom: -1px;
      transition: var(--trans);
      cursor: pointer;
      background: none;
      border-top: none;
      border-left: none;
      border-right: none;
      font-family: inherit;
    }
    .tab-link:hover { color: var(--text); }
    .tab-link.actif { color: var(--accent); border-bottom-color: var(--accent); }

    .alerte {
      border-radius: 6px;
      padding: 0.9rem 1.1rem;
      margin-bottom: var(--sp-sm);
      font-size: 0.9rem;
      display: flex;
      gap: 0.6rem;
      align-items: flex-start;
    }
    .alerte--succes {
      background: #eafaf1;
      border: 1px solid #a3e4c1;
      color: #1e7e47;
    }
    .alerte--erreur {
      background: #fdf3f2;
      border: 1px solid #f0b8b8;
      color: #c0392b;
    }

    .form-group input.erreur,
    .form-group textarea.erreur,
    .form-group select.erreur {
      border-color: #e74c3c;
    }
    .champ-erreur {
      font-size: 0.8rem;
      color: #e74c3c;
      margin-top: 0.25rem;
      display: block;
    }

    .recapitulatif {
      background: var(--tag-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: var(--sp-md);
      margin-top: var(--sp-sm);
    }
    .recapitulatif__titre {
      font-weight: 700;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--accent);
      margin-bottom: var(--sp-sm);
    }
    .recapitulatif__ligne {
      display: flex;
      gap: var(--sp-sm);
      padding: 0.6rem 0;
      border-bottom: 1px solid var(--border);
      font-size: 0.9rem;
    }
    .recapitulatif__ligne:last-child { border-bottom: none; }
    .recapitulatif__cle { color: var(--muted); min-width: 130px; font-weight: 500; }
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

            <h1 class="anim anim--d1"
                style="font-size:clamp(2rem,4vw,3.2rem); margin-bottom:0.75rem;">
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
                  <a href="mailto:elhadjily250@gmail.com" class="contact-info__val">
                    elhadjily250@gmail.com
                  </a>
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
              <p style="font-size:0.78rem; font-weight:700; text-transform:uppercase;
                         letter-spacing:0.08em; color:var(--muted); margin-bottom:0.75rem;">
                Retrouve-moi sur
              </p>
              <a href="https://github.com/elhadjily250-cloud"
                 target="_blank" rel="noopener"
                 class="btn btn--outline"
                 style="padding:0.55rem 1.1rem; font-size:0.88rem;">
                GitHub
              </a>
            </div>
          </div>

          <div class="form-card anim anim--d1">

            <div class="tabs" role="tablist">
              <button
                type="button"
                class="tab-link <?= $onglet_actif === 'contact' ? 'actif' : '' ?>"
                onclick="basculerOnglet('contact')"
                role="tab"
                aria-controls="panneau-contact">
                ✉️ Me contacter
              </button>
              <button
                type="button"
                class="tab-link <?= $onglet_actif === 'projet' ? 'actif' : '' ?>"
                onclick="basculerOnglet('projet')"
                role="tab"
                aria-controls="panneau-projet">
                💼 Demander un projet
              </button>
            </div>

            <div id="panneau-contact"
                 class="tab-pane tab-pane--contact <?= $onglet_actif === 'contact' ? 'actif' : '' ?>"
                 role="tabpanel">

              <?php if ($c_succes) : ?>
                <div class="alerte alerte--succes">
                  <span>✓</span>
                  <div>
                    <strong>Message envoyé !</strong><br>
                    Merci <?= htmlspecialchars($c_prenom ?: 'pour ton message') ?>.
                    Je te répondrai très bientôt.
                  </div>
                </div>
              <?php endif; ?>

              <?php if (!empty($c_erreurs)) : ?>
                <div class="alerte alerte--erreur">
                  <span>!</span>
                  <div>
                    <strong>Formulaire incomplet</strong> —
                    veuillez corriger les erreurs ci-dessous.
                  </div>
                </div>
              <?php endif; ?>

              <p class="form-card__title">Envoie-moi un message</p>
              <p class="form-card__intro">Une question, une opportunité, un bonjour ? Écris-moi !</p>

              <form method="POST" action="contact.php" novalidate>
                <input type="hidden" name="formulaire" value="contact">

                <div class="form-row">
                  <div class="form-group">
                    <label for="c-prenom">Prénom <abbr title="champ obligatoire">*</abbr></label>
                    <input
                      type="text"
                      id="c-prenom"
                      name="prenom"
                      value="<?= htmlspecialchars($c_prenom) ?>"
                      placeholder="Ton prénom"
                      autocomplete="given-name"
                      class="<?= isset($c_erreurs['prenom']) ? 'erreur' : '' ?>"
                    >
                    <?php if (isset($c_erreurs['prenom'])) : ?>
                      <span class="champ-erreur"><?= htmlspecialchars($c_erreurs['prenom']) ?></span>
                    <?php endif; ?>
                  </div>

                  <div class="form-group">
                    <label for="c-nom">Nom <abbr title="champ obligatoire">*</abbr></label>
                    <input
                      type="text"
                      id="c-nom"
                      name="nom"
                      value="<?= htmlspecialchars($c_nom) ?>"
                      placeholder="Ton nom"
                      autocomplete="family-name"
                      class="<?= isset($c_erreurs['nom']) ? 'erreur' : '' ?>"
                    >
                    <?php if (isset($c_erreurs['nom'])) : ?>
                      <span class="champ-erreur"><?= htmlspecialchars($c_erreurs['nom']) ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="form-group">
                  <label for="c-email">Adresse e-mail <abbr title="champ obligatoire">*</abbr></label>
                  <input
                    type="email"
                    id="c-email"
                    name="email"
                    value="<?= htmlspecialchars($c_email) ?>"
                    placeholder="ton@email.com"
                    autocomplete="email"
                    class="<?= isset($c_erreurs['email']) ? 'erreur' : '' ?>"
                  >
                  <?php if (isset($c_erreurs['email'])) : ?>
                    <span class="champ-erreur"><?= htmlspecialchars($c_erreurs['email']) ?></span>
                  <?php endif; ?>
                </div>

                <div class="form-group">
                  <label for="c-sujet">Sujet <abbr title="champ obligatoire">*</abbr></label>
                  <input
                    type="text"
                    id="c-sujet"
                    name="sujet"
                    value="<?= htmlspecialchars($c_sujet) ?>"
                    placeholder="De quoi tu veux parler ?"
                    class="<?= isset($c_erreurs['sujet']) ? 'erreur' : '' ?>"
                  >
                  <?php if (isset($c_erreurs['sujet'])) : ?>
                    <span class="champ-erreur"><?= htmlspecialchars($c_erreurs['sujet']) ?></span>
                  <?php endif; ?>
                </div>

                <div class="form-group">
                  <label for="c-message">Message <abbr title="champ obligatoire">*</abbr></label>
                  <textarea
                    id="c-message"
                    name="message"
                    placeholder="Ton message ici…"
                    class="<?= isset($c_erreurs['message']) ? 'erreur' : '' ?>"
                  ><?= htmlspecialchars($c_message) ?></textarea>
                  <?php if (isset($c_erreurs['message'])) : ?>
                    <span class="champ-erreur"><?= htmlspecialchars($c_erreurs['message']) ?></span>
                  <?php endif; ?>
                </div>

                <button type="submit" class="btn btn--primary" style="width:100%;">
                  Envoyer le message →
                </button>

                <p style="font-size:0.78rem; color:var(--muted); margin-top:0.75rem; text-align:center;">
                  Les champs marqués * sont obligatoires.
                </p>

              </form>
            </div>

            <div id="panneau-projet"
                 class="tab-pane tab-pane--projet <?= $onglet_actif === 'projet' ? 'actif' : '' ?>"
                 role="tabpanel">

              <?php if ($p_succes && !empty($p_demande)) : ?>
                <div class="alerte alerte--succes">
                  <span>✓</span>
                  <div>
                    <strong>Demande envoyée !</strong><br>
                    Je t'enverrai un retour à
                    <strong><?= htmlspecialchars($p_demande['email']) ?></strong> très bientôt.
                  </div>
                </div>

                <div class="recapitulatif">
                  <div class="recapitulatif__titre">📋 Récapitulatif de ta demande</div>
                  <div class="recapitulatif__ligne">
                    <span class="recapitulatif__cle">Nom complet</span>
                    <span><?= htmlspecialchars($p_demande['prenom'] . ' ' . $p_demande['nom']) ?></span>
                  </div>
                  <div class="recapitulatif__ligne">
                    <span class="recapitulatif__cle">Email</span>
                    <span><?= htmlspecialchars($p_demande['email']) ?></span>
                  </div>
                  <div class="recapitulatif__ligne">
                    <span class="recapitulatif__cle">Type de projet</span>
                    <span><?= htmlspecialchars($p_demande['type']) ?></span>
                  </div>
                  <div class="recapitulatif__ligne">
                    <span class="recapitulatif__cle">Budget</span>
                    <span><?= htmlspecialchars($p_demande['budget']) ?></span>
                  </div>
                  <div class="recapitulatif__ligne">
                    <span class="recapitulatif__cle">Délai souhaité</span>
                    <span><?= htmlspecialchars($p_demande['delai']) ?></span>
                  </div>
                  <div class="recapitulatif__ligne">
                    <span class="recapitulatif__cle">Description</span>
                    <span><?= htmlspecialchars($p_demande['description']) ?></span>
                  </div>
                </div>

              <?php else : ?>

                <?php if (!empty($p_erreurs)) : ?>
                  <div class="alerte alerte--erreur">
                    <span>!</span>
                    <div>
                      <strong>Formulaire incomplet</strong> —
                      veuillez corriger les erreurs ci-dessous.
                    </div>
                  </div>
                <?php endif; ?>

                <p class="form-card__title">Décris ton projet</p>
                <p class="form-card__intro">
                  Tu as un projet en tête ? Remplis ce formulaire et je t'enverrai un devis.
                </p>

                <form method="POST" action="contact.php" novalidate>
                  <input type="hidden" name="formulaire" value="projet">

                  <div class="form-row">
                    <div class="form-group">
                      <label for="p-prenom">Prénom <abbr title="champ obligatoire">*</abbr></label>
                      <input
                        type="text"
                        id="p-prenom"
                        name="prenom"
                        value="<?= htmlspecialchars($p_prenom) ?>"
                        placeholder="Ton prénom"
                        autocomplete="given-name"
                        class="<?= isset($p_erreurs['prenom']) ? 'erreur' : '' ?>"
                      >
                      <?php if (isset($p_erreurs['prenom'])) : ?>
                        <span class="champ-erreur"><?= htmlspecialchars($p_erreurs['prenom']) ?></span>
                      <?php endif; ?>
                    </div>

                    <div class="form-group">
                      <label for="p-nom">Nom <abbr title="champ obligatoire">*</abbr></label>
                      <input
                        type="text"
                        id="p-nom"
                        name="nom"
                        value="<?= htmlspecialchars($p_nom) ?>"
                        placeholder="Ton nom"
                        autocomplete="family-name"
                        class="<?= isset($p_erreurs['nom']) ? 'erreur' : '' ?>"
                      >
                      <?php if (isset($p_erreurs['nom'])) : ?>
                        <span class="champ-erreur"><?= htmlspecialchars($p_erreurs['nom']) ?></span>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="p-email">Adresse e-mail <abbr title="champ obligatoire">*</abbr></label>
                    <input
                      type="email"
                      id="p-email"
                      name="email"
                      value="<?= htmlspecialchars($p_email) ?>"
                      placeholder="ton@email.com"
                      autocomplete="email"
                      class="<?= isset($p_erreurs['email']) ? 'erreur' : '' ?>"
                    >
                    <?php if (isset($p_erreurs['email'])) : ?>
                      <span class="champ-erreur"><?= htmlspecialchars($p_erreurs['email']) ?></span>
                    <?php endif; ?>
                  </div>

                  <div class="form-group">
                    <label for="p-type">Type de projet <abbr title="champ obligatoire">*</abbr></label>
                    <select
                      id="p-type"
                      name="type"
                      class="<?= isset($p_erreurs['type']) ? 'erreur' : '' ?>"
                    >
                      <option value="" disabled <?= $p_type === '' ? 'selected' : '' ?>>
                        Sélectionne un type
                      </option>
                      <?php

                      $types = ['site-vitrine' => 'Site vitrine', 'portfolio' => 'Portfolio',
                                'e-commerce'   => 'Site Web',     'blog'      => 'Blog',
                                'autre'        => 'Autre'];
                      foreach ($types as $val => $libelle) : ?>
                        <option value="<?= $val ?>"
                          <?= $p_type === $val ? 'selected' : '' ?>>
                          <?= htmlspecialchars($libelle) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <?php if (isset($p_erreurs['type'])) : ?>
                      <span class="champ-erreur"><?= htmlspecialchars($p_erreurs['type']) ?></span>
                    <?php endif; ?>
                  </div>

                  <div class="form-group">
                    <label for="p-budget">Budget approximatif</label>
                    <select id="p-budget" name="budget">
                      <option value="" disabled <?= $p_budget === '' ? 'selected' : '' ?>>
                        Sélectionne une fourchette
                      </option>
                      <?php
                      $budgets = [
                          'moins-300000' => 'Moins de 300 000 FCFA',
                          '300-500k'     => '300 000 – 500 000 FCFA',
                          '500k-1m'      => '500 000 – 1 000 000 FCFA',
                          'plus-1m'      => 'Plus de 1 000 000 FCFA',
                          'a-definir'    => 'À définir ensemble',
                      ];
                      foreach ($budgets as $val => $libelle) : ?>
                        <option value="<?= $val ?>"
                          <?= $p_budget === $val ? 'selected' : '' ?>>
                          <?= htmlspecialchars($libelle) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="p-delai">Délai souhaité</label>
                    <input
                      type="text"
                      id="p-delai"
                      name="delai"
                      value="<?= htmlspecialchars($p_delai) ?>"
                      placeholder="Ex : dans 1 mois…"
                    >
                  </div>

                  <div class="form-group">
                    <label for="p-description">
                      Description du projet <abbr title="champ obligatoire">*</abbr>
                    </label>
                    <textarea
                      id="p-description"
                      name="description"
                      placeholder="Décris ton projet : objectif, public cible, fonctionnalités, inspirations visuelles…"
                      style="min-height:160px;"
                      class="<?= isset($p_erreurs['description']) ? 'erreur' : '' ?>"
                    ><?= htmlspecialchars($p_description) ?></textarea>
                    <?php if (isset($p_erreurs['description'])) : ?>
                      <span class="champ-erreur"><?= htmlspecialchars($p_erreurs['description']) ?></span>
                    <?php endif; ?>
                  </div>

                  <button type="submit" class="btn btn--primary" style="width:100%;">
                    Envoyer ma demande →
                  </button>

                  <p style="font-size:0.78rem; color:var(--muted); margin-top:0.75rem; text-align:center;">
                    Les champs marqués * sont obligatoires.
                  </p>

                </form>

              <?php endif; ?>

            </div>

          </div>


        </div>


      </div>
    </section>
  </main>

  <?php require 'composants/pied-de-page.php'; ?>

  <script>
    
    function basculerOnglet(id) {
      document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('actif'));
      document.querySelectorAll('.tab-link').forEach(b => b.classList.remove('actif'));

      document.getElementById('panneau-' + id).classList.add('actif');
      event.currentTarget.classList.add('actif');
    }
  </script>

</body>
</html>
