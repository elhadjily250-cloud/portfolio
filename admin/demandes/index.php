<?php
require '../../fonctions.php';
require '../../config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

verifier_admin();

if (isset($_GET['lire'])) {
    $stmt = $pdo->prepare('UPDATE demandes_projet SET lu = 1 WHERE id = ?');
    $stmt->execute([(int) $_GET['lire']]);
    header('Location: index.php');
    exit;
}

$demandes   = $pdo->query('SELECT * FROM demandes_projet ORDER BY date_demande DESC')->fetchAll();
$titre_page = 'Demandes de projet';
require '../composant-nav.php';
?>

  <div class="admin-entete">
    <h1>Demandes de projet</h1>
    <span style="font-size:0.85rem; color:var(--muted);"><?= count($demandes) ?> demande<?= count($demandes) > 1 ? 's' : '' ?></span>
  </div>

  <div style="background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Type</th>
          <th>Budget</th>
          <th>Description</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($demandes)) : ?>
          <tr>
            <td colspan="8" style="text-align:center; color:var(--muted); padding:2rem;">
              Aucune demande pour l'instant.
            </td>
          </tr>
        <?php else : ?>
          <?php foreach ($demandes as $dem) : ?>
            <tr>
              <td><strong><?= htmlspecialchars($dem['nom']) ?></strong></td>
              <td>
                <a href="mailto:<?= htmlspecialchars($dem['email']) ?>" style="color:var(--accent);">
                  <?= htmlspecialchars($dem['email']) ?>
                </a>
              </td>
              <td><?= htmlspecialchars($dem['type_projet']) ?></td>
              <td><?= htmlspecialchars($dem['budget'] ?? '—') ?></td>
              <td style="max-width:200px;">
                <p style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:200px;">
                  <?= htmlspecialchars($dem['description']) ?>
                </p>
              </td>
              <td><?= date('d/m/Y H:i', strtotime($dem['date_demande'])) ?></td>
              <td>
                <?php if ($dem['lu']) : ?>
                  <span class="badge-lu">Lu</span>
                <?php else : ?>
                  <span class="badge-nonlu">Non lu</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!$dem['lu']) : ?>
                  <a href="index.php?lire=<?= $dem['id'] ?>" class="btn btn--outline" style="padding:0.3rem 0.7rem; font-size:0.78rem;">
                    Marquer lu
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</main>
</body>
</html>