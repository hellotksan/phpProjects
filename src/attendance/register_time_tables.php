<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>登録完了</h1>
    <br>
    <hr><br>
    <?php
    session_start();

    date_default_timezone_set('Asia/Tokyo');

    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
    $workTime = htmlspecialchars($_POST['workTime'], ENT_QUOTES, 'UTF-8');
    $closeTime = htmlspecialchars($_POST['closeTime'], ENT_QUOTES, 'UTF-8');

    $daysOfWeek = ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"];
    $datetime = new DateTime($date);
    $dayOfWeek = $datetime->format('w');
    $dayOfWeek = $daysOfWeek[$dayOfWeek];

    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name, $port);
    if (!$con) {
        die("接続に失敗しました: " . mysqli_connect_error());
    }
    mysqli_set_charset($con, "utf8");

    // 既存レコードがあるかどうか確認する
    $check_sql = "SELECT * FROM attendance WHERE id = ? AND date = ?";
    $check_stmt = $con->prepare($check_sql);
    $check_stmt->bind_param("is", $id, $date);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    $check_in_time = $workTime;
    $check_out_time = $closeTime;

    $current_date = date("Y-m-d");

    // 18:00 in minutes
    $work_end_time = 18;
    // 19:00 in minutes
    $overtime_start_time = 19;
    // 22:00 in minutes
    $late_night_start_time = 22;

    // 出勤時間と退勤時間のタイムスタンプを取得
    $check_in_timestamp = strtotime($date . " " . $check_in_time);
    $check_out_timestamp = strtotime($date . " " . $check_out_time);

    // 0時0分からの経過分数を計算
    $check_in_hours = ($check_in_timestamp - strtotime($date . " 00:00:00")) / 60 / 60;
    $check_out_hours = ($check_out_timestamp - strtotime($date . " 00:00:00")) / 60 / 60;

    $check_in_hours = floor($check_in_hours);
    $check_out_hours = floor($check_out_hours);

    $total_minutes_worked = $check_out_hours - $check_in_hours + 1;

    // 単純(19時以降22時未満)な残業時間
    $overtime_minutes = 0;
    // 深夜(22時以降)の残業時間
    $late_night_overtime_minutes = 0;
    // 休日の出勤時間
    $holiday_overtime_minutes = 0;

    // 休日の残業
    if ($dayOfWeek == "土曜日" || $dayOfWeek == "日曜日") {
        if ($total_minutes_worked > 8) {
            // 休憩時間1時間を除く
            $total_minutes_worked -= 2;
            $holiday_overtime_minutes = $total_minutes_worked;
        }
    }
    // 平日の残業
    else {
        // 残業時間の計算
        $over_minutes = $check_out_hours - $work_end_time;
        // 22:00未満
        if ($over_minutes > 0 and $over_minutes < 4) {
            $overtime_minutes = $over_minutes;
        }
        // 22:00以降
        else if ($over_minutes >= 4) {
            $overtime_minutes = 4;
            $late_night_overtime_minutes = $over_minutes - 4;
        }
    }

    // 残業手当を計算
    $overtime_pay = ($overtime_minutes * (1000 * 1.25)) +
        ($late_night_overtime_minutes * (1000 * 1.5)) +
        ($holiday_overtime_minutes * (1000 * 1.75));

    // 既存レコードがある場合は更新する
    if ($result->num_rows > 0) {
        $update_sql = "UPDATE attendance SET name = ?, day_of_week = ?, check_in_time = ?, check_out_time = ?, overtime_minutes = ?, late_night_overtime_minutes = ?, holiday_overtime_minutes = ?, overtime_pay = ? WHERE id = ? AND date = ?";
        $update_stmt = $con->prepare($update_sql);
        $update_stmt->bind_param("ssssiiiiis", $name, $dayOfWeek, $workTime, $closeTime, $overtime_minutes, $late_night_overtime_minutes, $holiday_overtime_minutes, $overtime_pay, $id, $date);
        $update_stmt->execute();
        echo "レコードを更新しました。";
    } else {
        $insert_sql = "INSERT INTO attendance (id, name, date, day_of_week, check_in_time, check_out_time, overtime_minutes, late_night_overtime_minutes, holiday_overtime_minutes, overtime_pay) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $con->prepare($insert_sql);
        $insert_stmt->bind_param("ssssssiiii", $id, $name, $date, $dayOfWeek, $workTime, $closeTime, $overtime_minutes, $late_night_overtime_minutes, $holiday_overtime_minutes, $overtime_pay);
        $insert_stmt->execute();
        echo "レコードを追加しました。";
    }

    $con->close();
    ?>
    <br><br>
    <hr><br><a href='login.php'>ログインへ戻る</a>
</body>

</html>