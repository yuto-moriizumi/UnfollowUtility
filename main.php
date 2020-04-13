<?php
session_start();

require_once './secret.php';
require_once './autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

//セッションに入れておいたさっきの配列
$access_token = $_SESSION['access_token'];

//OAuthトークンとシークレットも使って TwitterOAuth をインスタンス化
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$myAccount = $connection->get('account/settings', [])->screen_name;

//フォローとフォロワーを取得
$follows = $connection->get('friends/ids', ['screen_name' => $myAccount])->ids;
$followers = $connection->get('followers/ids', ['screen_name' => $myAccount])->ids;
$hutodokimono = array_diff($follows, $followers);
?>
<html>
<body>
<h1>フォロー一括解除ページ</h1>
<?

echo '<p>フォローは' . count($follows) . '人、フォロワーは' . count($followers) . '人。解除候補は' . count($hutodokimono) . '人でした。</p>';

$ans = 0;
foreach ($hutodokimono as $user) {
    var_dump($user);
    $result = $connection->post('friendships/destroy', ['user_id' => $user]);
    if (isset($result->errors)) {
        var_dump($result->errors);
        break;
    }
    $ans += 1;
}
?>
<p>フォロー解除したのは<?=$ans?>人です。</p>
</body>
</html>
