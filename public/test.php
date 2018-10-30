<?php
session_start();

require_once '../vendor/autoload.php';

// 0) 設定 client 端的 id, secret
$client = new Google_Client;
$client->setClientId("974658843640-ban0u40f04qahk5lkl9kl1sj06cfmvi4.apps.googleusercontent.com");
$client->setClientSecret("M0qG1HkI5p89uMn24DNOCkc_");



var_dump($client);
exit;
// 2) 使用者認證後，可取得 access_token
if (isset($_GET['code']))
{
    $client->setRedirectUri("http://wsstest.teamotto.me/hksr/public/test.php");
    $result = $client->authenticate($_GET['code']);

    if (isset($result['error']))
    {
        die($result['error_description']);
    }

    $_SESSION['google']['access_token'] = $result;
    header("Location:http://wsstest.teamotto.me/hksr/public/test.php?action=profile");
}

// 3) 使用 id_token 取得使用者資料。另有 setAccessToken()、getAccessToken() 可以設定與取得 token
elseif ($_GET['action'] == "profile")
{
    $profile = $client->verifyIdToken($_SESSION['google']['access_token']['id_token']);
    print_r($profile); //使用者個人資料
}

// 1) 前往 Google 登入網址，請求用戶授權
else
{
    $client->revokeToken();
    session_destroy();

    // 添加授權範圍，參考 https://developers.google.com/identity/protocols/googlescopes
    $client->addScope(['https://www.googleapis.com/auth/userinfo.profile']);
    $client->setRedirectUri("http://domain/test.php");
    $url = $client->createAuthUrl();
    header("Location:{$url}");
}