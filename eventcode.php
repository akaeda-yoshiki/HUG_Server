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
		id VARCHAR(45) PRIMARY KEY,
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

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
                $sqldata = $db->prepare("SELECT id FROM `{$code}` WHERE data2 = '$role'");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                'code' => $row['code']
                        );
                }
        // echo $db_data;
                if (empty($db_data)) {
                // echo($code);
                // 挿入***********************************************
                        $write = $db->prepare("INSERT INTO `{$code}` (id, data1, data2, data3, data4, data5) VALUES(:id, :data1, :data2, :data3, :data4, :data5)");
                        $write->bindvalue(':id', 1);
                        $write->bindvalue(':data1', $role);
                        $write->bindvalue(':data2', $_POST['mail']);
                        $write->bindvalue(':data3', "");
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
                // echo $id;
                // if(empty($db_data)){
                //         echo("oo");
                // }
                // else{
                //         echo("pp");
                // }
                $sqldata = $db->prepare("SELECT id, title, category, situation, time, area, image, target, aim, open, create_day, play_count, assessment0, assessment1, assessment2, assessment3, assessment4, assessment5, assessment6, assessment7, assessment8, assessment9 FROM theme WHERE theme.id = '$id'");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                'id' => $row['id'],
                                'title' => $row['title'],
                                'category' => $row['category'],
                                'situation' => $row['situation'],
                                'time' => $row['time'],
                                'area' => $row['area'],
                                'image' => $row['image'],
                                'target' => $row['target'],
                                'aim' => $row['aim'],
                                'open' => $row['open'],
                                'create_day' => $row['create_day'],
                                'play_count' => $row['play_count'],
                                'assessment0' => $row['assessment0'],
                                'assessment1' => $row['assessment1'],
                                'assessment2' => $row['assessment2'],
                                'assessment3' => $row['assessment3'],
                                'assessment4' => $row['assessment4'],
                                'assessment5' => $row['assessment5'],
                                'assessment6' => $row['assessment6'],
                                'assessment7' => $row['assessment7'],
                                'assessment8' => $row['assessment8'],
                                'assessment9' => $row['assessment9'],
                        );
                }
                //JSONデータ出力
                header("Content-type: application/json; charset=UTF-8");
                echo json_encode($db_data);
                $db = null;
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
        }
}

?>