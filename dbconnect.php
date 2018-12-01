<?php
require_once('../kintai/config.php'); //外部ファイルの読み込み

$mysqli = new mysqli($host, $username, $password, $dbname);
if ($mysqli->connect_error) {
  die('接続失敗です。'.mysql_error());
  exit;
}
//print('<p>接続に成功しました。</p>');
?>
