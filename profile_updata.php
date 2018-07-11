<?php
if (isset($_POST['mail'])) {
        try {
                // 接続
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG', 'miyashita', 'sonicdance');

                $mail = $_POST['mail'];

                if (isset($_POST['pass']))
                        if (!(empty($_POST['pass']))) {
                        $pass = $_POST['pass'];
                        $sqldata = $db->prepare("UPDATE user SET pass = '$pass' WHERE user.mail = '$mail'");
                        $sqldata->execute();
                }
                if (isset($_POST['name']))
                        if (!(empty($_POST['name']))) {
                        $name = $_POST['name'];
                        $sqldata = $db->prepare("UPDATE user SET name = '$name' WHERE user.mail = '$mail'");
                        $sqldata->execute();

                }



                $db = null;// 切断
        } catch (PDOException $e) { //データベース接続失敗
        //     exit;
        }
}

