<?php
$page_admin = basename($_SERVER['PHP_SELF']);
$dossier_admin = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $titre_page ?? 'Administration' ?> — [El.LY]</title>
  <link rel="stylesheet" href="/Portfolio/style.css" />
  <style>
    body { display: flex; min-height: 100vh; }

    .admin-sidebar {
      width: 240px;
      background: var(--surface);
      border-right: 1px solid var(--border);
      padding: var(--sp-md);
      display: flex;
      flex-direction: column;
      gap: var(--sp-xs);
      flex-shrink: 0;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
    }

    .admin-sidebar__logo {
      font-family: var(--font-display);
      font-size: 1.3rem;
      margin-bottom: var(--sp-sm);
      padding-bottom: var(--sp-sm);
      border-bottom: 1px solid var(--border);
    }
    .admin-sidebar__logo span { color: var(--accent); }

    .admin-sidebar__section {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--muted);
      margin-top: var(--sp-sm);
      margin-bottom: 0.25rem;
    }

    .admin-nav__lien {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.55rem 0.75rem;
      border-radius: var(--radius);
      font-size: 0.88rem;
      color: var(--muted);
      transition: var(--trans);
    }
    .admin-nav__lien:hover { background: var(--tag-bg); color: var(--text); }
    .admin-nav__lien.actif { background: var(--accent-lt); color: var(--accent); font-weight: 600; }

    .admin-sidebar__deconnexion {
      margin-top: auto;
      padding-top: var(--sp-sm);
      border-top: 1px solid var(--border);
    }

    .admin-contenu {
      flex: 1;
      padding: var(--sp-md);
      overflow-y: auto;
    }

    .admin-entete {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: var(--sp-md);
      padding-bottom: var(--sp-sm);
      border-bottom: 1px solid var(--border);
    }

    .admin-entete h1 {
      font-size: clamp(1.4rem, 3vw, 2rem);
    }

    .admin-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.9rem;
    }
    .admin-table th {
      text-align: left;
      padding: 0.65rem 1rem;
      background: var(--surface);
      border-bottom: 2px solid var(--border);
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--muted);
    }
    .admin-table td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }
    .admin-table tr:hover td { background: var(--tag-bg); }

    .badge-lu   { background: var(--tag-bg); color: var(--muted); font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 100px; }
    .badge-nonlu { background: rgba(0,212,255,0.1); color: var(--accent); font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 100px; font-weight: 600; }

    .stat-grille { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: var(--sp-sm); margin-bottom: var(--sp-md); }
    .stat-carte { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: var(--sp-md); }
    .stat-carte__num { font-family: var(--font-display); font-size: 2.5rem; color: var(--accent); line-height: 1; }
    .stat-carte__lbl { font-size: 0.82rem; color: var(--muted); margin-top: 0.25rem; }

    .alerte { border-radius: var(--radius); padding: 0.85rem 1rem; margin-bottom: var(--sp-sm); font-size: 0.88rem; }
    .alerte--succes { background: #eafaf1; border: 1px solid #a3e4c1; color: #1e7e47; }
    .alerte--erreur { background: #fdf3f2; border: 1px solid #f0b8b8; color: #c0392b; }
    .champ-erreur { font-size: 0.8rem; color: #e74c3c; margin-top: 0.25rem; display: block; }
    .form-group input.erreur,
    .form-group textarea.erreur,
    .form-group select.erreur { border-color: #e74c3c; }
  </style>
</head>
<body>

<aside class="admin-sidebar">
  <div class="admin-sidebar__logo">[El<span>.</span>LY] <span style="font-size:0.7rem; color:var(--muted); font-family:var(--font-body);">Admin</span></div>

  <span class="admin-sidebar__section">Tableau de bord</span>
  <a href="../admin/dashboard.php" class="admin-nav__lien <?= $page_admin === 'dashboard.php' ? 'actif' : '' ?>">
    📊 Dashboard
  </a>

  <span class="admin-sidebar__section">Contenu</span>
  <a href="../admin/projets/index.php" class="admin-nav__lien <?= $dossier_admin === 'projets' ? 'actif' : '' ?>">
    🖥️ Projets
  </a>

  <span class="admin-sidebar__section">Messages</span>
  <a href="../admin/messages/index.php" class="admin-nav__lien <?= $dossier_admin === 'messages' ? 'actif' : '' ?>">
    ✉️ Messages contact
  </a>
  <a href="../admin/demandes/index.php" class="admin-nav__lien <?= $dossier_admin === 'demandes' ? 'actif' : '' ?>">
    💼 Demandes projet
  </a>

  <span class="admin-sidebar__section">Paramètres</span>
  <a href="../admin/utilisateurs/index.php" class="admin-nav__lien <?= $dossier_admin === 'utilisateurs' ? 'actif' : '' ?>">
    👤 Administrateurs
  </a>

  <div class="admin-sidebar__deconnexion">
    <p style="font-size:0.8rem; color:var(--muted); margin-bottom:0.5rem;">
      Connecté : <strong><?= htmlspecialchars($_SESSION['admin_prenom'] ?? '') ?></strong>
    </p>
    <a href="../admin/deconnexion.php" class="btn btn--outline" style="width:100%; justify-content:center; font-size:0.82rem; padding:0.5rem;">
      Se déconnecter
    </a>
    <a href="../index.php" style="display:block; text-align:center; font-size:0.78rem; color:var(--muted); margin-top:0.5rem;">
      ← Voir le site
    </a>
  </div>
</aside>

<main class="admin-contenu">