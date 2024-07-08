<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $id = $_POST['id'];
    $name = $_POST['name'];
    $password = $_POST['password'];

    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name, $port) or die("接続に失敗しました。");
    mysqli_set_charset($con, "utf8");

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO employee (id, name, password) VALUES (?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sss", $id, $name, $password_hashed);

    if ($stmt->execute()) {
        echo "新しいレコードが作成されました";
    } else {
        echo "エラー: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
    ?>
    <br>
    <a href="registerNext.php">登録</a><br>
    <a href="login.php">ログイン</a>
</body>

</html>