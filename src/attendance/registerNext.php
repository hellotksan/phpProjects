<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        echo "新しいユーザを登録しました";
    } else {
        echo "エラー: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
    ?>
    <br>
    <br<hr>
        <br>
        <a href="login.php" class="button">ログイン</a>
</body>

</html>