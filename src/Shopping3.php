<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping3</title>
</head>

<body>
    <?php
    $id = $_POST['toId'];
    $quantity = $_POST['quantity'];
    $action = $_POST['action'];

    $db_host = "localhost";
    $db_user = "admin";
    $db_password = "password";
    $db_name = "studyDB";
    $port = 3307;

    $con = new mysqli($db_host, $db_user, $db_password, $db_name, $port);

    if ($con->connect_error) {
        die("接続に失敗しました: " . $con->connect_error);
    }
    $con->set_charset("utf8");

    if ($action === 'default') {
        $stmt = $con->prepare("UPDATE cart SET purchase = purchase + ?, after = 0 WHERE id = ?");
        $stmt->bind_param("is", $quantity, $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows == 0) {
                $stmt = $con->prepare("INSERT INTO cart (id, purchase, after) VALUES (?, ?, 0)");
                $stmt->bind_param("ss", $id, $quantity);

                if ($stmt->execute()) {
                    echo "カートに追加されました。<br>";
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
    # 削除するときのSQLを実行する
    elseif ($action === 'delete') {
        $stmt = $con->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo "レコードが削除されました<br>";
        } else {
            echo "エラー: " . $stmt->error . "<br>";
        }
    }
    # 後で買うリストに追加するときのSQLを実行する
    elseif ($action === 'add_to_later') {
        $stmt = $con->prepare("UPDATE cart SET after = 1 WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo "後で買うリストに追加されました<br>";
        } else {
            echo "エラー: " . $stmt->error . "<br>";
        }
    }
    # カートに追加するときのSQLを実行する
    elseif ($action === 'add_to_cart') {
        $stmt = $con->prepare("UPDATE cart SET after = 0 WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo "カートに追加されました<br>";
        } else {
            echo "エラー: " . $stmt->error . "<br>";
        }
    }
    echo "<br><hr><br>";

    # 後で買うリストやカートで表示するためのSQLを実行する
    $stmt = $con->prepare("SELECT * FROM shop JOIN cart ON shop.id = cart.id");

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $total_price = 0;

        echo "<h3>カートの表示</h3>";
        while ($row = $result->fetch_assoc()) {
            if ($row['after'] == 0) {
                $purchase_price = $row['price'] * $row['purchase'];
                $total_price += $purchase_price;

                echo $row['id'] . " , " . $row['name'] . " , " . $row['price'];
                echo "円 , " . $row['purchase'] . "個 , ";
                echo '<img src="./../images/' . $row['photo'] . '">';
                echo ' <form method="POST" style="display:inline;">
                        <input type="hidden" name="toId" value="' . $row['id'] . '">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="quantity" value="' . $row['purchase'] . '">
                        <button type="submit">削除</button>
                    </form>';
                echo ' <form method="POST" style="display:inline;">
                        <input type="hidden" name="toId" value="' . $row['id'] . '">
                        <input type="hidden" name="action" value="add_to_later">
                        <input type="hidden" name="quantity" value="' . $row['purchase'] . '">
                        <button type="submit">後で買うリストに追加</button>
                    </form>';
                echo "<br>";
            }
        }
        echo "<h3>合計金額: " . $total_price . "円</h3>";
        echo "<br><hr><br>";

        echo "<h3>後で買うリストの表示</h3>";
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            if ($row['after'] == 1) {
                echo $row['id'] . " , " . $row['name'] . " , " . $row['price'] . "円 , ";
                echo $row['purchase'] . "個 , ";
                echo '<img src="./../images/' . $row['photo'] . '">';
                echo ' <form method="POST" style="display:inline;">
                        <input type="hidden" name="toId" value="' . $row['id'] . '">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="quantity" value="' . $row['purchase'] . '">
                        <button type="submit">削除</button>
                    </form>';
                echo ' <form method="POST" style="display:inline;">
                        <input type="hidden" name="toId" value="' . $row['id'] . '">
                        <input type="hidden" name="action" value="add_to_cart">
                        <input type="hidden" name="quantity" value="' . $row['purchase'] . '">
                        <button type="submit">カートに戻す</button>
                    </form>';
                echo "<br>";
            }
        }
    } else {
        echo "エラー: " . $stmt->error . "<br>";
    }

    $stmt->close();
    $con->close();
    ?>
    <br>
    <hr><br>
    <a href="Shopping4.php">レジに進む</a><br><br><br>
    <a href="Shopping1.php">買い物に戻る</a><br>
</body>

</html>