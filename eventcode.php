
<?php
$mode = "";
if (isset($_POST['mode']))
        $mode = $_POST['mode'];

        //イベントコードの生成******************************************************************************
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
		data1 VARCHAR(100),
                data2 VARCHAR(45),
                data3 VARCHAR(45),
                data4 VARCHAR(45),
                data5 VARCHAR(45)
	) engine=innodb default charset=utf8";

	// SQL実行
                $create = $db->prepare($sql);
                $create->execute();

                echo $code;

                // mkdir("image/$code", 777);
                $db = null;// 切断
        } catch (PDOException $e) { //データベース接続失敗
//     echo $e->getMessage();
                exit;
        }
        // 引数のイベントコードへの新規データ挿入******************************************************************************
} else if (isset($_POST['code']) && strcmp($mode, "event_confirm") == 0) {

        $code = $_POST['code'];
        try {
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

                $sqldata = $db->prepare("SELECT code FROM eventcode WHERE '$code' = eventcode.code");
                $sqldata->execute();

                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                'code' => $row['code']
                        );
                }

                if (!empty($db_data)) {
                        $sqldata = $db->prepare("SELECT stage FROM eventcode WHERE '$code' = eventcode.code");
                        $sqldata->execute();
                        while ($row = $sqldata->fetch()) {
                                $db_data1[] = array(
                                        'stage' => $row['stage']
                                );
                        }
                        if ($db_data1[0]["stage"] == 0)
                                echo "ok";
                        else
                                echo "play_now";
                } else
                        echo "no";


                $db = null;// 切断
        } catch (PDOException $e) { //データベース接続失敗
                //     echo $e->getMessage();
                exit;
        }
} else if (isset($_POST['code']) && isset($_POST['data1']) && isset($_POST['id']) && strcmp($mode, "insert") == 0) {
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
                $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                $stmt = $db->prepare("SELECT * FROM `{$code}`");
                $stmt->execute();
                $count = $stmt->rowCount();
                $insert_flag = true;
                switch ($id) {
                        case 1:
                                // echo ("メンバー登録");
                                $sqldata = $db->prepare("SELECT id FROM `{$code}` WHERE `{$code}`.data1 = '$data1' AND `{$code}`.id = 1");
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
                        case 2:
                        case 4:
                                if (!empty($data3))
                                        $data3 = $data3 . "_" . $count;
                                break;
                        case 5:
                                $stmt = $db->prepare("SELECT * FROM `{$code}` WHERE id = 5");
                                $stmt->execute();
                                $data2 = $stmt->rowCount();
                                break;
                }
                if ($insert_flag) {


                        
                        //  echo($count);
                // 挿入***********************************************
                        $write = $db->prepare("INSERT INTO `{$code}` (num, id, data1, data2, data3, data4, data5) VALUES(:num, :id, :data1, :data2, :data3, :data4, :data5)");
                        // $write->bindvalue(':num', str_pad($count, 4, 0, STR_PAD_LEFT));
                        $write->bindvalue(':num', $count);
                        $write->bindvalue(':id', $id);
                        $write->bindvalue(':data1', $data1);
                        $write->bindvalue(':data2', $data2);
                        $write->bindvalue(':data3', $data3);
                        $write->bindvalue(':data4', $data4);
                        $write->bindvalue(':data5', $data5);
                        $write->execute();
                        if ($id == 2 || $id == 4)
                                echo "ok/" . $count;
                        else
                                echo "ok";
                }
                $db = null;
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
                exit;
        }
        // 引数のイベントコードのデータを削除******************************************************************************
} else if (isset($_POST['code']) && isset($_POST['id']) && strcmp($mode, "updata") == 0) {
        try {

                $data = "";
                $data1 = "";
                $data2 = "";
                $data3 = "";
                if (isset($_POST['data']))
                        $data = $_POST['data'];
                if (isset($_POST['data1']))
                        $data1 = $_POST['data1'];
                if (isset($_POST['data2']))
                        $data2 = $_POST['data2'];
                if (isset($_POST['data3']))
                        $data3 = $_POST['data3'];
                $id = $_POST['id'];
                $code = $_POST['code'];

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

                $insert_flag = true;
                switch ($id) {
                        case 1:
                                $sqldata = $db->prepare("UPDATE  `{$code}` set id = -1 WHERE  `{$code}`.id = '$id' AND `{$code}`.data2 = '$data' AND `{$code}`.data1 = '$data1'");
                                $sqldata->execute();
                                break;
                        case 4:
                                $sqldata1 = $db->prepare("UPDATE  `{$code}` set data5 = 1 WHERE num = '$data'");
                                $sqldata1->execute();
                                break;
                        case 5:
                                $num = "";
                                if (isset($_POST['num']))
                                        $num = $_POST['num'];
                                // if (strcmp($data, "") != 0) {
                                //         $sqldata1 = $db->prepare("UPDATE  `{$code}` set data1 = '$data' WHERE data2 = '$num' AND id = '$id'");
                                //         $sqldata1->execute();
                                // }
                                // if (strcmp($data1, "") != 0) {
                                //         $sqldata1 = $db->prepare("UPDATE  `{$code}` set data3 = '$data1' WHERE data2 = '$num' AND id = '$id'");
                                //         $sqldata1->execute();
                                // }
                                // if (strcmp($data2, "") != 0) {
                                //         $sqldata1 = $db->prepare("UPDATE  `{$code}` set data4 = '$data2' WHERE data2 = '$num' AND id = '$id'");
                                //         $sqldata1->execute();
                                // }
                                // if (strcmp($data3, "") != 0) {
                                //         $sqldata1 = $db->prepare("UPDATE  `{$code}` set data5 = '$data2' WHERE data2 = '$num' AND id = '$id'");
                                //         $sqldata1->execute();
                                // }
                                $sqldata1 = $db->prepare("UPDATE  `{$code}` set data1 = '$data' WHERE data1 != '$data' AND data2 = '$num' AND id = '$id'");
                                $sqldata1->execute();
                                $sqldata1 = $db->prepare("UPDATE  `{$code}` set data3 = '$data1' WHERE data3 != '$data1' AND data2 = '$num' AND id = '$id'");
                                $sqldata1->execute();
                                $sqldata1 = $db->prepare("UPDATE  `{$code}` set data4 = '$data2' WHERE data4 != '$data2' AND data2 = '$num' AND id = '$id'");
                                $sqldata1->execute();
                                $sqldata1 = $db->prepare("UPDATE  `{$code}` set data5 = '$data2' WHERE data5 != '$data3' AND data2 = '$num' AND id = '$id'");
                                $sqldata1->execute();
                                break;
                }
                $db = null;
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
                exit;
        }
        // 指定IDのデータを読み込む
} else if (isset($_POST['code']) && isset($_POST['num']) && strcmp($mode, "trace") == 0) {
        try {

                $data = "";
                $data1 = "";
                if (isset($_POST['data']))
                        $data = $_POST['data'];
                if (isset($_POST['data1']))
                        $data1 = $_POST['data1'];
                $num = $_POST['num'];
                $code = $_POST['code'];

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

                $sqldata = $db->prepare("SELECT num, id, data1, data2, data3, data4, data5 FROM  `{$code}` WHERE  `{$code}`.num = '$num'");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                "num" => $row['num'],
                                "id" => $row['id'],
                                'data1' => $row['data1'],
                                'data2' => $row['data2'],
                                'data3' => $row['data3'],
                                'data4' => $row['data4'],
                                'data5' => $row['data5']
                        );
                }

                $insert_flag = true;

                switch ($db_data[0]["id"]) {
                        case 2:
                        case 3:
                                $trace_num = $db_data[0]["num"];
                                $sqldata = $db->prepare("SELECT num, id, data1, data2, data3, data4, data5 FROM  `{$code}` WHERE  `{$code}`.data2 = '$trace_num' AND id = 4");
                                $sqldata->execute();
                                break;
                        case 4:
                                $trace_num = $db_data[0]["data2"];
                                $sqldata = $db->prepare("SELECT num, id, data1, data2, data3, data4, data5 FROM  `{$code}` WHERE  `{$code}`.num = '$trace_num'");
                                $sqldata->execute();

                                while ($row = $sqldata->fetch()) {
                                        $db_data2[] = array(
                                                "num" => $row['num'],
                                                "id" => $row['id'],
                                                'data1' => $row['data1'],
                                                'data2' => $row['data2'],
                                                'data3' => $row['data3'],
                                                'data4' => $row['data4'],
                                                'data5' => $row['data5']
                                        );
                                }
                                $db_data = $db_data2;
                                $trace_num = $db_data2[0]["num"];
                                $sqldata = $db->prepare("SELECT num, id, data1, data2, data3, data4, data5 FROM  `{$code}` WHERE  `{$code}`.data2 = '$trace_num' AND id = 4");
                                $sqldata->execute();
                                break;
                }

                while ($row = $sqldata->fetch()) {
                        $db_data1[] = array(
                                "num" => $row['num'],
                                "id" => $row['id'],
                                'data1' => $row['data1'],
                                'data2' => $row['data2'],
                                'data3' => $row['data3'],
                                'data4' => $row['data4'],
                                'data5' => $row['data5']
                        );
                }
                header("Content-type: application/json; charset=UTF-8");
                echo json_encode(array_merge($db_data, $db_data1));
                $db = null;
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
                exit;
        }
        // 指定IDのデータを読み込む
} else if (isset($_POST['code']) && isset($_POST['id'])) {
        try {
                $id = $_POST['id'];
                $code = $_POST['code'];

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

                if ($id == "-1")
                        $sqldata = $db->prepare("SELECT num, id, data1, data2, data3, data4, data5 FROM  `{$code}` ");
                else
                        $sqldata = $db->prepare("SELECT num, id, data1, data2, data3, data4, data5 FROM  `{$code}` WHERE  `{$code}`.id = '$id'");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                "num" => $row['num'],
                                "id" => $row['id'],
                                'data1' => $row['data1'],
                                'data2' => $row['data2'],
                                'data3' => $row['data3'],
                                'data4' => $row['data4'],
                                'data5' => $row['data5']
                        );
                }
                header("Content-type: application/json; charset=UTF-8");
                echo json_encode($db_data);
                $db = null;

        } catch (PDOException $e) { //データベース接続失敗
                echo $e->getMessage();
        }
        // イベントコードからテーマIDを読み込む******************************************************************************
} else if (isset($_POST['code']) && isset($_POST['num'])) {
        try {
                // $num = str_pad($_POST['num'], 4, 0, STR_PAD_LEFT);
                $num = $_POST['num'];
                $code = $_POST['code'];

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
                $sqldata = $db->prepare("SELECT num, id, data1, data2, data3, data4, data5 FROM  `{$code}` WHERE  `{$code}`.num = '$num'");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                "num" => $row['num'],
                                "id" => $row['id'],
                                'data1' => $row['data1'],
                                'data2' => $row['data2'],
                                'data3' => $row['data3'],
                                'data4' => $row['data4'],
                                'data5' => $row['data5']
                        );
                }
                header("Content-type: application/json; charset=UTF-8");
                echo json_encode($db_data);
                $db = null;

        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
        }
        // イベントコードからテーマIDを読み込む******************************************************************************
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