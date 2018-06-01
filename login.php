<?php
if(isset($_POST['name']) && isset($_POST['mail'])){//新規ユーザ登録
        try{
                // 接続
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG', 'miyashita', 'sonicdance');
                $write=$db->prepare('INSERT INTO user (mail, name, role) VALUES(:mail, :name, :role)');
                $write->bindvalue(':mail',$_POST['mail']);
                $write->bindvalue(':name',$_POST['name']);
                $write->bindvalue(':role',1);
                $write->execute();
                $db = null;// 切断
        } catch(PDOException $e){ //データベース接続失敗
            echo $e->getMessage();
            exit;
        }
}

        ?>