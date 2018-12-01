<?php
ob_start();
session_start();
if(isset($_SESSION['user']) === TRUE) {
   // ログイン済みの場合、ホームページへリダイレクト
   header("Location: list.php");
}
// DBとの接続
include_once 'dbconnect.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ログイン</title>
        <!-- Bootstrap読み込み（スタイリングのため） -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="login.css">
    </head>

    <nav class="navbar navbar-default">
            <div class="container">
              <div class="navbar-header">
                <a href="#.php" class="navbar-brand">勤怠管理</a>
              </div>
              <div id="navbar" class="navbar-right">
                <ul class="nav navbar-nav navbar-right">
                  <li><a href="login.php">ログイン</a></li>
                </ul>
              </div>
            </div>
    </nav>

    <body>
        <div class="col-xs-6 col-xs-offset-3">
        <?php
        // ログインボタンがクリックされたときに下記を実行
        if(isset($_POST['login'])) {
            $email = $mysqli->real_escape_string($_POST['email']);
            $password = $mysqli->real_escape_string($_POST['password']);
        // クエリの実行
        $query = "SELECT *FROM users WHERE email='$email'";
        $result = $mysqli->query($query);
        if(!$result) {
            print('クエリが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
        }
        // パスワード(暗号化済み）とユーザーIDの取り出し
        while($row = $result->fetch_assoc()) {
            $db_hashed_pwd = $row['password'];
            $id = $row['id'];
        }
        // データベースの切断
        $result->close();
        ?>
        <?php
        // ハッシュ化されたパスワードがマッチするかどうかを確認
        if(password_verify($password, $db_hashed_pwd)) {
            $_SESSION['user'] = $id;
            header("Location: input.php");
            exit;
        } else { ?>
            <div class="alert alert-danger" role="alert">メールアドレスとパスワードが一致しません。</div>
        <?php }
        } ?>
            <div class="panel panel-default" style="margin-top:50px;">
                <div class="panel-heading"><h4>ログイン</h4></div>
                <div class="panel-body">
                <form method="post">
                        <div class="form-group">
                            <label>メールアドレス<input type="email" class="form-control" name="email" placeholder="メールアドレスを入力して下さい。" size="80"></label><br>
                        </div>
                        <div class="form-group">
                        <label>パスワード<input type="password" class="form-control" name="password" placeholder="パスワードを入力して下さい。" size="80"></label><br />
                        </div>
                        <button type="submit" class="btn btn-success" name="login" style="margin-right: 20px;">ログインする</button>
                        <a href="register.php">新規登録はこちら</a>
                </form>
                </div>
            </div>
        </div>
    </body>
</html>
