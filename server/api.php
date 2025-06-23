<?php

//server settings
$serverLogin = 'serverLogin'; // once you need to rename this variable in "5b.js", else login and register won't work
$serverHost = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; // uses for login without reload page

//error_reporting(E_ALL);

// ---------- captcha block

$registercaptcha = false; // hCaptcha on register, recommended "true"
$logincaptcha = false; // hCaptcha on login

// if one of the values above is true, fill variables below by docs https://docs.hcaptcha.com/
$hCaptchaSiteKey = '';
$hCaptchaSecretKey = '';

// ---------- endblock

// connection to database settings
$DBhost = 'localhost';
$DBport = 3306;
$DBdatabase = 'bfdia';
$DBusername = 'bfdia';
$DBpassword = 'somePasswordHere';
try {
    $conn = new PDO("mysql:host=$DBhost;port=$DBport;dbname=$DBdatabase", "$DBusername", "$DBpassword", array(
            PDO::ATTR_PERSISTENT => true
    ));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit(__DIR__."\\connection.php | Connection error: " . $e->getMessage());
}

class Levels {
    public int $ID;
    public string $title;
    public int $authorId;
    public string $levelData;
    public string $description;
    public int $difficulty;
    public int $featured;
    public int $plays;
    public string $thumbnail;
    public int $unlisted;
    public string $createDate;
    public string $updated;

    public static function getRecent(int $page) {
        global $conn;
        $page2 = $page * 8;
        $levels = $conn->prepare("SELECT * FROM `levels` WHERE `unlisted` = 0 ORDER BY `ID` DESC LIMIT 8 OFFSET $page2");
        $levels->execute();
        return $levels->fetchAll(PDO::FETCH_CLASS, 'Levels');
    }

    public static function getProfile(int $page, int $authorId) {
        global $conn;
        $page2 = $page * 4;
        $levels = $conn->prepare("SELECT * FROM `levels` WHERE `unlisted` = 0 AND `authorId` = ? ORDER BY `ID` DESC LIMIT 4 OFFSET $page2");
        $levels->execute([$authorId]);
        return $levels->fetchAll(PDO::FETCH_CLASS, Levels::class);
    }

    public static function getOldest(int $page) {
        global $conn;
        $page2 = $page * 8;
        $levels = $conn->prepare("SELECT * FROM `levels` WHERE `unlisted` = 0 ORDER BY `ID` ASC LIMIT 8 OFFSET $page2");
        $levels->execute();
        return $levels->fetchAll(PDO::FETCH_CLASS, Levels::class);
    }

    public static function getMostPlays(int $page) {
        global $conn;
        $page2 = $page * 8;
        $levels = $conn->prepare("SELECT * FROM `levels` WHERE `unlisted` = 0 ORDER BY `plays` DESC LIMIT 8 OFFSET $page2");
        $levels->execute();
        return $levels->fetchAll(PDO::FETCH_CLASS, Levels::class);
    }

    public static function getById($ID) {
        global $conn;
        $levels = $conn->prepare("SELECT * FROM `levels` WHERE `ID` = ?");
        $levels->execute([$ID]);
        return $levels->fetchObject(Levels::class);
    }

    public static function getByName($name, $page) {
        global $conn;
        $page2 = $page * 8;
        $levels = $conn->prepare("SELECT * FROM `levels` WHERE `title` LIKE ? ORDER BY `plays` DESC LIMIT 8 OFFSET $page2");
        $levels->execute(['%'.$name.'%']);
        return $levels->fetchAll(PDO::FETCH_CLASS, Levels::class);
    }

    public static function playLevel(int $ID, $IP) {
        global $conn;
        $z = $conn->prepare('SELECT * FROM `level play` WHERE `IP` = ? AND `levelId` = ? LIMIT 1');
        $z->execute([$IP, $ID]);
        $resp = $z->fetchAll(PDO::FETCH_ASSOC);
        if (empty($resp)) {
            $z = $conn->prepare('INSERT INTO `level play` (`IP`, `levelId`) VALUES (?, ?)');
            $z->execute([$IP, $ID]);
            $z = $conn->prepare('UPDATE `levels` SET `plays` = `plays` + 1 WHERE `ID` = ?');
            $z->execute([$ID]);
            return 1;
        }   else 
            return 0;
    }

    public static function uploadLevel(string $title, int $authorId, string $levelData, string $description, string $uploadtime) {
        global $conn;
        $description = $description ? $description : 'none';
        $z = $conn->prepare('INSERT INTO `levels` (`title`, `authorId`, `levelData`, `createDate`, `description`) VALUES (?, ?, ?, ?, ?)');
        $z->execute([$title, $authorId, $levelData, $uploadtime, $description]);
        //return $conn->lastInsertId();
        return 1;
    }

    public static function uploadLevelForPack(string $title, int $authorId, string $levelData, string $uploadtime) {
        global $conn;
        $z = $conn->prepare('INSERT INTO `levels` (`title`, `authorId`, `levelData`, `createDate`, `unlisted`) VALUES (?, ?, ?, ?, 1)');
        $z->execute([$title, $authorId, $levelData, $uploadtime]);
        return $conn->lastInsertId();
    }

    public function render() {
        $user = Users::getUserById($this->authorId);
        $creator = $user->render();
        $featured = $this->featured ? 'true' : 'false';
        $unlisted = $this->unlisted ? 'true' : 'false';

        $html = "\n".'{'.
            '"created":"'.$this->createDate.'",'.
            '"creator":'.$creator.','.
            '"data":"'.$this->levelData.'",'.
            '"description":"'.$this->description.'",'.
            '"difficulty":'.$this->difficulty.','.
            '"featured":'.$featured.','.
            '"id":"'.$this->ID.'",'.
            '"plays":'.$this->plays.','.
            '"thumbnail":"'.$this->thumbnail.'",'.
            '"title":"'.$this->title.'",'.
            '"unlisted":'.$unlisted.','.
            '"updated":"'.$this->updated.'"'.
        '}';
        return $html;
    }
}

class Packs {
    public int $ID;
    public string $title;
    public string $description;
    public int $authorId;
    public string $levels;
    public int $featured;
    public int $plays;
    public int $stars;
    public string $createDate;
    public string $updated;

    public static function getRecent(int $page) {
        global $conn;
        $page2 = $page * 8;
        $levels = $conn->prepare("SELECT * FROM `packs` ORDER BY `ID` DESC LIMIT 8 OFFSET $page2");
        $levels->execute();
        return $levels->fetchAll(PDO::FETCH_CLASS, Packs::class);
    }

    public static function getProfile(int $page, int $authorId) {
        global $conn;
        $page2 = $page * 4;
        $levels = $conn->prepare("SELECT * FROM `packs` WHERE `authorId` = ? ORDER BY `ID` DESC LIMIT 4 OFFSET $page2");
        $levels->execute([$authorId]);
        return $levels->fetchAll(PDO::FETCH_CLASS, Packs::class);
    }

    public static function getOldest(int $page) {
        global $conn;
        $page2 = $page * 8;
        $levels = $conn->prepare("SELECT * FROM `packs` ORDER BY `ID` ASC LIMIT 8 OFFSET $page2");
        $levels->execute();
        return $levels->fetchAll(PDO::FETCH_CLASS, Packs::class);
    }

    public static function getMostPlays(int $page) {
        global $conn;
        $page2 = $page * 8;
        $levels = $conn->prepare("SELECT * FROM `packs` ORDER BY `plays` DESC LIMIT 8 OFFSET $page2");
        $levels->execute();
        return $levels->fetchAll(PDO::FETCH_CLASS, Packs::class);
    }

    public static function getById($ID) {
        global $conn;
        $levels = $conn->prepare("SELECT * FROM `packs` WHERE `ID` = ?");
        $levels->execute([$ID]);
        return $levels->fetchObject(Packs::class);
    }

    public static function playPack(int $ID, $IP) {
        global $conn;
        $z = $conn->prepare('SELECT * FROM `pack play` WHERE `IP` = ? AND `packId` = ? LIMIT 1');
        $z->execute([$IP, $ID]);
        $resp = $z->fetchAll(PDO::FETCH_ASSOC);
        if (empty($resp)) {
            $z = $conn->prepare('INSERT INTO `pack play` (`IP`, `packId`) VALUES (?, ?)');
            $z->execute([$IP, $ID]);
            $z = $conn->prepare('UPDATE `packs` SET `plays` = `plays` + 1 WHERE `ID` = ?');
            $z->execute([$ID]);
            return 1;
        }   else 
            return 0;
    }

    public static function uploadPack(string $title, int $authorId, string $levels, string $description, string $uploadtime) {
        global $conn;
        $description = $description ? $description : 'none';
        $z = $conn->prepare('INSERT INTO `packs` (`title`, `authorId`, `levels`, `createDate`, `description`) VALUES (?, ?, ?, ?, ?)');
        $z->execute([$title, $authorId, $levels, $uploadtime, $description]);
        //return $conn->lastInsertId();
        return 1;
    }

    public function render($fullLevels = false) {
        $user = Users::getUserById($this->authorId);
        $creator = $user->render();
        $featured = $this->featured ? 'true' : 'false';

        $levels = '[';
        if ($fullLevels == true) {
            $levelIds = json_decode($this->levels);
            $f = true;
            foreach ($levelIds as $levelId) {
                $level = Levels::getById($levelId);
                $levels .= $f ? '' : ',';
                $f = false;
                $levels .= $level->render();
            }
            $levels .= ']';
        } else {
            $levels = $this->levels;
        }

        $html = "\n".'{'.
            '"created":"'.$this->createDate.'",'.
            '"creator":'.$creator.','.
            '"description":"'.$this->description.'",'.
            '"featured":'.$featured.','.
            '"id":"'.$this->ID.'",'.
            '"levels":'.$levels.','.
            '"plays":'.$this->plays.','.
            '"title":"'.$this->title.'",'.
            '"updated":"'.$this->updated.'"'.
        '}';
        return $html;
    }
}

class Users {
    public int $ID;
    public string $username;
    public string $password;
    public int $isActive;
    public int $regDate;
    public string $token;

    public function verifyPassword(string $password) {
        return password_verify($password, $this->password);
    }
    
    public static function hasUsed(string $username) {
        global $conn;
        $user = $conn->prepare('SELECT * FROM `users` WHERE `username` = ? LIMIT 1');
        $user->execute([$username]);
        return $user->fetchColumn();
    }

    public static function getUserById($ID) {
        global $conn;
        $levels = $conn->prepare("SELECT * FROM `users` WHERE `ID` = ?");
        $levels->execute([$ID]);
        return $levels->fetchObject(Users::class);
    }

    public static function getUserByName(string $name) {
        global $conn;
        $levels = $conn->prepare("SELECT * FROM `users` WHERE `username` = ?");
        $levels->execute([$name]);
        return $levels->fetchObject(Users::class);
    }

    public static function getUserByToken(string $token) {
        global $conn;
        $levels = $conn->prepare("SELECT * FROM `users` WHERE `token` = ?");
        $levels->execute([$token]);
        return $levels->fetchObject(Users::class);
    }

    static function newUser(string $username, string $password, string $token, int $time) {
        global $conn;
        if (!$conn
            ->prepare('INSERT INTO users (username, password, token, regDate) VALUES (?, ?, ?, ?)')
            ->execute([
                $username,
                password_hash($password, PASSWORD_DEFAULT),
                $token,
                $time
            ]))
            return null;
        return $token;
    }
    
    static function randomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function render() {
        return "\n    ".'{'.
            '"created":"'.$this->regDate.'",'.
            '"discordId":"0",'.
            '"id":"'.$this->ID.'",'.
            '"levelpacks":[],'.
            '"levels":[],'.
            '"stars":[],'.
            '"updated":"",'.
            '"username":"'.$this->username.'"'.
        '}';
    }
}