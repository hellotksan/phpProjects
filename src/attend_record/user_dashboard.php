<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>time tables</title>
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
    session_start();

    $id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');

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

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $current_time = date("H:i:s");
        $current_date = date("Y-m-d");
        if (isset($_POST['check_in'])) {
            $sql = "INSERT INTO attendance (id, date, check_in_time) VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE check_in_time = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssss", $id, $current_date, $current_time, $current_time);
        } elseif (isset($_POST['check_out'])) {
            // $sql = "UPDATE attendance SET check_out_time = ? WHERE id = ? AND date = ?";
            // $stmt = $con->prepare($sql);
            // $stmt->bind_param("sss", $current_time, $id, $current_date);

            $check_in_sql = "SELECT check_in_time FROM attendance WHERE id = ? AND date = ?";
            $check_in_stmt = $con->prepare($check_in_sql);
            $check_in_stmt->bind_param("ss", $id, $current_date);
            $check_in_stmt->execute();
            $check_in_result = $check_in_stmt->get_result();
            $check_in_row = $check_in_result->fetch_assoc();
            $check_in_time = $check_in_row['check_in_time'];

            // 残業時間を計算
            $check_in_minutes = strtotime($check_in_time) / 60;
            $check_out_minutes = strtotime($current_time) / 60;

            $overtime_minutes = 0;
            $late_night_overtime_minutes = 0;
            $holiday_overtime_minutes = 0;

            if (date('N', strtotime($current_date)) >= 6) {
                // 休日の残業
                $total_minutes = $check_out_minutes - $check_in_minutes - 60; // 休憩1時間を引く
                if ($total_minutes > 480) {
                    $holiday_overtime_minutes = $total_minutes - 480;
                }
            } else {
                // 平日の残業
                if ($check_out_minutes > 1140) { // 19:00以降
                    $overtime_minutes = min($check_out_minutes - 1140, 180); // 22:00までの残業
                    if ($check_out_minutes > 1320) { // 22:00以降
                        $late_night_overtime_minutes = $check_out_minutes - 1320; // 22:00以降の残業
                    }
                }
            }

            // 残業手当を計算
            $overtime_pay = ($overtime_minutes * (1000 * 1.25)) +
                ($late_night_overtime_minutes * (1000 * 1.5)) +
                ($holiday_overtime_minutes * (1000 * 1.75));

            $sql = "UPDATE attendance SET check_out_time = ?, overtime_minutes = ?, late_night_overtime_minutes = ?, holiday_overtime_minutes = ?, overtime_pay = ? WHERE id = ? AND date = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("siiisis", $current_time, $overtime_minutes, $late_night_overtime_minutes, $holiday_overtime_minutes, $overtime_pay, $id, $current_date);
        }
        $stmt->execute();
        $stmt->close();
    }

    $sql = "SELECT * FROM attendance WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <form method="post">
        <button type="submit" name="check_in">出勤</button>
        <button type="submit" name="check_out">退勤</button>
    </form>
    <br>
    <a href="user_dashboard.php?id=<?php echo $id; ?>" class="button">&lt;</a>
    <a href="user_dashboard.php?id=<?php echo $id; ?>" class="button">当月</a>
    <a href="user_dashboard.php?id=<?php echo $id; ?>" class="button">&gt;</a>
    <hr>
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>">
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
            <th>深夜残業</th>
            <th>休日残業</th>
            <th>残業手当</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['day_of_week'], ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['check_in_time'], ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td>
                    <?php
                    if (isset($row['check_in_time']) && $row['check_in_time'] > "09:01:00") {
                        echo "遅刻";
                    } else {
                        echo "定刻";
                    }
                    ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['check_out_time'], ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td>
                    <?php
                    if (isset($row['check_out_time']) && $row['check_out_time'] < "17:59:00") {
                        echo "早退";
                    } else {
                        echo "定刻";
                    }
                    ?>
                </td>
                <td><?php echo htmlspecialchars($row['overtime_minutes'], ENT_QUOTES, 'UTF-8'); ?> 分</td>
                <td><?php echo htmlspecialchars($row['late_night_overtime_minutes'], ENT_QUOTES, 'UTF-8'); ?> 分</td>
                <td><?php echo htmlspecialchars($row['holiday_overtime_minutes'], ENT_QUOTES, 'UTF-8'); ?> 分</td>
                <td><?php echo htmlspecialchars($row['overtime_pay'], ENT_QUOTES, 'UTF-8'); ?> 円</td>
            </tr>
        <?php endwhile; ?>
    </table>
    <hr>
    <br>
    <a href="admin_dashboard.php">戻る</a>
    <?php $stmt->close(); ?>
    <?php $con->close(); ?>
</body>

</html>