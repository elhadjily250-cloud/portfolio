<?php
require '../../fonctions.php';
require '../../config/connexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

verifier_admin();

$id = (int) ($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM projets WHERE id = ?');
$stmt->execute([$id]);

header('Location: index.php?succes=' . urlencode('Projet supprimé avec succès.'));
exit;