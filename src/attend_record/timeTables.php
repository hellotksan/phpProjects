<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>time tables</title>
</head>

<body>
    <?php
    session_start();

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

    $insert_sql = "INSERT INTO attendance (id, name, date, day_of_week, check_in_time, check_out_time) VALUES (?, ?, ?, ?, ?, ?)";
    $insert_stmt = $con->prepare($insert_sql);
    $insert_stmt->bind_param("ssssss", $id, $name, $date, $dayOfWeek, $workTime, $closeTime);
    $insert_stmt->execute();

    $sql = "SELECT * FROM attendance WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <h1>変更しました</h1>
    <hr>
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>">
    <table border="1">
        <tr>
            <th>ID</th>
            <th>氏名</th>
            <th>日付</th>
            <th>曜日</th>
            <th>出勤</th>
            <th>退勤</th>
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
                    <?php echo htmlspecialchars($row['check_out_time'], ENT_QUOTES, 'UTF-8'); ?>
                </td>
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