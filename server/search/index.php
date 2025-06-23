<?php
require_once __DIR__.'/../api.php';

$echo = '[';

$levels = Levels::getByName($_GET['text'], $_GET['page']);

$f = true;
foreach ($levels as $level) {
    $echo .=  $f ? '' : ',';
    $f = false;
    $echo .= $level->render();
}
$echo .= ']';
    
echo $echo;

$page = null;
$echo = null;
$levels = null;
$level = null;
$f = null;