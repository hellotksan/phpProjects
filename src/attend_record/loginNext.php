<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    session_start();

    $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

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

    $sql = "SELECT * FROM employee WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['id'] === "P01" and $user["name"] === "admin") {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php?id=" . urlencode($user['id']));
        }
        exit();
    } else {
        echo "認証失敗";
    }

    $stmt->close();
    $con->close();
    ?>
    <br>
    <a href="login.php">ログイン</a><br>
    <a href="register.php">登録</a>
</body>

</html>