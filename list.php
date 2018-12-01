<?php
session_start();
include_once 'dbconnect.php';
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
}
// ユーザーIDを取り出す
$query = "SELECT * FROM users WHERE id=".$_SESSION['user']."";
$result = $mysqli->query($query);
while($row = $result->fetch_assoc()) {
    $user = $row['username'];
}
//日付表示
ini_set('display_errors', true);
error_reporting(E_ALL);

function weekday(DateTime $dt)
{
    $weeks = ['日', '月', '火', '水', '木', '金', '土'];
    return $weeks[$dt->format('w')];
}

$dt = new DateTime('first day of this month');

if (filter_input(INPUT_POST, 'y') && filter_input(INPUT_POST, 'm')) {
    $year = filter_input(INPUT_POST, 'y');
    $month = filter_input(INPUT_POST, 'm');
    $dt = new DateTime(sprintf('%d-%d-01', $year, $month));
}
$year = $dt->format('Y');
$month = $dt->format('m');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>勤怠_マイページ</title>
        <!-- Bootstrap読み込み（スタイリングのため） -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="list.css">
    </head>

    <nav class="navbar navbar-default">
            <div class="container">
              <div class="navbar-header">
                <a href="top.php" class="navbar-brand">勤怠</a>
              </div>
              <div id="navbar" class="navbar-right">
                <ul class="nav navbar-nav navbar-right">
                  <li ><a><?php echo htmlspecialchars($user); ?>がログインしています。</a></li>
                  <li><a href="input.php">時刻入力</a></li>
                  <li><a href="logout.php?logout">ログアウト</a></li>
                </ul>
              </div>
            </div>
    </nav>

    <body>
        <div class="col-md-4 col-sm-offset-1">
        <h1>勤怠管理表</h1>
        <hr>
        <form class="form-inline" action="" method="post">
            <select class="form-control" name="y" style="width:130px;">
                <?php foreach (range(2018, 2019) as $y): ?>
                    <?php if ((new DateTime())->format('Y') == $y) : ?>
                        <option value="<?= $y; ?>" selected="selected"><?= $y."年"; ?></option>
                    <?php else : ?>
                        <option value="<?= $y; ?>"><?= $y."年"; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <select class="form-control" name="m" style="width:80px;">
                <?php foreach (range(1, 12) as $m): ?>
                    <?php if ((new DateTime())->format('m') == $m) : ?>
                        <option value="<?= $m; ?>" selected="selected"><?= $m."月"; ?></option>
                    <?php else : ?>
                        <option value="<?= $m; ?>"><?= $m."月"; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-success">表示</button>
        </form>
        <?php
        $query = "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF( TIMEDIFF( finish_time , start_time ) , breck_time )))), '%H時間%i分') as working_sum ,
                  TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(finish_time,'18:00') * (finish_time > '18:00')))), '%H時間%i分') as overtime
                  FROM work WHERE (DATE_FORMAT(date, '%Y%m') = '$year$month') AND user_id=".$_SESSION['user']."";
                $result = $mysqli->query($query);
                if (!$result) {
                    print('クエリーが失敗しました。' . $mysqli->error);
                    $mysqli->close();
                    exit();
                }
        ?>
            <hr>
            <table id="attendance2">
                    <thead>
                        <th>稼働月</th>
                        <th>稼働時間</th>
                        <th>残業時間</th>
                        <th>勤務日数</th>
                    </thead>
                    <tbody>
                    <?php while($row = $result->fetch_assoc()) {
                        $working_sum = $row['working_sum'];
                        $over_sum = $row['overtime'];
                    ?>
                        <td><?= $dt->format('Y年m月'); ?></td>
                        <td><?php if(empty($working_sum)){echo "0時間";} elseif(!empty($working_sum)) {echo $working_sum;} ?></td>
                        <td><?php if(empty($over_sum)){echo "0時間";} elseif(!empty($over_sum)) {echo $over_sum;} ?></td>
                    <?php } ?>
                    <?php
                    $query = "SELECT COUNT(form) as day FROM work WHERE (DATE_FORMAT(date, '%Y%m') = '$year$month') AND form = '出勤' AND user_id=".$_SESSION['user']."";
                            $result = $mysqli->query($query);
                            if (!$result) {
                                print('クエリーが失敗しました。' . $mysqli->error);
                                $mysqli->close();
                                exit();
                            }
                    ?>
                    <?php while($row = $result->fetch_assoc()) {
                        $day = $row['day'];
                    ?>
                        <td><?php echo $day.'日'; ?></td>
                    <?php } ?>
                    </tbody>
            </table>
            <br>
        </div>
        <?php
        $query = "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF( TIMEDIFF( finish_time , start_time ) , breck_time )))), '%H:%i') as working_sum ,
                  TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(finish_time,'18:00') * (finish_time > '18:00')))), '%H:%i') as overtime
                  FROM work WHERE (DATE_FORMAT(date, '%Y%m') = '$year$month') AND user_id=".$_SESSION['user']."";
                $result = $mysqli->query($query);
                if (!$result) {
                    print('クエリーが失敗しました。' . $mysqli->error);
                    $mysqli->close();
                    exit();
                }
        ?>
        <div class="col-sm-offset-1 col-sm-10">
            <table id="attendance">
                <thead>
                <tr>
                    <th class="date">区分</th>
                    <th class="date">日付</th>
                    <th class="day">曜日</th>
                    <th>始業時刻</th>
                    <th>終業時刻</th>
                    <th>休憩</th>
                    <th>実働</th>
                    <th>時間外</th>
                    <th class="remarks">備考</th>
                    <th>編集</th>
                </tr>
                </thead>
                <tfoot>
                <?php while($row = $result->fetch_assoc()) {
                        $working_sum = $row['working_sum'];
                        $over_sum = $row['overtime'];
                 ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th>合 計</th>
                    <td><?php echo $working_sum; ?></td>
                    <td><?php echo $over_sum; ?></td>
                    <td></td>
                    <td></td>
                <?php } ?>
                </tr>
                </tfoot>
                <tbody>
                <?php
                $query = "SELECT id,date,form,start_time,finish_time,breck_time,remarks,TIMEDIFF( TIMEDIFF( finish_time , start_time ) , breck_time ) as working_time,
                          TIME_FORMAT(TIMEDIFF(finish_time,'18:00'), '%H:%i') as overtime
                          FROM work WHERE (DATE_FORMAT(date, '%Y%m') = '$year$month') AND user_id=".$_SESSION['user']."";
                $result = $mysqli->query($query);
                if (!$result) {
                    print('クエリーが失敗しました。' . $mysqli->error);
                    $mysqli->close();
                    exit();
                }
                ?>
                <?php while($row = $result->fetch_assoc()) {
                        $id = $row['id'];
                        $date = $row['date'];
                        $form = $row['form'];
                        $start = $row['start_time'];
                        $finish = $row['finish_time'];
                        $breck = $row['breck_time'];
                        $remarks = $row['remarks'];
                        $working = $row['working_time'];
                        $overtime = $row['overtime'];
                    ?>
                    <td style="color:<?php if($form == "休み") {$style = "red";} elseif($form == "出勤") {$style = "black";} echo $style; ?>">
                        <?php echo $form; ?></td>
                    <td><?php echo date('n月j日', strtotime($date)); ?></td>
                    <td><?php $weekday = array( "日", "月", "火", "水", "木", "金", "土" );?><?php echo $weekday[date('w', strtotime($date))]; ?></td>
                    <td><?php echo date('H:i', strtotime($start)); ?></td>
                    <td><?php echo date('H:i', strtotime($finish)); ?></td>
                    <td><?php echo date('H:i', strtotime($breck)); ?></td>
                    <td><?php if($finish !== "00:00:00") {echo date('H:i', strtotime($working));}
                              elseif($finish == "00:00:00") {echo "00:00";}?></td>
                    <td><?php if($finish > "18:00") {echo $overtime;}
                              elseif($finish < "18:00") {echo "00:00";}?></td>
                    <td><?php echo $remarks; ?></td>
                    <td><a href="list_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" role="button">編集</a></td>
                </tbody>
                <?php }
                ?>
            </table>
        </div>
    </body>
</html>
