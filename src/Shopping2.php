<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B1next</title>
</head>

<body>
    <?php
    $toId = $_GET['toId'];
    $name = $_GET['name'];
    $price = $_GET['price'];
    $photo = $_GET['photo'];

    // データの表示
    echo "選んだ商品は「";
    echo $toId . "、" . $price . "、" . $name . "」です。" . "<img src='./../images/" . $photo . "' alt='" . $name . "' width='70' height='70'><br>";
    ?>
    <br><br>

    <form action="Shopping3.php" method="post">
        <input type="hidden" name="toId" value="<?php echo $toId; ?>">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="hidden" name="price" value="<?php echo $price; ?>">
        <input type="hidden" name="photo" value="<?php echo $photo; ?>">
        <input type="hidden" name="quantity" value="0">
        <input type="hidden" name="cancel_id" value="true">
        <button type="submit">カートから削除する</button>
    </form>

    <br>
    <hr><br>

    <form action="Shopping3.php" method="post">
        <input type="hidden" name="toId" value="<?php echo $toId; ?>">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="hidden" name="price" value="<?php echo $price; ?>">
        <input type="hidden" name="photo" value="<?php echo $photo; ?>">
        <input type="hidden" name="cancel_id" value="false">
        <button type="submit">後で買うリストに追加する</button>
    </form>

    <br>
    <hr><br>

    <form action="Shopping3.php" method="post">
        <input type="hidden" name="toId" value="<?php echo $toId; ?>">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="hidden" name="price" value="<?php echo $price; ?>">
        <input type="hidden" name="photo" value="<?php echo $photo; ?>">
        <input type="hidden" name="cancel_id" value="false">
        <input type="hidden" name="cart_id" value="true">
        <button type="submit">後で買うリストからカートに追加する</button>
    </form>

    <br><br>

    <form action="Shopping3.php" method="post">
        <p>購入数の入力
            <select name="quantity">
                <?php
                // 在庫数を仮で10とする
                for ($i = 1; $i <= 10; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>
        </p>
        <!-- 商品情報を送信するための hidden フィールド -->
        <input type="hidden" name="toId" value="<?php echo $toId; ?>">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="hidden" name="price" value="<?php echo $price; ?>">
        <input type="hidden" name="photo" value="<?php echo $photo; ?>">
        <input type="hidden" name="cancel_id" value="false">
        <input type="hidden" name="cart_id" value="yes">
        <button type="submit">カートに入れる</button>
    </form>

    <br><br>
    <a href="Shopping1.php">Back</a><br>
</body>

</html>