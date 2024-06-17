<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="A2next.php" method="POST">
        <select name="nameSelect">
            <?php for ($i = 2000; $i <= 2020; $i++) { ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php  } ?>
        </select>
        <button>送信</button>
    </form>

</body>

</html>