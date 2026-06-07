<?php

/**
 * Nettoie une valeur pour l'afficher sans risque dans du HTML.
 * @param string $valeur
 * @return string
 */
function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur));
}

/**
 * Vérifie qu'un champ n'est pas vide après suppression des espaces.
 * @param string $valeur
 * @return bool
 */
function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}

/**
 * Vérifie que l'adresse e-mail a un format valide.
 * @param string $email
 * @return bool
 */
function email_valide(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Récupère le nom du fichier PHP en cours d'exécution.
 * @return string
 */
function page_courante(): string {
    return basename($_SERVER['PHP_SELF']);
}

/**
 * Retourne class="active" si la page correspond à la page courante.
 * @param string $page
 * @return string
 */
function lien_actif(string $page): string {
    return page_courante() === $page ? 'class="active"' : '';
}

/**
 * Génère un jeton CSRF et le stocke en session.
 * Le jeton protège les formulaires contre les attaques cross-site.
 * @return string
 */
function generer_jeton_csrf(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['jeton_csrf'])) {
        $_SESSION['jeton_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['jeton_csrf'];
}

/**
 * Vérifie que le jeton CSRF soumis correspond à celui en session.
 * Arrête l'exécution si le jeton est invalide ou absent.
 * @return void
 */
function verifier_jeton_csrf(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $jeton_soumis  = $_POST['jeton_csrf'] ?? '';
    $jeton_session = $_SESSION['jeton_csrf'] ?? '';

    if (!hash_equals($jeton_session, $jeton_soumis)) {
        http_response_code(403);
        die('Requête invalide. Veuillez recharger la page et réessayer.');
    }

    unset($_SESSION['jeton_csrf']);
}

/**
 * Enregistre la visite courante dans la table visites.
 * Appelée en haut de chaque page publique.
 * @param PDO $pdo
 * @return void
 */
function enregistrer_visite(PDO $pdo): void {
    $ip   = $_SERVER['REMOTE_ADDR'] ?? 'inconnue';
    $page = $_SERVER['REQUEST_URI'] ?? '/';

    $stmt = $pdo->prepare(
        'INSERT INTO visites (adresse_ip, page) VALUES (?, ?)'
    );
    $stmt->execute([$ip, $page]);
}

/**
 * Vérifie que l'administrateur est connecté.
 * Redirige vers la page de connexion si ce n'est pas le cas.
 * @return void
 */
function verifier_admin(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_id'])) {
        header('Location: /admin/connexion.php');
        exit;
    }
}

/**
 * Récupère tous les projets depuis la base de données.
 * @param PDO $pdo
 * @return array
 */
function obtenir_projets(PDO $pdo): array {
    $stmt = $pdo->query('SELECT * FROM projets ORDER BY date_creation DESC');
    return $stmt->fetchAll();
}

/**
 * Recherche les projets dont le titre ou la description contient le mot-clé.
 * Utilise une requête préparée avec LIKE pour éviter les injections SQL.
 * @param PDO $pdo
 * @param string $mot_cle
 * @return array
 */
function rechercher_projets(PDO $pdo, string $mot_cle): array {
    $terme = '%' . $mot_cle . '%';
    $stmt  = $pdo->prepare(
        'SELECT * FROM projets
         WHERE titre LIKE ? OR description LIKE ?
         ORDER BY date_creation DESC'
    );
    $stmt->execute([$terme, $terme]);
    return $stmt->fetchAll();
}

/**
 * Insère un message de contact dans la base de données.
 * @param PDO $pdo
 * @param string $nom
 * @param string $email
 * @param string $message
 * @return void
 */
function sauvegarder_message(PDO $pdo, string $nom, string $email, string $message): void {
    $stmt = $pdo->prepare(
        'INSERT INTO messages_contact (nom, email, message) VALUES (?, ?, ?)'
    );
    $stmt->execute([$nom, $email, $message]);
}

/**
 * Insère une demande de projet dans la base de données.
 * @param PDO $pdo
 * @param string $nom
 * @param string $email
 * @param string $type_projet
 * @param string $description
 * @param string $budget
 * @return void
 */
function sauvegarder_demande(PDO $pdo, string $nom, string $email, string $type_projet, string $description, string $budget): void {
    $stmt = $pdo->prepare(
        'INSERT INTO demandes_projet (nom, email, type_projet, description, budget)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$nom, $email, $type_projet, $description, $budget]);
}
