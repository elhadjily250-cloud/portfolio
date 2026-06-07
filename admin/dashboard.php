<?php
require '../fonctions.php';
require '../config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

verifier_admin();

$nb_projets   = $pdo->query('SELECT COUNT(*) FROM projets')->fetchColumn();
$nb_messages  = $pdo->query('SELECT COUNT(*) FROM messages_contact')->fetchColumn();
$nb_non_lus   = $pdo->query('SELECT COUNT(*) FROM messages_contact WHERE lu = 0')->fetchColumn();
$nb_demandes  = $pdo->query('SELECT COUNT(*) FROM demandes_projet WHERE lu = 0')->fetchColumn();
$nb_visites   = $pdo->query('SELECT COUNT(*) FROM visites')->fetchColumn();

$dernieres_visites = $pdo->query(
    'SELECT * FROM visites ORDER BY date_visite DESC LIMIT 5'
)->fetchAll();

$titre_page = 'Dashboard';
require 'composant-nav.php';
?>

  <div class="admin-entete">
    <h1>Dashboard</h1>
    <span style="font-size:0.85rem; color:var(--muted);"><?= date('d/m/Y H:i') ?></span>
  </div>

  <div class="stat-grille">
    <div class="stat-carte">
      <div class="stat-carte__num"><?= $nb_projets ?></div>
      <div class="stat-carte__lbl">Projets publiés</div>
    </div>
    <div class="stat-carte">
      <div class="stat-carte__num"><?= $nb_messages ?></div>
      <div class="stat-carte__lbl">Messages reçus</div>
    </div>
    <div class="stat-carte">
      <div class="stat-carte__num" style="color:<?= $nb_non_lus > 0 ? '#ff6b35' : 'var(--accent)' ?>">
        <?= $nb_non_lus ?>
      </div>
      <div class="stat-carte__lbl">Messages non lus</div>
    </div>
    <div class="stat-carte">
      <div class="stat-carte__num" style="color:<?= $nb_demandes > 0 ? '#ff6b35' : 'var(--accent)' ?>">
        <?= $nb_demandes ?>
      </div>
      <div class="stat-carte__lbl">Demandes non lues</div>
    </div>
    <div class="stat-carte">
      <div class="stat-carte__num"><?= $nb_visites ?></div>
      <div class="stat-carte__lbl">Visites totales</div>
    </div>
  </div>

  <h2 style="font-size:1.1rem; margin-bottom:var(--sp-sm);">5 dernières visites</h2>
  <div style="background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>IP</th>
          <th>Page</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($dernieres_visites as $visite) : ?>
          <tr>
            <td><?= htmlspecialchars($visite['adresse_ip']) ?></td>
            <td><?= htmlspecialchars($visite['page']) ?></td>
            <td><?= htmlspecialchars($visite['date_visite']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</main>
</body>
</html>