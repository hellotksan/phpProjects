<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>表示したい種類をクリックして下さい。</p>
    <a href="C2.php?clickValue=A">１．果物</a><br>
    <a href="C2.php?clickValue=B">２．野菜</a><br>
    <a href="C2.php?clickValue=C">３．その他</a><br>

    <?php
    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name, $port) or die("接続に失敗しました。");
    mysqli_set_charset($con, "utf8");

    // SQLクエリの初期設定
    $sql = "SELECT code, name, price, photo FROM food";
    $where = "";

    // URLパラメータのチェック
    if (isset($_GET['clickValue'])) {
        $clickValue = $_GET['clickValue'];
        if ($clickValue == 'A') {
            $where = " WHERE code LIKE 'A%'";
        } elseif ($clickValue == 'B') {
            $where = " WHERE code LIKE 'B%'";
        } elseif ($clickValue == 'C') {
            $where = " WHERE code LIKE 'C%'";
        }
    }

    // 完全なSQLクエリの作成
    $sql .= $where;
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
    if ($count > 0) {
        echo ("<br>値段の平均は" . ($sum / $count) . "円です");
    } else {
        echo ("<br>データがありません");
    }

    // データベース接続解除
    mysqli_close($con);
    ?>
</body>

</html>