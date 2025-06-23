<?php
require_once __DIR__.'/../api.php';

$echo = '';

$level = Levels::getById($_GET['id']);

$echo .= $level->render(true);

echo $echo;

$echo = null;
$pack = null;