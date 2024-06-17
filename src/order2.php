<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $id = $_GET['toId'];

    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name, $port) or die("接続に失敗しました。");

    mysqli_set_charset($con, "utf8");

    // SQLクエリの実行
    $sql = "update sushi set buy = buy + 1 where id = $id";
    $re = mysqli_query($con, $sql);

    header('Location:order1.php');
    exit();
    ?>
</body>

</html>