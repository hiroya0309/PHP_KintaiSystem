<?php
// 変数の初期化
$page_flag = 0;
$clean = array();
$error = array();

// サニタイズ
if( !empty($_POST) ) {
	foreach( $_POST as $key => $value ) {
		$clean[$key] = htmlspecialchars( $value, ENT_QUOTES);
	}
}

if( !empty($_POST['confirm']) ) {
    $error = validation($clean);
    if(empty($error)){
        $page_flag = 1;
    }

} elseif( !empty($_POST['action']) ) {
	$page_flag = 2;
}

// バリデーション
function validation($data) {
    $error = array();

    preg_match_all("/(\d\d):(\d\d)/", ($data['start']), $m);
    $min = $m[2][0];
    if( !empty($data['start']) && ($min != "00" ) && ($min != "15" ) && ($min != "30" ) && ($min != "45" ) ) {
        $error[] = "出勤時刻を15分単位で入力してください。";
    }
    preg_match_all("/(\d\d):(\d\d)/", ($data['finish']), $m);
    $min = $m[2][0];
    if( !empty($data['finish']) && ($min != "00" ) && ($min != "15" ) && ($min != "30" ) && ($min != "45" ) ) {
        $error[] = "退勤時刻を15分単位で入力してください。";
    }
    preg_match_all("/(\d\d):(\d\d)/", ($data['breck']), $m);
    $min = $m[2][0];
    if( !empty($data['breck']) && ($min != "00" ) && ($min != "15" ) && ($min != "30" ) && ($min != "45" ) ) {
        $error[] = "休憩時間を15分単位で入力してください。";
    }
    if( empty($data['form']) ) {
		$error[] = "区分を選んでください。";
	}
	if( empty($data['start'])) {
		$error[] = "出勤時刻を入力してください。";
	}
    if( empty($data['finish'])) {
		$error[] = "退勤時刻を入力してください。";
    }
	if( empty($data['breck'])) {
		$error[] = "休憩時間を入力してください。";
    }
    return $error;
}
?>
<?php
session_start();
// DBとの接続
include_once 'dbconnect.php';
// ユーザーIDを取り出す
$query = "SELECT * FROM users WHERE id=".$_SESSION['user']."";
$result = $mysqli->query($query);
while($row = $result->fetch_assoc()) {
    $user = $row['username'];
}
// work情報を取り出す
$query = "SELECT * FROM work WHERE id=".$_GET['id']."";
$result = $mysqli->query($query);
if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
}
while($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $form = $row['form'];
    $date = $row['date'];
    $start = $row['start_time'];
    $finish = $row['finish_time'];
    $breck = $row['breck_time'];
    $remarks = $row['remarks'];
}
// 「更新する」を押下されたらDBに保存
if(isset($_POST['action'])) {
    $form = $mysqli->real_escape_string($_POST['form']);
    $start_time = date($_POST['start']);
    $finish_time = date($_POST['finish']);
    $breck_time = date($_POST['breck']);
    $remarks = $mysqli->real_escape_string($_POST['remarks']);
    // POSTされた情報をDBに格納する
    $sql = "UPDATE work SET
            form = '$form',
            start_time = '$start_time',
            finish_time = '$finish_time',
            breck_time = '$breck_time',
            remarks = '$remarks',
            updated_at = now()
            WHERE id = $id";
    if($mysqli->query($sql)) {  ?>
          <!--<div class="alert alert-success center-block" role="alert" style="width:60%;">投稿が完了しました。</div>-->
    <?php }else { ?>
        <div class="alert alert-danger" role="alert">エラーが発生しました。</div>
    <?php
        }
    }
    $mysqli->close();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>勤怠_編集</title>
        <!-- Bootstrap読み込み（スタイリングのため） -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="list.css">

    </head>
    <body>
    <nav class="navbar navbar-default">
            <div class="container">
              <div class="navbar-header">
                <a href="top.php" class="navbar-brand">勤怠</a>
              </div>
              <div id="navbar" class="navbar-right">
                <ul class="nav navbar-nav navbar-right">
                  <li ><a><?php echo htmlspecialchars($user); ?>がログインしています。</a></li>
                  <li><a href="input.php">時刻入力</a></li>
                  <li><a href="list.php">勤怠管理表</a></li>
                </ul>
              </div>
            </div>
    </nav>

    <?php if( $page_flag === 1 ): ?>
    <div class="col-xs-6 col-xs-offset-3">
            <div class="panel panel-success" style="margin-top:50px;">
                <div class="panel-heading" style="color:#808080;"><h4>編集内容確認</h4></div>
                <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label>区分</label>
                        <input type="text" class="form-control" name="date" readonly="readonly" value="<?php if( $_POST['form'] === "出勤" ){ echo '出勤'; }
                                elseif( $_POST['form'] === "休み" ){ echo '休み'; } ?>">
                    </div>
                    <div class="form-group">
                        <label>日付</label>
                            <input type="text" class="form-control" name="date" readonly="readonly" value="<?php echo date('Y年n月j日', strtotime($_POST['date']))."(";
                            $weekday = array( "日", "月", "火", "水", "木", "金", "土" );?><?php echo $weekday[date('w', strtotime($date))].")"; ?>">
                    </div>
                    <div class="form-group">
                        <label>始業時刻</label>
                            <input type="text" class="form-control" name="start" readonly="readonly" value="<?php echo date('H:i', strtotime($_POST['start'])); ?>">
                    </div>
                    <div class="form-group">
                        <label>終業時刻</label>
                            <input type="text" id="fromfinish" class="form-control" name="finish" readonly="readonly" value="<?php echo date('H:i', strtotime($_POST['finish'])); ?>">
                    </div>
                    <div class="form-group">
                        <label>休憩時間</label>
                            <input type="text" id="formbreck" class="form-control" name="breck" readonly="readonly" value="<?php echo date('H:i', strtotime($_POST['breck'])); ?>">
                    </div>
                    <div class="form-group">
                        <label>備考</label><br>
                            <textarea class="form-control" name="remarks" readonly="readonly" rows="3"><?php echo nl2br($_POST['remarks']); ?></textarea>
                    </div>
                    <input type="submit" class="btn btn-default" value="修正する">
                    <input type="submit" class="btn btn-info" name="action" value="編集する">
                    <input type="hidden" name="form" value="<?php echo $_POST['form'] ?>">
                    <input type="hidden" name="date" value="<?php echo $_POST['date'] ?>">
                    <input type="hidden" name="start" value="<?php echo $_POST['start'] ?>">
                    <input type="hidden" name="finish" value="<?php echo $_POST['finish'] ?>">
                    <input type="hidden" name="breck" value="<?php echo $_POST['breck'] ?>">
                    <input type="hidden" name="remarks" value="<?php echo $_POST['remarks'] ?>">
                </form>
                </div>
            </div>
        </div>



    <?php elseif( $page_flag === 2 ): ?>
    <div class="col-md-4 col-sm-offset-1">
        <div class="container">
            <div class="row">
            <div class="col-sm-12">
                    <hr>
                    <h1>編集完了</h1>
                    <hr>
                    <h4>勤怠管理表を更新しました。</h4><br>
                    <a href="list.php" class="btn btn-primary" role="button" style="margin-right: 20px;">勤怠管理表へ戻る</a>
                    <a href="input.php" class="btn btn-success" role="button">時刻入力</a>
            </div>
            </div>
        </div>
    </div>

    <?php else: ?>
        <div class="col-xs-6 col-xs-offset-3">
            <div class="panel panel-info" style="margin-top:50px;">
                <div class="panel-heading" style="color:#808080;"><h4>勤怠表編集</h4></div>
                <div class="panel-body">

                <!--バリデーションのエラー表示 -->
                <?php if(!empty($error)): ?>
                    <ul class="alert alert-danger" style="padding: 10px 30px";>
                    <?php foreach($error as $value): ?>
                            <li><?php echo $value; ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form method="post">
                    <div class="form-group">
                        <label>区分</label>
                        <select id="from" class="form-control" name="form" style="width:300px;">
                            <option value="" disabled selected style='display:none;'>区分を選んでください</option>
                            <option value="出勤" >出勤</option>
                            <option value="休み" >休み</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>日付</label>
                            <input type="text" class="form-control" name="date" disabled="disabled" value="<?php echo date('Y年n月j日', strtotime($date)); ?>(<?php $weekday = array( "日", "月", "火", "水", "木", "金", "土" );?><?php echo $weekday[date('w', strtotime($date))]; ?>)">
                            <input type="hidden" class="form-control" name="date" value="<?php echo date('Y-m-d', strtotime($date)); ?>">
                    </div>
                    <div class="form-group">
                        <label>始業時刻</label>
                            <input type="text" id="fromstart" class="form-control" name="start" placeholder="15分単位、00:00形式で入力してください（例 09:00）" value="<?php echo date('H:i', strtotime($start)) ?>">
                    </div>
                    <div class="form-group">
                        <label>終業時刻</label>
                            <input type="text" id="fromfinish" class="form-control" name="finish" placeholder="15分単位、00:00形式で入力してください（例 01:15）" value="<?php echo date('H:i', strtotime($finish)) ?>">
                    </div>
                    <div class="form-group">
                        <label>休憩時間</label>
                            <input type="text" id="formbreck" class="form-control" name="breck" placeholder="15分単位、00:00形式で入力してください（例 01:30）" value="<?php echo date('H:i', strtotime($breck)) ?>">
                    </div>
                    <div class="form-group">
                        <label>備考</label><br>
                            <textarea class="form-control" name="remarks" placeholder="備考を入力してください" rows="3"><?php echo nl2br($remarks); ?></textarea>
                    </div>
                    <input type="submit" class="btn btn-success" name="confirm" value="編集内容を確認する">
                    <script>
                        (function () {
                        var body = {
                            'start': [
                            '',
                            '<?php echo date('H:i', strtotime($start)) ?>',
                            '00:00'
                            ],
                            'finish': [
                            '',
                            '<?php echo date('H:i', strtotime($finish)) ?>',
                            '00:00'
                            ],
                            'breck': [
                            '',
                            '<?php echo date('H:i', strtotime($breck)) ?>',
                            '00:00']
                        };
                        document.getElementById('from').addEventListener('change', action, false);
                        function action() {
                            var select = document.getElementById('from').selectedIndex;
                            document.getElementById('fromstart').value = body['start'][select];
                            document.getElementById('fromfinish').value = body['finish'][select];
                            document.getElementById('formbreck').value = body['breck'][select];
                        }
                        }());
                    </script>
                </form>
                </div>
            </div>
        </div>
    </body>
    <?php endif; ?>
</html>
