<?php
if (isset($_POST['pass']) && isset($_POST['mail']) && isset($_POST['faze'])) {//新規ユーザ登録
        try {
                // 接続
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG', 'miyashita', 'sonicdance');

                $pass = $_POST['pass'];
                $mail = $_POST['mail'];

                switch ($_POST['faze']) {
                        case 'login':
                                $sqldata = $db->prepare("SELECT name FROM user WHERE '$pass' = user.pass AND user.mail = '$mail'");
                                $sqldata->execute();
                                $data = null;
                                while ($row = $sqldata->fetch()) {
                                        $data[] = array(
                                                'name' => $row['name']
                                        );
                                }
                                header("Content-type: application/json; charset=UTF-8");

                                echo json_encode($data);
                                break;
                        case 'new':
                                $sqldata = $db->prepare("SELECT mail FROM user WHERE user.mail = '$mail'");
                                $sqldata->execute();
                                $data = null;
                                while ($row = $sqldata->fetch()) {
                                        $data[] = array(
                                                'mail' => $row['mail'],
                                        );
                                }
                                if ($data == null) {
                                        $write = $db->prepare('INSERT INTO user (mail, pass) VALUES(:mail, :pass)');
                                        $write->bindvalue(':mail', $mail);
                                        $write->bindvalue(':pass', $pass);
                                        $write->execute();
                                        $data = "ok";
                                }
                                header("Content-type: application/json; charset=UTF-8");

                                echo json_encode($data);
                                break;

                        case 'forget':

                                break;

                }
                
                // $cnt;
                // $mysqli = new mysqli( '192.168.0.159', 'miyashita', 'sonicdance', 'HUG');
                // if ($result = $mysqli->query("SELECT mail FROM user")) {

                //         $cnt = $result->num_rows;
                //         $result->close();
                // }
                // $mysqli->close();

                // // 接続
                // $db = new PDO('mysql:host=192.168.0.159;dbname=HUG', 'miyashita', 'sonicdance');
                // $write=$db->prepare('INSERT INTO user (mail, name, role, number) VALUES(:mail, :name, :role, :number)');
                // $write->bindvalue(':mail',$_POST['mail']);
                // $write->bindvalue(':name',$_POST['name']);
                // $write->bindvalue(':role',$_POST['role']);
                // $write->bindvalue(':number',$cnt + 1);

                // $write->execute();
                // echo json_encode($cnt + 1);
                $db = null;// 切断
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
                exit;
        }
}

