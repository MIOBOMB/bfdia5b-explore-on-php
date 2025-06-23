<?php
require_once './server/api.php';
$er = 0;

error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $logincaptcha === true) {
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
    $user = Users::getUserByName($username);
    if ($user != null) {
        if ($user->verifyPassword($password)) {
            echo 'DONE<script>localStorage.'.$serverLogin.' = "'.$user->token.'";window.parent.postMessage("'.$user->token.'", "'.$serverHost.'");</script>';
        } else $er = '-1';
    } else $er = '-2';
}
if ($er !== 0) 
    exit($er);
echo $logincaptcha ? '<script src="https://js.hcaptcha.com/1/api.js" async defer></script>' : ''
?>
<div align="center">
    <h1>LOG IN</h1>
    <form method="post">
        <input name="username" maxlength="32" minlength="3" type="text" placeholder="USER NAME"><br><br>
        <input name="password" maxlength="64" minlength="5" type="password" placeholder="PASSWORD"><br><br>
        <?php echo $logincaptcha ? '<div class="h-captcha" data-sitekey="'.$hCaptchaSiteKey.'"></div><br><br>' : ''?>
        <p>Need account? <a href="reg.php">Register!</a></p><br><br>
        <input type="submit" value="LOG IN">
    </form>
</div>



