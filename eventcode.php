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

        // echo $code;


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
}
?>