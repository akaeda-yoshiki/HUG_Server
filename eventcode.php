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
} else if (isset($_POST['code']) && isset($_POST['data1']) && isset($_POST['id']) && strcmp($_POST['mode'], "insert") == 0) {
        try {

                $data1 = $_POST['data1'];
                $data2 = "";
                $data3 = "";
                $data4 = "";
                $data5 = "";
                if (isset($_POST['data2']))
                        $data2 = $_POST['data2'];
                if (isset($_POST['data3']))
                        $data3 = $_POST['data3'];
                if (isset($_POST['data4']))
                        $data4 = $_POST['data4'];
                if (isset($_POST['data5']))
                        $data5 = $_POST['data5'];

                $id = $_POST['id'];
                $code = $_POST['code'];

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

                $insert_flag = true;
                switch ($id) {
                        case 1:
                                // echo ("メンバー登録");
                                $sqldata = $db->prepare("SELECT id FROM `{$code}` WHERE `{$code}`.data1 = '$data1'");
                                $sqldata->execute();
                                while ($row = $sqldata->fetch()) {
                                        $db_data[] = array(
                                                'id' => $row['id']
                                        );
                                }
                                if (empty($db_data)) {
                                        // echo ("成功");
                                        // echo($code);
                                        $sqldata = $db->prepare("SELECT name FROM user WHERE user.mail = '$data2'");
                                        $sqldata->execute();

                                        while ($row = $sqldata->fetch()) {
                                                $data3 = $row['name'];
                                        }
                                        if (empty($data3))
                                                $data3 = "ゲスト";
                                } else {
                                        // echo ("失敗");
                                        $insert_flag = false;
                                }
                                break;
                }
                if ($insert_flag) {
                // echo($code);

                        $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                        $stmt = $db->prepare("SELECT * FROM `{$code}`");
                        $stmt->execute();
                        $count = $stmt->rowCount();
                // 挿入***********************************************
                        $write = $db->prepare("INSERT INTO `{$code}` (num, id, data1, data2, data3, data4, data5) VALUES(:num, :id, :data1, :data2, :data3, :data4, :data5)");
                        $write->bindvalue(':num', $count);
                        $write->bindvalue(':id', $id);
                        $write->bindvalue(':data1', $data1);
                        $write->bindvalue(':data2', $data2);
                        $write->bindvalue(':data3', $data3);
                        $write->bindvalue(':data4', $data4);
                        $write->bindvalue(':data5', $data5);
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