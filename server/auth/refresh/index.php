<?php
require_once __DIR__.'/../../api.php';

$user = Users::getUserByToken($_POST['refresh_token']);

if ($user != null)
    exit('200');
else
    exit('400');