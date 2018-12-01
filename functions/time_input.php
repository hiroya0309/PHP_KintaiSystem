<?php
session_start();
include_once 'dbconnect.php';
// ユーザーIDを取り出す
$query = "SELECT * FROM users WHERE id=".$_SESSION['user']."";
$result = $mysqli->query($query);
while($row = $result->fetch_assoc()) {
    $user_id = $row['id'];
}


/// 出勤ボタンが押された場合
if (isset($_POST["btn_input"])) {
  $start = date($_POST['start_time']);
  $remarks = $mysqli->real_escape_string($_POST['remarks']);
  preg_match_all("/(\d\d):(\d\d)/", $start, $m);
  $min = $m[2][0];
  if( !empty($start) && ($min != "00" ) && ($min != "15" ) && ($min != "30" ) && ($min != "45" ) ) {
    $errorMessage = "15分単位で入力してください。";
  }
  elseif( empty($start) ) {
    $errorMessage = "出勤時間を入力してください。";
  }
  else {
    $conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
    mysqli_select_db($conn,'kintai') or exit("データベース名が間違っています。");
    //以下のSQL文は、同じ日付が存在するかを調べる
    $sql="SELECT * FROM work where date = DATE(now()) AND form IS NOT NULL";
    $result=mysqli_query($conn,$sql) or exit("データの抽出に失敗しました。");
    //以下のプログラムは、すでに出勤時間を登録済みであれば、登録済みのメッセージを出す
    if(mysqli_num_rows($result)!=0){
      $errorMessage = "本日の出勤時間は登録済みです。";
     }
    else{
        $sql = "UPDATE work SET
                user_id = '$user_id',
                form = '出勤' ,
                remarks = '$remarks' ,
                start_time = '$start' ,
                created_at = now()
                WHERE date = DATE(now())";
    //$sql = "INSERT INTO work(user_id,form,date,start_time,remarks,created_at) VALUES('$user_id','出勤' ,now(), '$start', '$remarks', now())";
      if($mysqli->query($sql)) {
        header("Location: list.php");
      } else { ?>
        <div class="alert alert-danger" role="alert">エラーが発生しました。</div>
      <?php
      }
    }
  }
}
  // 退勤ボタンが押された場合
if (isset($_POST["btn_input2"])) {
  $finish = date($_POST['finish_time']);
  $breck = $mysqli->real_escape_string($_POST['breck_time']);
  $remarks = $mysqli->real_escape_string($_POST['remarks']);
  preg_match_all("/(\d\d):(\d\d)/", $finish, $m);
  $min = $m[2][0];
  if( !empty($finish) && ($min != "00" ) && ($min != "15" ) && ($min != "30" ) && ($min != "45" ) ) {
    $errorMessage = "15分単位で入力してください。";
  }
  elseif( empty($breck) ) {
    $errorMessage = "休憩時間を選んでください。";
  }
  elseif( empty($finish) ) {
    $errorMessage = "退勤時間を入力してください。";
  }
  else {
    $conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
    mysqli_select_db($conn,'kintai') or exit("データベース名が間違っています。");
    //以下のSQL文は、出勤時間が登録されているかを調べる
    $sql="SELECT * FROM work WHERE EXISTS (SELECT * FROM work where date = DATE(now()))";
    $result=mysqli_query($conn,$sql) or exit("データの抽出に失敗しました。");
    //以下のプログラムは、登録されていれば、メッセージを出す
    if(mysqli_num_rows($result) == 0){
      $errorMessage = "出勤時間が登録されていません。";
    }
    //休憩時間によって分岐
    elseif( $breck === "1" ){
      $sql = "UPDATE work SET
              user_id = '$user_id',
              breck_time = '0:00' ,
              remarks = '$remarks' ,
              finish_time = '$finish' ,
              updated_at = now()
              WHERE date = DATE(now())";
      var_dump($sql);
      if($mysqli->query($sql)) {
        header("Location: list.php");
      } else { ?>
        <div class="alert alert-danger" role="alert">エラーが発生しました。</div>
      <?php
      }
    }elseif( $breck === "2" ) {
      $sql = "UPDATE work SET
      user_id = '$user_id',
      breck_time = '1:00' ,
      remarks = '$remarks' ,
      finish_time = '$finish' ,
      updated_at = now()
      WHERE date = DATE(now())";
      var_dump($sql);
      if($mysqli->query($sql)) {
      header("Location: list.php");
      } else { ?>
      <div class="alert alert-danger" role="alert">エラーが発生しました。</div>
      <?php
      }
    }
  }
}
$mysqli->close();
?>
