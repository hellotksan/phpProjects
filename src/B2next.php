<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B1next</title>
</head>

<body>
    <?php
    // GETパラメータの取得とエスケープ処理
    $toId = $_GET['toId'];
    $name = $_GET['name'];
    $price = $_GET['price'];
    $photo = $_GET['photo'];

    // データの表示
    echo $toId . " " . $name . " " . $price . " " . "<img src='./../images/" . $photo . "' alt='" . $name . "' width='70' height='70'><br>";
    ?>
</body>

</html>