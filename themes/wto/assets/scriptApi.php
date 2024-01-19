<?php

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, "https://sparkly-blancmange-8150f4.netlify.app/articles");
    $curl_result = curl_exec($ch);
    $data=json_decode($curl_result,true);

    curl_close($ch); 

    $host = 'localhost'; // имя хоста
    $user = 'a120001_dbuser';      // имя пользователя
    $pass = '941x!nCOolD^>8yV';          // пароль
    $name = 'a120001_lumins';      // имя базы данных

    $conn = new mysqli($host, $user, $pass, $name);

    if ($conn->connect_error) {
        echo 'Ошибка подключения к базе данных';
        die("Ошибка: " . $conn->connect_error);
        exit();
    } 
    $sql="DELETE FROM lumin_articles_list;"; 

    if(!$conn->query($sql)) var_dump($conn->error);
        $sql = '';
        foreach ($data as $article) {
            $sql="INSERT INTO `lumin_articles_list` (title,content) VALUES('".$article['title']."','".str_replace("'","\'",$article['description'])."');"; 
            if(!$conn->query($sql)) var_dump($conn->error);
    }
    $conn->close();
    echo "скрипт отработал";
?>