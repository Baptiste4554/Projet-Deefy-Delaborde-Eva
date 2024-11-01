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

    public function saveTrack($titre, $genre, $duree, $filename, $auteur, $date): int {
        $query = "INSERT INTO track (titre, genre, duree, filename, auteur_podcast, date_posdcast) 
                  VALUES (:titre, :genre, :duree, :filename, :auteur, :date)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':duree', $duree);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':auteur', $auteur);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return (int)$this->pdo->lastInsertId();
    }
    

    public function addTrackToPlaylist(int $playlistId, int $trackId): void {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM playlist2track WHERE id_pl = :playlistId");
        $stmt->execute(['playlistId' => $playlistId]);
        $compteur = $stmt->fetchColumn();
        $noPisteDansListe = $compteur + 1;
        $stmt = $this->pdo->prepare("
            INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste)  VALUES (:playlistId, :trackId, :noPisteDansListe)");
        $stmt->execute(['playlistId' => $playlistId,'trackId' => $trackId,'noPisteDansListe' => $noPisteDansListe]);
    }

    public function addUserToPlaylist(int $userId, int $playlistId): void {
        $stmt = $this->getPDO()->prepare("INSERT INTO user2playlist (id_user, id_pl) VALUES (:id_user, :id_pl)");
        $stmt->execute(['id_user' => $userId,'id_pl' => $playlistId]);
    }
    


    public function getPDO(): PDO {
        return $this->pdo;
    }
}
