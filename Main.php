<?php
declare(strict_types=1);

require_once './vendor/autoload.php';

use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\repository\DeefyRepository;
session_start();
DeefyRepository::setConfig(__DIR__ . '/src/classes/repository/db.config.ini');


$d = new Dispatcher();
$d->run();
