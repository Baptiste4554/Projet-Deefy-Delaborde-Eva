<?php

namespace iutnc\deefy\repository;

use PDO;
use PDOException;

class DeefyRepository {
  
    private static array $config = []; // Tableau de configuration
    private static ?DeefyRepository $instance = null; // Instance unique de la classe
    private PDO $pdo; // Connexion PDO

    /**
     * Méthode pour définir la configuration de la base de données
     *
     * @param string $file Chemin du fichier .ini contenant la configuration
     */
    public static function setConfig(string $file): void {
        if (file_exists($file)) {
            self::$config = parse_ini_file($file);
        } else {
            throw new \InvalidArgumentException("Le fichier de configuration {$file} est introuvable.");
        }
    }

    /**
     * Méthode pour obtenir l'instance unique de DeefyRepository
     *
     * @return DeefyRepository Instance unique de la classe
     */
    public static function getInstance(): DeefyRepository {
        if (self::$instance === null) {
            self::$instance = new self(); // Créer l'instance
        }
        return self::$instance;
    }

    /**
     * Constructeur privé pour initialiser la connexion PDO
     */
    private function __construct() {
        if (empty(self::$config)) {
            throw new \RuntimeException("La configuration de la base de données est absente.");
        }

        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8",
                self::$config['host'],
                self::$config['dbname']
            );
            $this->pdo = new PDO($dsn, self::$config['username'], self::$config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new \RuntimeException("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Exemple de méthode pour trouver une playlist par ID
     *
     * @param int $id Identifiant de la playlist
     * @return array|false Les données de la playlist ou false si non trouvée
     */
    public function findPlaylistById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM playlist WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
