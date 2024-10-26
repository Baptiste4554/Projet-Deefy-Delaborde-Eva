<?php

namespace iutnc\deefy\repository;

use PDO;
use Exception;

class DeefyRepository {
    private PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf) {
        $this->pdo = new PDO($conf['dsn'], $conf['username'], $conf['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }

    public static function setConfig(string $file): void {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new Exception("Erreur lors de la lecture du fichier de configuration");
        }
        self::$config = [
            'dsn' => "mysql:host={$conf['host']};dbname={$conf['database']}",
            'username' => $conf['username'],
            'password' => $conf['password']
        ];
    }
    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            if (empty(self::$config)) {
                throw new Exception("Configuration non définie. Utilisez setConfig() en premier.");
            }
            self::$instance = new self(self::$config);
        }
        return self::$instance;
    }

    public function getAllPlaylists(): array {
        $stmt = $this->pdo->query("SELECT id, nom FROM playlist");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveEmptyPlaylist(string $nom): int {
        $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (:nom)");
        $stmt->execute(['nom' => $nom]);
        return (int)$this->pdo->lastInsertId();
    }

      public function saveTrack(string $titre, string $genre, int $duree, string $file): int {
        $stmt = $this->pdo->prepare("INSERT INTO track (titre, genre, duree, file) VALUES (:titre, :genre, :duree, :file)");
        $stmt->execute(['titre' => $titre, 'genre' => $genre, 'duree' => $duree, 'file' => $file]);
        return (int)$this->pdo->lastInsertId();
    }

      public function addTrackToPlaylist(int $playlistId, int $trackId): void {
        $stmt = $this->pdo->prepare("INSERT INTO playlist2track (pl_id, tr_id) VALUES (:playlistId, :trackId)");
        $stmt->execute(['playlistId' => $playlistId, 'trackId' => $trackId]);
    }
}
