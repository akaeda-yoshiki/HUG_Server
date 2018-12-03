<?php



try {


        $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
        $sqldata = $db->prepare("SELECT theme_id, code FROM eventcode WHERE eventcode.open = 'ok'");
        $sqldata->execute();
        while ($row = $sqldata->fetch()) {
                $db_data[] = array(
                        'theme_id' => $row['theme_id'],
                        'code' => $row['code']
                );
        }
        $senddate = array();
        for ($i = 0; $i < count($db_data); $i++) {
                $code = $db_data[$i]['code'];
                $sqldata = $db->prepare("SELECT num, id, data1, data2, data3, data4, data5 FROM  `{$code}` WHERE  `{$code}`.id = 2 OR `{$code}`.id = 3");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data1[] = array(
                                'theme_id' => $db_data[$i]["theme_id"],
                                'code' => $db_data[$i]['code'],
                                "num" => $row['num'],
                                "id" => $row['id'],
                                'data1' => $row['data1'],
                                'data2' => $row['data2'],
                                'data3' => $row['data3'],
                                'data4' => $row['data4'],
                                'data5' => $row['data5']
                        );
                }
                $senddate += $db_data1;
                // print_r($db_data1);
        }
        // echo $db_data1;
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode($senddate);
        $db = null;
} catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
}



?>
