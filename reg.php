<?php
require_once './server/api.php';
$er = 0;

error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $registercaptcha === true) {
    if (isset($_POST['h-captcha-response'])) {
        $data = array('secret' => $hCaptchaSecretKey,'response' => $_POST['h-captcha-response']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $responseData = json_decode($response);
        if(!$responseData->success) {
            $er = '-3';
        }
    } else $er = '-3';
}
if ($er !== 0) 
    exit($er);

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    if (!Users::hasUsed($username)) {
        $token = hash('sha256', Users::randomString(16).$username);
        $id = Users::newUser($username, $password, $token, time());
        echo 'DONE<script>localStorage.'.$serverLogin.' = "'.$id.'";window.parent.postMessage("'.$id.'", "'.$serverHost.'");</script>';
    } else {
        $er = '-1';
    }
}
if ($er !== 0) 
    exit($er);
echo $registercaptcha ? '<script src="https://js.hcaptcha.com/1/api.js" async defer></script>' : ''
?>
<div align="center">
    <h1>REGISTER</h1>
    <form method="post">
        <input name="username" maxlength="32" minlength="3" type="text" placeholder="USER NAME"><br><br>
        <input name="password" maxlength="64" minlength="5" type="password" placeholder="PASSWORD"><br><br>
        <?php echo $registercaptcha ? '<div class="h-captcha" data-sitekey="'.$hCaptchaSiteKey.'"></div><br><br>' : ''?>
        <input type="submit" value="REGISTER">
    </form>
</div>



