<?php
declare(strict_types=1);

require_once './vendor/autoload.php';

use iutnc\deefy\dispatch\Dispatcher;

session_start();

$d = new Dispatcher();
$d->run();