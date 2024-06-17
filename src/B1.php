<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name, $port);
    if (!$con) {
        die("データベースに接続できませんでした: " . mysqli_connect_error());
    }

    mysqli_set_charset($con, "utf8");

    $sql = "SELECT * FROM worker";
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        die("SQLクエリの準備に失敗しました: " . mysqli_error($con));
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $sum = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $sum += $row['age'];
        echo '<a href="B1next.php?toId=' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['id']) . '</a>';
        echo htmlspecialchars($row['name'] . ' ' . $row['age']);
        echo '<img src="./../images/' . htmlspecialchars($row['photo']) . '" width="70" height="70"><br>';
    }

    $count = mysqli_num_rows($result);

    if ($count > 0) {
        echo "<br>年齢の平均は" . ($sum / $count) . "才です";
    }

    mysqli_stmt_close($stmt);

    mysqli_close($con);
    ?>
</body>

</html>