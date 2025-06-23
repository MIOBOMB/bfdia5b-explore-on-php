<?php
require_once __DIR__.'/../api.php';

$echo = '';

$pack = Packs::getById($_GET['id']);

$echo .= $pack->render(true);

echo $echo;

$echo = null;
$pack = null;