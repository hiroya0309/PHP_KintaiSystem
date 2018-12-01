<?php include_once(dirname(__FILE__).' /./functions/time_input.php');?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>勤怠_時刻入力</title>

        <!-- Bootstrap読み込み（スタイリングのため） -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="input.css">
        <!-- JavaScript読み込み -->
        <script src="clock.js"></script>

    </head>

    <nav class="navbar navbar-default">
            <div class="container">
              <div class="navbar-header">
                <a href="#.php" class="navbar-brand">勤怠</a>
              </div>
              <div id="navbar" class="navbar-right">
                <ul class="nav navbar-nav navbar-right">
                  <li><a href="list.php">勤怠管理表</a></li>
                </ul>
              </div>
            </div>
    </nav>

    <body>
      <!-- 時計枠 -->
      <div id ="clock_frame">
          <!-- 日付部分 -->
          <span id ="clock_date"></span><br>
          <!-- 時刻部分 -->
          <span id ="clock_time"></span><br>
          <form class="form-inline" name="tm" method="post">
            <span>
            <!-- 時刻取得 -->
              <script language="JavaScript">
                function time_start(){
                var DD = new Date();
                var Hr = DD.getHours() ;
                var Mi = DD.getMinutes() ;
                if(Hr < 10) Hr = "0" + Hr;
                if(Mi < 10) Mi = "0" + Mi;
                document.tm.start_time.value =Hr + ":" + Mi ;
                }
                function time_finish(){
                var DD = new Date();
                var Hr = DD.getHours() ;
                var Mi = DD.getMinutes() ;
                if(Hr < 10) Hr = "0" + Hr;
                if(Mi < 10) Mi = "0" + Mi;
                document.tm.finish_time.value =Hr + ":" + Mi ;
                }
              </script>
              <div class="panel panel-default" style="width:620px;">
                  <div class="panel-body">
                  <!--バリデーションのエラー表示 -->
                  <?php if(!empty($errorMessage)): ?>
                    <ul class="alert alert-danger" style="width:580px;padding: 10px 30px;">
                        <li><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></li>
                    </ul>
                  <?php endif; ?>
                    <input type="text" class="form-control" name="start_time" placeholder="出勤時間（15分単位で入力）" style="width:200px;" >
                    <input type="button" class="btn btn-warning btn-sm" name="action" value="時間取得" style="margin-right:30px" onClick="time_start()">
                    <input type="text" class="form-control" name="finish_time" placeholder="退勤時間（15分単位で入力）" style="width:200px;" >
                    <input type="button" class="btn btn-warning btn-sm" name="action" value="時間取得" onClick="time_finish()"><br>
                    <input type="submit" name="btn_input" value="出勤登録" style="margin-right:225px;">
                    <input type="submit" name="btn_input2" value="退勤登録" style="margin-bottom:30px;"><br>
                    <select class="form-control" name="breck_time" style="width:300px;">
                      <option value="" disabled selected style='display:none;'>休憩時間を選んでください</option>
                      <option value="1" >休憩時間：0分</option>
                      <option value="2" >休憩時間：60分</option>
                    </select><br>
                    <textarea class="form-control" name="remarks" placeholder="備考" rows="4" style="width:580px;"></textarea><br>
                  </div>
              </div>
            </span>
          </form>
      </div>
    </body>
</html>
