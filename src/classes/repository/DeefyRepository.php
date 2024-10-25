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

        $host = 'localhost';
        $db = 'deefy_musique';
        $user = 'root';
        $pass = ''; 
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur connexion : " . $e->getMessage();
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

    public function getAllPlaylists(): array {
        $stmt = $this->pdo->query("SELECT id, nom FROM playlist");
        $playlists = [];
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $playlists[] = new \iutnc\deefy\model\Playlist($row['id'], $row['nom']);
        }
    
        return $playlists;
    }

    public function saveEmptyPlaylist(string $nom): bool {
        $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (:nom)");
        return $stmt->execute(['nom' => $nom]);
    }

    public function saveTrack(\iutnc\deefy\model\Track $track): bool {
        $stmt = $this->pdo->prepare("INSERT INTO track (titre, genre, duree, filename) VALUES (:titre, :genre, :duree, :filename)");
        return $stmt->execute([
            'titre' => $track->titre,
            'genre' => $track->genre,
            'duree' => $track->duree,
            'filename' => $track->filename
        ]);
    }

    public function addTrackToPlaylist(int $idTrack, int $idPlaylist): bool {
        $stmt = $this->pdo->prepare("INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) 
                                     VALUES (:id_pl, :id_track, 
                                     (SELECT COALESCE(MAX(no_piste_dans_liste) + 1, 1) 
                                      FROM playlist2track WHERE id_pl = :id_pl))");
        return $stmt->execute(['id_pl' => $idPlaylist, 'id_track' => $idTrack]);
    }
    
    
}
