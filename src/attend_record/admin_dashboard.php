<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

    $sql = "SELECT * FROM employee";
    $re = mysqli_query($con, $sql);

    echo "こんにちは。管理人さん。";
    echo "<br>";
    echo "変更したいユーザーを選んでください。";
    echo "<br>";

    while ($row = mysqli_fetch_assoc($re)) {
        echo '<a href="editTime.php?toId=' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '&name=' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '" class="button">変更</a>';
        echo '[' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '] ' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
        echo '<br>';
    }

    mysqli_free_result($re);
    mysqli_close($con);
    ?>
</body>

</html>