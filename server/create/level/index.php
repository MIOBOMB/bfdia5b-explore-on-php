<?php
require_once __DIR__.'/../../api.php';

$user = Users::getUserByToken($_POST['access_token']);

$file = str_replace("\r\n", "\\n", $_POST['file']);

echo Levels::uploadLevel($_POST['title'], $user->ID, $file, $_POST['description'], date("o-m-j",time()));