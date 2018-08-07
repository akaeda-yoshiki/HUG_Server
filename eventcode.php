<?php

if (isset($_POST['theme_id']) && isset($_POST['mail']) && isset($_POST['open'])) {
        try {
                $loop = true;
                while ($loop) {
        //ランダムな英数字の生成
                        $text = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPUQRSTUVWXYZ';
                        $code = substr(
                                str_shuffle($text),
                                0,
                                10
                        );

                        $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
                        $sqldata = $db->prepare("SELECT code FROM eventcode WHERE eventcode.code = '$code'");
                        $sqldata->execute();
                        while ($row = $sqldata->fetch()) {
                                $db_data[] = array(
                                        'code' => $row['code']
                                );
                        }

                        if (empty($db_data)) {
                        // 挿入***********************************************
                                $loop = false;
                        }
                }

                $write = $db->prepare('INSERT INTO eventcode (code, theme_id, mail, open) VALUES(:code, :theme_id, :mail, :open)');
                $write->bindvalue(':code', $code);
                $write->bindvalue(':theme_id', $_POST['theme_id']);
                $write->bindvalue(':mail', $_POST['mail']);
                $write->bindvalue(':open', $_POST['open']);
                $write->execute();

        // SQL作成
                $sql = "CREATE TABLE `$code` (
                num VARCHAR(45) PRIMARY KEY,
		id VARCHAR(45),
		data1 VARCHAR(45),
                data2 VARCHAR(45),
                data3 VARCHAR(45),
                data4 VARCHAR(45),
                data5 VARCHAR(45)
	) engine=innodb default charset=utf8";

	// SQL実行
                $create = $db->prepare($sql);
                $create->execute();
                $db = null;// 切断
        } catch (PDOException $e) { //データベース接続失敗
//     echo $e->getMessage();
                exit;
        }
} else if (isset($_POST['code']) && isset($_POST['role']) && isset($_POST['mail'])) {
        try {
                $code = $_POST['code'];
                $role = $_POST['role'];
                $mail = $_POST['mail'];

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
                $sqldata = $db->prepare("SELECT id FROM `{$code}` WHERE `{$code}`.data1 = '$role'");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                'id' => $row['id']
                        );
                }
        // echo $db_data;
                if (empty($db_data)) {
                // echo($code);
                        $sqldata1 = $db->prepare("SELECT name FROM user WHERE user.mail = '$mail'");
                        $sqldata1->execute();
                        $name = "ゲスト";
                        while ($row = $sqldata1->fetch()) {
                                $name = $row['name'];
                                // $db_data[] = array(
                                //         'name' => $row['name']
                                // );
                        }
                        // echo $name;

                        $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                        $sql = "SELECT * FROM `{$code}`";
                        $stmt = $db->query($sql);
                        $stmt->execute();
                        $count = $stmt->rowCount();
                // 挿入***********************************************
                        $write = $db->prepare("INSERT INTO `{$code}` (num, id, data1, data2, data3, data4, data5) VALUES(:num, :id, :data1, :data2, :data3, :data4, :data5)");
                        $write->bindvalue(':num', $count);
                        $write->bindvalue(':id', 1);
                        $write->bindvalue(':data1', $role);
                        $write->bindvalue(':data2', $mail);
                        $write->bindvalue(':data3', $name);
                        $write->bindvalue(':data4', "");
                        $write->bindvalue(':data5', "");
                        $write->execute();
                        echo "ok";
                }
                $db = null;
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
                exit;
        }
} else if (isset($_POST['code']) && isset($_POST['id'])) {
        try {
                $id = $_POST['id'];
                $code = $_POST['code'];

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
                $sqldata = $db->prepare("SELECT data1, data2, data3, data4, data5 FROM  `{$code}` WHERE  `{$code}`.id = '$id'");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                'data1' => $row['data1'],
                                'data2' => $row['data2'],
                                'data3' => $row['data3'],
                                'data4' => $row['data4'],
                                'data5' => $row['data5']
                        );
                }
                // echo $code . "::" . $id;
                // echo $db_data;
                header("Content-type: application/json; charset=UTF-8");
                echo json_encode($db_data);
                $db = null;

        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
        }
} else if (isset($_POST['code'])) {
        try {

                $code = $_POST['code'];

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
                $sqldata = $db->prepare("SELECT theme_id FROM eventcode WHERE eventcode.code = '$code'");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                'theme_id' => $row['theme_id']
                        );
                }
                $id = $db_data[0]["theme_id"];
                echo $id;
                $db = null;
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
        }
}

?>