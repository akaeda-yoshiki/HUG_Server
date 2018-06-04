<?php
if(isset($_POST['name']) && isset($_POST['mail'])){//新規ユーザ登録
        try{
                $cnt;
                $mysqli = new mysqli( '192.168.0.159', 'miyashita', 'sonicdance', 'HUG');
                if ($result = $mysqli->query("SELECT mail FROM user")) {

                        $cnt = $result->num_rows;
                        $result->close();
                }
                $mysqli->close();

                // 接続
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG', 'miyashita', 'sonicdance');
                $write=$db->prepare('INSERT INTO user (mail, name, role, number) VALUES(:mail, :name, :role, :number)');
                $write->bindvalue(':mail',$_POST['mail']);
                $write->bindvalue(':name',$_POST['name']);
                $write->bindvalue(':role',1);
                $write->bindvalue(':number',$cnt + 1);

                $write->execute();
                echo json_encode($cnt + 1);
                $db = null;// 切断
        } catch(PDOException $e){ //データベース接続失敗
            echo $e->getMessage();
            exit;
        }
}

