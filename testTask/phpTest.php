<?php

require_once 'MySQLdb.php';

$mysqlConf = require 'mysql.php';
MySQLdb::init($mysqlConf['dbName'], $mysqlConf['host'], $mysqlConf['user'], $mysqlConf['password']);

// ВАЛИДАЦИЯ PHP

if (!empty($_POST)) {

    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $comment = clean($_POST['comment']);

    $check1 = ($_POST['check1']);
    $check2 = ($_POST['check2']);

    if (!empty($name) && !empty($email) && !empty($comment) && !empty($check1) && !empty($check2)) {

        $nameValidateSymbols = nameValidateSymbols($name);
        $emailValidate = filter_var($email, FILTER_VALIDATE_EMAIL);
        $commentValidateSymbols = commentValidateSymbols($email, FILTER_VALIDATE_EMAIL);
        $photoPermissionValidate = photoPermissionValidate($_FILES['photo']['name']);

        if($check1 == $check2){
            $check = true;
        } else {
            $check = false;
        }

        if (!$nameValidateSymbols && checkLength($name, 2, 32) && $emailValidate && !$commentValidateSymbols && checkLength($comment, 2, 1000) && $photoPermissionValidate && $check) {

            // СОЖРАНЕНИЕ в БД

            uploadFile($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
            $photo = $_FILES['photo']['name'];

            MySQLdb::getInstance()->addComment($name, $email, $comment, $photo);
            $info = "Благодарим за Ваш отзыв!!!";
        } else {
            $info = "Введенные данные некорректны<br/>(не должны содержать спец символы, превышать допустимую длинну, email содержит @ и ., изображение в формате 'jpg', 'gif', 'png' )!";
        }
    } else {
        $info = "все поля должны быть заполнены!";
    }
}

// ФУНКЦИИ ДЛЯ ВАЛИДАЦИИ

function clean($value)
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);

    return $value;
}

function checkLength($value, $min, $max)
{
    $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
    return !$result;
}

function nameValidateSymbols($name)
{
    $patternName = '/\[0-9!@#$%^&*();\\/\|<>\"\'.:]/';
    return preg_match($patternName, $name);
}

function commentValidateSymbols($comment)
{
    $patternName = '/\[#$^&*\\/\|<>/';
    return preg_match($patternName, $comment);
}

function photoPermissionValidate($photoName)
{
    if (empty($photoName)) {
        return true;
    } elseif (!empty($photoName)) {
        $temp = explode('.', $photoName);
        $permissionFile = $temp[1];

        if ($permissionFile == 'jpg' || $permissionFile == 'gif' || $permissionFile == 'png') {
            return true;
        } else {
            return false;
        }
    }
}

// ФУНКЦИЯ ВЫВОДА КОММЕНТАРИЕВ
function printComment($name, $date, $comment, $photo)
{
    echo "<div class='row'>";
    echo "<b>$name</b>" . " <i>" . $date . "</i><br>";
    echo $comment;

    if ($photo) {
        echo "<img src='/img/" . $photo . "' width='300px'>";
    }
    echo "</div>";
}

// ФУНКЦИЯ ЗАГРУЗКИ ФАЙЛА
function uploadFile($tmpPath, $fileName)
{
    $pictureDir = __DIR__ . '/img';
    if (!file_exists($pictureDir)) {
        mkdir($pictureDir);
    }

    $imageNewPath = $pictureDir . '/' . $fileName;
    move_uploaded_file($tmpPath, $imageNewPath);
    return $fileName;
}



