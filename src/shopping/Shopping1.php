<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping1</title>
</head>

<body>
    <br><br>

    <button onclick="location.href='Shopping1.php'">番号順</button>
    <button onclick="location.href='Shopping1.php?clickValue=asc'">価格の安い順</button>
    <button onclick="location.href='Shopping1.php?clickValue=desc'">価格の高い順</button>

    <br><br>
    <hr><br>

    <span>検索するキーワードを入力してください。</span>
    <form action="Shopping1.php" method="post">
        <input type="text" name="keyword">
        <input type="submit" value="検索">
    </form>

    <br>
    <hr><br>

    <?php
    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name, $port) or die("接続に失敗しました。");
    mysqli_set_charset($con, "utf8");

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

    while ($row = mysqli_fetch_array($re)) {
        echo '<a href="Shopping2.php?toId=' . $row['id'] . '&name=' . $row['name'] . '&price=' . $row['price'] . '&photo=' . $row['photo'] . '">' . $row['id'] . '</a> ';
        echo $row['name'] . ' ' . $row['price'] . '円';
        echo '<img src="./../images/' . $row['photo'] . '" width="70" height="70"><br>';
    }
    mysqli_close($con);
    ?>
</body>

</html>