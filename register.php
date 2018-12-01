<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>新規登録</title>
        <!-- Bootstrap読み込み（スタイリングのため） -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
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
            // エラーメッセージ、登録完了メッセージの初期化
            $errorMessage = "";

            $username = isset($_POST['username'])? $_REQUEST['username'] : NULL;
            $email = isset($_POST['email'])? $_REQUEST['email'] : NULL;
            $password = isset($_POST['password'])? $_REQUEST['password'] : NULL;
            $password = password_hash($password, PASSWORD_DEFAULT);


            // 新規登録ボタンが押された場合
            if (isset($_POST["signup"])) {
            if (empty($_POST["username"])){
                $errorMessage = 'ユーザー名を入力してください。';
            }
            else if (empty($_POST["email"])) {
                $errorMessage = 'メールアドレスを入力してください。';
            }
            else if (empty($_POST["password"])) {
                $errorMessage = 'パスワードを入力してください。';
            }
            else if (mb_strlen($_POST["password"]) < 6) {
                $errorMessage = 'パスワードは6文字以上で設定してください。';
            }
            else if (empty($_POST["password2"])) {
                $errorMessage = '確認用パスワードを入力してくださいです。';
            }
            else if($_POST["password"] != $_POST["password2"]) {
                $errorMessage = 'パスワードに誤りがあります。';
            }
            else {
                $conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
                mysqli_select_db($conn,'kintai') or exit("データベース名が間違っています。");

                //以下のSQL文は、同じユーザ名が存在するかを調べる
                $sql="SELECT * FROM users where userName='{$username}';";
                $result=mysqli_query($conn,$sql) or exit("データの抽出に失敗しました。");

                //以下のプログラムは、すでに同じユーザ名が存在すれば、登録済みのメッセージを出す
                if(mysqli_num_rows($result)!=0){ ?>
                <div class="alert alert-danger" role="alert"><?php echo $username ?>は登録済みです。もう一度別の名前で登録してください。</div>
            <?php }
                else{
                //以下のプログラムは新規登録を行うためのプログラム
                $sql = "INSERT INTO users(username,email,password,created_at) VALUES('$username','$email','$password',now())";
                $result=mysqli_query($conn,$sql)  or exit("データの書き込みに失敗しました。"); ?>
                <div class="alert alert-success" role="alert">登録しました。</div>
            <?php
            }
            mysqli_close($conn);
            }
            }
            ?>
            <!--バリデーションのエラー表示 -->
            <?php if(!empty($errorMessage)): ?>
            <ul class="alert alert-danger" style="padding: 10px 30px;">
                <li><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></li>
            </ul>
            <?php endif; ?>

            <div class="panel panel-default">
                <div class="panel-heading"><h4>新規登録</h4></div>
                <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label>ユーザー名<input type="text" class="form-control" name="username"
                        value="<?php if( !empty($_POST['username']) ){ echo $_POST['username']; } ?>"
                        placeholder="ユーザー名を入力して下さい。" size="80"></label><br>
                    </div>
                    <div class="form-group">
                        <label>メールアドレス<input type="email" class="form-control" name="email"
                        value="<?php if( !empty($_POST['email']) ){ echo $_POST['email']; } ?>"
                        placeholder="メールアドレスを入力して下さい。" size="80"></label><br>
                    </div>
                    <div class="form-group">
                        <label>パスワード<input type="password" class="form-control" name="password" placeholder="パスワードを入力して下さい。" size="80"></label><br>
                    </div>
                    <div class="form-group">
                    　  <label>パスワード（確認用）<input type="password" class="form-control" name="password2" placeholder="パスワードをもう一度入力して下さい。" size="80"></label><br>
                    </div>
                    <button type="submit" class="btn btn-primary" name="signup" style="margin-right: 10px;">新規登録</button>
                    <a href="login.php">ログインはこちら</a>
                </form>
                </div>
            </div>
        </div>
    </body>
</html>
