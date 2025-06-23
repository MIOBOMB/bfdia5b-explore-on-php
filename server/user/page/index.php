<?php
require_once __DIR__.'/../../api.php';

$page = $_GET['page'] - 1;

$echo = '[';

if ($_GET['type'] == 0)
    $levels = Levels::getProfile($page, $_GET['id']);
else
    $levels = Packs::getProfile($page, $_GET['id']);

$f = true;
foreach ($levels as $level) {
    $echo .=  $f ? '' : ',';
    $f = false;
    $echo .= $level->render();
}

$echo .= ']';

echo $echo;

$echo = null;
$levels = null;
$f = null;