<?php
require_once __DIR__.'/../api.php';

if ($_GET['type'] == 0)
    echo Levels::playLevel($_GET['id'], $_SERVER['REMOTE_ADDR']);

if ($_GET['type'] == 1)
    echo Packs::playPack($_GET['id'], $_SERVER['REMOTE_ADDR']);