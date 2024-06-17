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

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name, $port) or die("接続に失敗しました。");

    mysqli_set_charset($con, "utf8");

    // SQLクエリの実行
    $sql = "SELECT * FROM food";
    $re = mysqli_query($con, $sql);

    // 合計金額を初期化
    $sum = 0;

    // データの表示
    while ($row = mysqli_fetch_array($re)) {
        $sum += $row['price'];
    ?>
        <a href="B2next.php?toId=<?php echo $row['code']; ?>&name=<?php echo $row['name']; ?>&price=<?php echo $row['price']; ?>&photo=<?php echo $row['photo']; ?>">
            <?php echo $row['code']; ?>
        </a>
        <?php echo $row['name'] . ' ' . $row['price']; ?>
        <img src="./../images/<?php echo $row['photo']; ?>" width="70" height="70"><br>
    <?php }

    // 平均値の計算と表示
    $count = mysqli_num_rows($re);
    echo ("<br>値段の平均は" . ($sum / $count) . "円です");

    // データベース接続解除
    mysqli_close($con);
    ?>
</body>

</html>