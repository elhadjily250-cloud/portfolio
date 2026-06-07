<?php
require '../../fonctions.php';
require '../../config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

verifier_admin();

$admins     = $pdo->query('SELECT id, prenom, nom, email, date_creation FROM administrateurs ORDER BY date_creation DESC')->fetchAll();
$titre_page = 'Administrateurs';
require '../composant-nav.php';
?>

  <div class="admin-entete">
    <h1>Administrateurs</h1>
    <a href="creer.php" class="btn btn--primary">+ Ajouter un admin</a>
  </div>

  <?php if (isset($_GET['succes'])) : ?>
    <div class="alerte alerte--succes"><?= htmlspecialchars($_GET['succes']) ?></div>
  <?php endif; ?>

  <div style="background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Créé le</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($admins as $admin) : ?>
          <tr>
            <td><strong><?= htmlspecialchars($admin['prenom'] . ' ' . $admin['nom']) ?></strong></td>
            <td><?= htmlspecialchars($admin['email']) ?></td>
            <td><?= date('d/m/Y', strtotime($admin['date_creation'])) ?></td>
            <td>
              <?php if ($admin['id'] !== (int) $_SESSION['admin_id']) : ?>
                <a href="supprimer.php?id=<?= $admin['id'] ?>"
                   class="btn btn--outline" style="padding:0.35rem 0.8rem; font-size:0.8rem; border-color:#e74c3c; color:#e74c3c;"
                   onclick="return confirm('Supprimer cet administrateur ?')">
                  Supprimer
                </a>
              <?php else : ?>
                <span style="font-size:0.8rem; color:var(--muted);">Compte actif</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</main>
</body>
</html>