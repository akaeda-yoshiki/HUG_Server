<?php
if (isset($_POST['mail'])) {
        try {
                // 接続
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG', 'miyashita', 'sonicdance');
                $mail = $_POST['mail'];
                $sqldata = $db->prepare("SELECT pass FROM user WHERE user.mail = '$mail'");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                'pass' => $row['pass']
                        );
                }
                $now_pass = $db_data[0]['pass'];
                if (isset($_POST['new_pass']) && isset($_POST['now_pass']))
                        if (strcmp($_POST['now_pass'], $now_pass) == 0)
                        if (!(empty($_POST['new_pass']))) {
                        $pass = $_POST['new_pass'];
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

