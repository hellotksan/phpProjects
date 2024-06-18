<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $id = $_POST['toId'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $photo = $_POST['photo'];
    $quantity = $_POST['quantity'];
    $cancel_id = $_POST['cancel_id'];
    $later_id = $_POST['laterId'] ?? null;
    $cart_id = $_POST['cartId'] ?? null;

    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name, $port) or die("接続に失敗しました。");
    mysqli_set_charset($con, "utf8");

    // キャンセル処理
    if ($cancel_id) {
        $sql = "DELETE FROM cart WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo "商品がカートから削除されました<br>";
        } else {
            echo "エラー: " . $stmt->error . "<br>";
        }
    }

    if ($later_id) {
        $sql = "UPDATE cart SET later IS true WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo "商品が後で買うリストに移動されました<br>";
        } else {
            echo "エラー: " . $stmt->error . "<br>";
        }
    }

    if ($cart_id) {
        $sql = "UPDATE cart SET later IS false WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo "商品がカートに戻されました<br>";
        } else {
            echo "エラー: " . $stmt->error . "<br>";
        }
    }

    if ($id && $quantity) {
        // カートに同じ商品が存在するか確認するSQL文
        $sql = "UPDATE cart SET purchase = purchase + ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $quantity, $id);

        // SQL文を実行
        if ($stmt->execute()) {
            // 影響を受けた行数を確認
            if ($stmt->affected_rows == 0) {
                # カートに追加するSQLの実行
                $sql = "INSERT INTO cart (id, purchase, after) VALUES (?, ?, 0)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ss", $id, $quantity);

                if ($stmt->execute()) {
                    echo "新しいレコードが作成されました<br>";
                } else {
                    echo "エラー: " . $stmt->error . "<br>";
                }
            } else {
                echo "既存のレコードが更新されました<br>";
            }
        } else {
            echo "エラー: " . $stmt->error . "<br>";
        }
    }

    $sql = "SELECT * FROM shop JOIN cart ON shop.id = cart.id";
    $stmt = $con->prepare($sql);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        echo "<h3>カートの表示</h3>";
        while ($row = $result->fetch_assoc()) {
            if (is_null($row['after'])) {
                echo "ID: " . $row['id'] . " - Name: " . $row['name'] . " - Price: " . $row['price'] . " - Photo: ";
                echo '<img src="./../images/' . $row['photo'] . '">';
                echo " - Quantity: " . $row['purchase'] . "<br>";
            }
        }

        echo "<h3>後で買うリストの表示</h3>";
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            if (!is_null($row['after'])) {
                echo "ID: " . $row['id'] . " - Name: " . $row['name'] . " - Price: " . $row['price'] . " - Photo: ";
                echo '<img src="./../images/' . $row['photo'] . '">';
                echo " - Quantity: " . $row['purchase'] . "<br>";
            }
        }
    } else {
        echo "エラー: " . $stmt->error . "<br>";
    }


    $stmt->close();
    $con->close();
    ?>
    <br>
    <a href="Shopping1.php">Back</a><br>
</body>

</html>