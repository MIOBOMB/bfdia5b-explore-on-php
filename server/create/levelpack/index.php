<?php
require_once __DIR__.'/../../api.php';

$user = Users::getUserByToken($_POST['access_token']);

$preFile = explode("\r\n\r\n", $_POST['file']);

$levelIds = '[';

$f = true;
foreach ($preFile as $file) {
    $title = stristr($file, "\r\n", true);
    $title = str_replace('\\n', '', $title);
    $file = str_replace("\r\n", "\\n", $file);

    $check = Levels::uploadLevelForPack($title, $user->ID, $file, date("o-m-j",time()));
    if ($check !== false) 
        if ($f == false)
            $levelIds .= ',"'.$check.'"';
        else
            $levelIds .= '"'.$check.'"';
    $f = false;
}
$levelIds .= ']';

echo Packs::uploadPack($_POST['title'], $user->ID, $levelIds, $_POST['description'], date("o-m-j",time()));