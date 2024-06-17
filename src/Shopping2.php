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
    echo $toId . " " . $name . " " . $price . " " . "<img src='./../images/" . $photo . "' alt='" . $name . "' width='70' height='70'><br>";
    ?>

    <form action="" method="post">
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
        <input type="hidden" name="productId" value="<?php echo $toId; ?>">
        <input type="hidden" name="productName" value="<?php echo $name; ?>">
        <input type="hidden" name="productPrice" value="<?php echo $price; ?>">
        <input type="hidden" name="productPhoto" value="<?php echo $photo; ?>">
        <button type="submit">カートに入れる</button>
    </form>

</body>

</html>