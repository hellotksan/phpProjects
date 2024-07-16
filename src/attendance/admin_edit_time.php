<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    session_start();

    $id = htmlspecialchars($_GET['toId'], ENT_QUOTES, 'UTF-8');

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

    $sql = "SELECT * FROM employee WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $name = $user['name'];

    echo '<h1>出退勤変更</h1><br>';
    echo "ID: " . $id . ", 名前: " . $name . "さん";
    echo "<br><hr><br>";

    echo "<form action='register_time_tables.php' method='post'>";
    echo "<input type='hidden' name='id' value=" . $id . ">";
    echo "<input type='hidden' name='name' value=" . $name . ">";
    echo "<label for='id'>変更する日時</label>";
    echo "<input type='date' id='date' name='date' required><br>";
    echo "<label for='workTime'>出勤時刻</label>";
    echo "<input type='time' id='workTime' name='workTime' required><br>";
    echo "<label for='closeTime'>退勤時刻</label>";
    echo "<input type='time' id='closeTime' name='closeTime' required><br><br>";
    echo "<button type='submit'>変更</button>";
    echo "</form>";

    echo "<br><hr><br>";
    echo "<a href='admin_dashboard.php'>管理者ダッシュボードに戻る</a>";

    $stmt->close();
    $con->close();
    ?>
</body>

</html>