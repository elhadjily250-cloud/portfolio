<?php
require '../../fonctions.php';
require '../../config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

verifier_admin();

if (isset($_GET['lire'])) {
    $stmt = $pdo->prepare('UPDATE messages_contact SET lu = 1 WHERE id = ?');
    $stmt->execute([(int) $_GET['lire']]);
    header('Location: index.php');
    exit;
}

$messages   = $pdo->query('SELECT * FROM messages_contact ORDER BY date_envoi DESC')->fetchAll();
$titre_page = 'Messages contact';
require '../composant-nav.php';
?>

  <div class="admin-entete">
    <h1>Messages de contact</h1>
    <span style="font-size:0.85rem; color:var(--muted);"><?= count($messages) ?> message<?= count($messages) > 1 ? 's' : '' ?></span>
  </div>

  <div style="background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Message</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($messages)) : ?>
          <tr>
            <td colspan="6" style="text-align:center; color:var(--muted); padding:2rem;">
              Aucun message pour l'instant.
            </td>
          </tr>
        <?php else : ?>
          <?php foreach ($messages as $msg) : ?>
            <tr>
              <td><strong><?= htmlspecialchars($msg['nom']) ?></strong></td>
              <td>
                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" style="color:var(--accent);">
                  <?= htmlspecialchars($msg['email']) ?>
                </a>
              </td>
              <td style="max-width:280px;">
                <p style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:280px;">
                  <?= htmlspecialchars($msg['message']) ?>
                </p>
              </td>
              <td><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></td>
              <td>
                <?php if ($msg['lu']) : ?>
                  <span class="badge-lu">Lu</span>
                <?php else : ?>
                  <span class="badge-nonlu">Non lu</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!$msg['lu']) : ?>
                  <a href="index.php?lire=<?= $msg['id'] ?>" class="btn btn--outline" style="padding:0.3rem 0.7rem; font-size:0.78rem;">
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