<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Tables</title>
    <style>
        .button {
            display: inline-block;
            padding: 8px 20px;
            font-size: 12px;
            color: #ffffff;
            background-color: #777777;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            margin: 2px 1px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php
    $current_month_total = 0;

    session_start();

    date_default_timezone_set('Asia/Tokyo');

    function getJapaneseDayOfWeek($date)
    {
        $daysOfWeek = ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'];
        $dayIndex = date('w', strtotime($date));
        return $daysOfWeek[$dayIndex];
    }

    $id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');

    // データベース接続設定
    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    // PDOで接続
    try {
        $con = new PDO("mysql:host=$db_host;dbname=$db_name;port=$port;charset=utf8", $db_user, $db_password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("接続に失敗しました: " . $e->getMessage());
    }

    // ユーザー名を取得
    $user_sql = "SELECT name FROM employee WHERE id = ?";
    $stmt = $con->prepare($user_sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $name = $user['name'];

    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $current_time = date("H:i:s");
        $current_date = date("Y-m-d");
        $day_of_week = getJapaneseDayOfWeek($current_date);

        if (isset($_POST['check_in'])) {
            $sql = "INSERT INTO attendance (id, name, date, day_of_week, check_in_time)
                    VALUES (?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE check_in_time = ?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$id, $name, $current_date, $day_of_week, $current_time, $current_time]);
        } elseif (isset($_POST['check_out'])) {
            $check_in_sql = "SELECT check_in_time FROM attendance WHERE id = ? AND date = ?";
            $stmt = $con->prepare($check_in_sql);
            $stmt->execute([$id, $current_date]);
            $check_in_row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($check_in_row) {
                $check_in_time = $check_in_row['check_in_time'];
                $check_out_time = $current_time;

                // 18:00 in minutes
                $work_end_time = 18;
                // 19:00 in minutes
                $overtime_start_time = 19;
                // 22:00 in minutes
                $late_night_start_time = 22;

                // 出勤時間と退勤時間のタイムスタンプを取得
                $check_in_timestamp = strtotime($current_date . " " . $check_in_time);
                $check_out_timestamp = strtotime($current_date . " " . $check_out_time);

                // 0時0分からの経過時間(時)を計算
                $check_in_hours = ($check_in_timestamp - strtotime($current_date . " 00:00:00")) / 60 / 60;
                $check_out_hours = ($check_out_timestamp - strtotime($current_date . " 00:00:00")) / 60 / 60;

                $check_in_hours = floor($check_in_hours);
                $check_out_hours = ceil($check_out_hours);

                $total_minutes_worked = $check_out_hours - $check_in_hours + 1;
                $overtime_minutes = 0;
                $late_night_overtime_minutes = 0;
                $holiday_overtime_minutes = 0;

                // 休日の残業
                if (date('N', strtotime($current_date)) >= 6) {
                    if ($total_minutes_worked >= 8) {
                        // 休憩時間1時間を除く
                        $total_minutes_worked -= 1;
                        $holiday_overtime_minutes = $total_minutes_worked - 8;
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
                        $late_night_overtime_minutes = $over_minutes;
                    }
                }

                // 残業手当を計算
                $overtime_pay = ($overtime_minutes * (1000 * 1.25 / 60)) +
                    ($late_night_overtime_minutes * (1000 * 1.5 / 60)) +
                    ($holiday_overtime_minutes * (1000 * 1.75 / 60));

                $sql = "UPDATE attendance SET check_out_time = ?, overtime_minutes = ?, late_night_overtime_minutes = ?, holiday_overtime_minutes = ?, overtime_pay = ? WHERE id = ? AND date = ?";
                $stmt = $con->prepare($sql);
                $stmt->execute([$current_time, $overtime_minutes, $late_night_overtime_minutes, $holiday_overtime_minutes, $overtime_pay, $id, $current_date]);
            } else {
                echo "選んだ日付の出勤記録がありません。";
            }
        }
    }

    $start_date = date("Y-m-01", strtotime("$year-$month-01"));
    $end_date = date("Y-m-t", strtotime("$year-$month-01"));

    $sql = "SELECT * FROM attendance WHERE id = ? AND date BETWEEN ? AND ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id, $start_date, $end_date]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 前月と来月の年と月を計算
    $prev_month = $month - 1;
    $next_month = $month + 1;
    $prev_year = $year;
    $next_year = $year;

    if ($prev_month < 1) {
        $prev_month = 12;
        $prev_year--;
    }

    if ($next_month > 12) {
        $next_month = 1;
        $next_year++;
    }
    ?>

    <h1>ユーザダッシュボード</h1><br>
    <hr><br>
    <form method="post">
        <button type="submit" name="check_in">出勤</button>
        <button type="submit" name="check_out">退勤</button>
    </form>
    <br>
    <a href="user_dashboard.php?id=<?php echo $id; ?>&year=<?php echo $prev_year; ?>&month=<?php echo $prev_month; ?>" class="button">&lt;</a>
    <a href="user_dashboard.php?id=<?php echo $id; ?>&year=<?php echo $year; ?>&month=<?php echo $month; ?>" class="button">当月</a>
    <a href="user_dashboard.php?id=<?php echo $id; ?>&year=<?php echo $next_year; ?>&month=<?php echo $next_month; ?>" class="button">&gt;</a>
    <hr>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>氏名</th>
            <th>日付</th>
            <th>曜日</th>
            <th>出勤</th>
            <th>遅刻</th>
            <th>退勤</th>
            <th>早退</th>
            <th>残業</th>
            <th>深夜残業</th>
            <th>休日残業</th>
            <th>残業手当</th>
        </tr>
        <?php foreach ($result as $row) : ?>
            <?php $current_month_total += $row['overtime_pay']; ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['day_of_week'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['check_in_time'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo isset($row['check_in_time']) && strtotime($row['check_in_time']) > strtotime('09:00:00') ? '遅刻' : '定刻'; ?></td>
                <td><?php echo htmlspecialchars($row['check_out_time'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo isset($row['check_out_time']) && strtotime($row['check_out_time']) < strtotime('18:00:00') ? '早退' : '定刻'; ?></td>
                <td><?php echo htmlspecialchars($row['overtime_minutes'], ENT_QUOTES, 'UTF-8'); ?> 時間</td>
                <td><?php echo htmlspecialchars($row['late_night_overtime_minutes'], ENT_QUOTES, 'UTF-8'); ?> 時間</td>
                <td><?php echo htmlspecialchars($row['holiday_overtime_minutes'], ENT_QUOTES, 'UTF-8'); ?> 時間</td>
                <td><?php echo htmlspecialchars($row['overtime_pay'], ENT_QUOTES, 'UTF-8'); ?> 円</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p>当月の残業手当は <?php echo $current_month_total; ?> 円です。</p>
    <br>
    <hr><br>
    <a href="login.php">ログインへ戻る</a>
</body>

</html>