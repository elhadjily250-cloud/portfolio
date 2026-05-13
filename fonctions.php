<?php

/**
 * Nettoie une valeur pour l'afficher sans risque dans du HTML.
 * Supprime les espaces inutiles et échappe les caractères spéciaux.
 *
 * @param string $valeur  La valeur brute provenant d'un formulaire
 * @return string         La valeur nettoyée, prête à être affichée
 */
function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur));
}

/**
 * Vérifie qu'un champ n'est pas vide après suppression des espaces.
 *
 * @param string $valeur  La valeur à vérifier
 * @return bool           true si le champ est valide, false sinon
 */
function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}

/**
 * Vérifie que l'adresse e-mail a un format valide.
 *
 * @param string $email  L'adresse e-mail à valider
 * @return bool          true si l'email est valide, false sinon
 */
function email_valide(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Récupère le nom du fichier PHP en cours d'exécution.
 * Utilisé pour mettre en surbrillance le lien actif dans la navigation.
 *
 * @return string  Le nom du fichier courant (ex: "index.php")
 */
function page_courante(): string {
    return basename($_SERVER['PHP_SELF']);
}

/**
 * Retourne la chaîne 'class="active"' si la page fournie est la page courante.
 * Permet de mettre en surbrillance le lien de navigation actif.
 *
 * @param string $page  Le nom du fichier à tester (ex: "projets.php")
 * @return string       La chaîne 'class="active"' ou une chaîne vide
 */
function lien_actif(string $page): string {
    return page_courante() === $page ? 'class="active"' : '';
}

/**
 * Retourne tous les projets du portfolio sous forme de tableau associatif.
 * Chaque projet contient : titre, description, technologies, image, lien.
 * Ce tableau remplace les cartes HTML codées en dur — il suffit d'ajouter
 * une entrée ici pour qu'un nouveau projet apparaisse automatiquement.
 *
 * @return array  Tableau indexé de projets associatifs
 */
function obtenir_projets(): array {
    return [
        [
            'titre'        => 'Générateur de fiche d\'identité dynamique en PHP',
            'description'  => 'Générateur dynamique de fiches d\'identité utilisant les fondamentaux de PHP (variables, concaténation et logique conditionnelle).',
            'technologies' => ['HTML', 'CSS', 'PHP'],
            'image'        => 'image-identite.jpg',
            'emoji'        => '📄',
            'lien'         => '#',
        ],
        
        [
            'titre'        => 'Site Restaurant',
            'description'  => 'Conception et développement d\'un site vitrine dédié à un restaurant fictif. Interface moderne, responsive et intuitive avec menu et galerie.',
            'technologies' => ['HTML', 'CSS'],
            'image'        => 'image-restaurant.jpg',
            'emoji'        => '🍽️',
            'lien'         => '#',
        ],
        [
            'titre'        => 'Poubelle intelligente basée sur ESP32',
            'description'  => 'Conception d\'une poubelle intelligente avec détection de présence, capteur DHT11, écran LCD I2C et interface web connectée en Wi-Fi.',
            'technologies' => ['HTML', 'CSS', 'ESP32'],
            'image'        => 'image-poubelle.jpg',
            'emoji'        => '♻️',
            'lien'         => '#',
        ],
        [
            'titre'        => 'Portfolio personnel',
            'description'  => 'Ce portfolio — site multipage responsive conçu en HTML, CSS et PHP. Animations CSS, design minimaliste et formulaires de contact fonctionnels.',
            'technologies' => ['HTML', 'CSS', 'PHP'],
            'image'        => 'image-portfolio.jpg',
            'emoji'        => '🖥️',
            'lien'         => '#',
        ],
        [
            'titre'        => 'CV en ligne',
            'description'  => 'CV numérique interactif en HTML et CSS pur. Mise en page print-ready et version téléchargeable.',
            'technologies' => ['HTML', 'CSS'],
            'image'        => 'image-cv.jpg',
            'emoji'        => '📋',
            'lien'         => '#',
        ],
        
    ];
}
