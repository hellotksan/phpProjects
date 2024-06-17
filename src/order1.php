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
    $sql = "SELECT * FROM sushi";
    $re = mysqli_query($con, $sql);

    // 結果を配列に保存
    $data = [];
    while ($row = mysqli_fetch_array($re)) {
        $data[] = $row;
    }

    // データの表示
    foreach ($data as $row) {
    ?>
        <a href="order2.php?toId=<?php echo $row['id']; ?>">
            <?php echo $row['id']; ?>
        </a>
        <?php echo $row['name'] . ' ' . $row['price']; ?>
        <img src="./../images/<?php echo $row['photo']; ?>" width="70" height="70"><br>
    <?php
    }

    // 合計金額を初期化
    $sum = 0;

    // 購入の支払い金額の表示と合計の計算
    echo '<br><br><br>';
    echo "<h3>支払い票</h3>";
    foreach ($data as $row) {
        if ($row['buy'] != 0) {
            $individualTotal = $row['price'] * $row['buy'];
            $sum += $individualTotal;
        } else {
            $individualTotal = 0;
        }
    ?>
        <p>
            名前: <?php echo $row['name']; ?>
            <?php echo $row['price']; ?>
            * <?php echo $row['buy']; ?>
            = <?php echo $individualTotal; ?> 円
        </p>
    <?php

    }

    // 購入の支払い金額の合計の表示
    echo "<br>購入の支払い金額の合計は " . $sum . " 円です";

    // データベース接続解除
    mysqli_close($con);
    ?>
</body>

</html>