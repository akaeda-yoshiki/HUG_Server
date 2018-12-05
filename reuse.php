<?php

try {

        $title = "";
        $category = "";
        $situation = "";
        $time = "";
        $area = "";
        $target = "";
        $aim = "";
        $assessment = "";


        if (isset($_POST["title"]))
                $title = $_POST["title"];
        if (isset($_POST["category"]))
                $category = $_POST["category"];
        if (isset($_POST["situation"]))
                $situation = $_POST["situation"];
        if (isset($_POST["time"]))
                $time = $_POST["time"];
        if (isset($_POST["area"]))
                $area = $_POST["area"];
        if (isset($_POST["target"]))
                $target = $_POST["target"];
        if (isset($_POST["aim"]))
                $aim = $_POST["aim"];
        if (isset($_POST["assessment"]))
                $assessment = $_POST["assessment"];

        $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
        $sqldata = $db->prepare("SELECT theme_id, code FROM eventcode, theme WHERE eventcode.open = 'ok' AND theme.id = eventcode.theme_id AND theme.title LIKE '%$title%' AND theme.category LIKE '%$category%' AND theme.situation LIKE '%$situation%' AND theme.time LIKE '%$time%' AND theme.area LIKE '%$area%' AND theme.target LIKE '%$target%' AND theme.aim LIKE '%$aim%' AND (theme.assessment0 LIKE '%$assessment%' OR theme.assessment1 LIKE '%$assessment%' OR theme.assessment2 LIKE '%$assessment%' OR theme.assessment3 LIKE '%$assessment%' OR theme.assessment4 LIKE '%$assessment%' OR theme.assessment5 LIKE '%$assessment%' OR theme.assessment6 LIKE '%$assessment%' OR theme.assessment7 LIKE '%$assessment%' OR theme.assessment8 LIKE '%$assessment%' OR theme.assessment9 LIKE '%$assessment%')");
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
                $sqldata = $db->prepare("SELECT num, id, data1, data2, data3, data4, data5 FROM  `{$code}` WHERE  (`{$code}`.id = 2 OR `{$code}`.id = 3) ");
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
