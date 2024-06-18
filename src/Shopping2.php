<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping2</title>
</head>

<body>
    <?php
    $toId = $_GET['toId'];
    $name = $_GET['name'];
    $price = $_GET['price'];
    $photo = $_GET['photo'];

    echo "選んだ商品は「";
    echo $toId . "、" . $price . "、" . $name . "」です。";
    echo "<img src='./../images/" . $photo . "' alt='" . $name . "' width='70' height='70'><br>";
    ?>

    <br>
    <hr><br>

    <form action="Shopping3.php" method="post">
        <p>購入数の入力
            <select name="quantity">
                <?php
                for ($i = 1; $i <= 10; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>
        </p>
        <input type="hidden" name="toId" value="<?php echo $toId; ?>">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="hidden" name="price" value="<?php echo $price; ?>">
        <input type="hidden" name="photo" value="<?php echo $photo; ?>">
        <input type="hidden" name="action" value="default">
        <button type="submit">カートに入れる</button>
    </form>

    <br>
    <hr><br>

    <a href="Shopping1.php">買い物に戻る</a><br>
</body>

</html>