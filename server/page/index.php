<?php
require_once __DIR__.'/../api.php';

$echo = '[';

$page = $_GET['page'] - 1;

if ($_GET['type'] == 0) {
    switch ($_GET['sort']) {
        case 0: 
            $levels = Levels::getRecent($page);
            break;
        case 1: 
            $levels = Levels::getOldest($page);
            break;
        case 2: 
            $levels = Levels::getMostPlays($page);
            break;
    }

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
}

if ($_GET['type'] == 1) {
    switch ($_GET['sort']) {
        case 0: 
            $packs = Packs::getRecent($page);
            break;
        case 1: 
            $packs = Packs::getOldest($page);
            break;
        case 2: 
            $packs = Packs::getMostPlays($page);
            break;
    }

    $f = true;
    foreach ($packs as $pack) {
        $echo .=  $f ? '' : ',';
        $f = false;
        $echo .= $pack->render();
    }
    $echo .= ']';
    
    echo $echo;

    $page = null;
    $echo = null;
    $packs = null;
    $pack = null;
    $f = null;
}