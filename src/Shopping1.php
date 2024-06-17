<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <button onclick="location.href='Shopping1.php?clickValue=asc'">価格の安い順</button>
    <button onclick="location.href='Shopping1.php?clickValue=desc'">価格の高い順</button>
    <!-- <button onclick="location.href='S.php?clickValue=no'">番号順</button> -->
    <br>
    <hr>
    <span>検索するキーワードを入力してください。</span>
    <form action="Shopping1.php" method="post">
        <input type="text" name="keyword">
        <input type="submit" value="検索">
    </form>
    <hr>

    <?php
    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name, $port) or die("接続に失敗しました。");
    mysqli_set_charset($con, "utf8");

    // デフォルトはid
    $order = "id";
    if (isset($_GET['clickValue'])) {
        if ($_GET['clickValue'] == 'asc') {
            $order = "price ASC";
        } elseif ($_GET['clickValue'] == 'desc') {
            $order = "price DESC";
        }
    }

    $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

    $sql = "SELECT * FROM shop WHERE name LIKE '%$keyword%' ORDER BY $order";
    $re = mysqli_query($con, $sql);

    // 合計金額を初期化
    // $sum = 0;

    // データの表示
    while ($row = mysqli_fetch_array($re)) {
        // $sum += $row['price'];
    ?>
        <a href="Shopping2.php?toId=<?php echo $row['id']; ?>&name=<?php echo $row['name']; ?>&price=<?php echo $row['price']; ?>&photo=<?php echo $row['photo']; ?>">
            <?php echo $row['id']; ?>
        </a>
        <?php echo $row['name'] . ' ' . $row['price']; ?>
        <img src="./../images/<?php echo $row['photo']; ?>" width="70" height="70"><br>
    <?php }

    // 平均値の計算と表示
    // $count = mysqli_num_rows($re);
    // echo ("<br>値段の平均は" . ($sum / $count) . "円です");

    // データベース接続解除
    mysqli_close($con);
    ?>
</body>

</html>