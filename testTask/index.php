<!--    PHP   -->
<?php
require_once 'phpTest.php';
require_once 'MySQLdb.php';

$mysqlConf = require 'mysql.php';
MySQLdb::init($mysqlConf['dbName'], $mysqlConf['host'], $mysqlConf['user'], $mysqlConf['password']);
?>

<!--    HTML   -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Reviews</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="styleTest.css" rel="stylesheet">
    <script async type="text/javascript" src="jsTest.js"></script>
</head>

<body>

<div class="content comment">
    <h3 class="head">Отзывы покупателей:</h3>

    <!--   ВЫВОДИМ ВСЕ КОММЕНТАРИИ ИЗ БД-->
    <?php
    $comments = MySQLdb::getInstance()->getComments();
    if (!empty($comments)) {
        foreach ($comments as $key) {
            printComment($key['name'], $key['date'], $key['comment'], $key['photoURI']);
        }
    } else {
        echo "здесь пока нет отзывов";
    }
    ?>
</div>
<br/>

    <!--   ФОРМА    -->
<div class="container-fluid content">
    <h3 class="head">Напишите Ваш отзыв</h3>

    <form enctype="multipart/form-data" method="post" action="" name="form" onsubmit="return validate()">

        <label>Ваше имя</label>
        <input type="text" name="name"/>
        <span id="nameV"></span>

        <label>Ваш email</label>
        <input type="text" name="email"/>
        <span style="color:red" id="emailV"></span>

        <label>Отзыв</label>
        <textarea name="comment"></textarea>
        <span id="commentV"></span>

        <label>Прикрепить фото</label>
        <input class="file" type="file" name="photo"/>

        <label>Введите число: <?php echo $check=mt_rand(); ?></label>
        <input type="hidden" name="check1" value="<?php echo $check; ?>"/>
        <input type="text" name="check2"/>
        <span id="check2V"></span>
        <input type="submit" value="Отправить отзыв" class="btn">

        <?php if (isset($info)) {
            echo "<div class='row' style='color:red'>" . $info . "</div>";
        } ?>
    </form>

</div>
</body>
</html>