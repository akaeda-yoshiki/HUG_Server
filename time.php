<?php
$mode = "";
if (isset($_POST['mode']))
        $mode = $_POST['mode'];

        // 現在の時刻取得
$now_time = array(
        "year" => date("Y"),
        "month" => date("m"),
        "day" => date("d"),
        "hour" => date("G"),
        "minute" => date("i"),
        "second" => date("s")
);

// ゲーム開始時に時刻登録
if (isset($_POST['code']) && strcmp($mode, "new") == 0) {

        $code = $_POST['code'];
        try {
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

                $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                $stmt = $db->prepare("SELECT * FROM `{$code}`");
                $stmt->execute();
                $count = $stmt->rowCount();
                $write = $db->prepare("INSERT INTO `{$code}` (num, id, data1, data2, data3, data4, data5) VALUES(:num, :id, :data1, :data2, :data3, :data4, :data5)");
                $write->bindvalue(':num', $count);
                $write->bindvalue(':id', "0");
                $write->bindvalue(':data1', $now_time["hour"] . "_" . $now_time["minute"] . "_" . $now_time["second"]);
                $write->bindvalue(':data2', "0_0_0");
                $write->bindvalue(':data3', "0_0_0");
                $write->bindvalue(':data4', $now_time["year"] . "/" . $now_time["month"] . "/" . $now_time["day"]);
                $write->bindvalue(':data5', "");
                $write->execute();

                // ゲーム状況（stage）とプレイ日時を更新
                $day = date("Y/m/d");
                $sqldata = $db->prepare("UPDATE  eventcode set stage = 1 WHERE code = '$code'");
                $sqldata->execute();
                $sqldata = $db->prepare("UPDATE  eventcode set play_day = '$day' WHERE code = '$code'");
                $sqldata->execute();

                // プレイ回数を更新
                $sqldata1 = $db->prepare("SELECT theme_id, play_count FROM eventcode, theme WHERE eventcode.code = '$code' AND theme.id = eventcode.theme_id");
                $sqldata1->execute();
                while ($row = $sqldata1->fetch()) {
                        $db_data[] = array(
                                'theme_id' => $row['theme_id'],
                                'play_count' => $row['play_count']
                        );
                }
                $theme_id = $db_data[0]["theme_id"];
                $play_count = $db_data[0]["play_count"] + 1;
                $sqldata = $db->prepare("UPDATE  theme set play_count = '$play_count'  WHERE id = '$theme_id'");
                $sqldata->execute();


                $db = null;// 切断
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
                exit;
        }
} else if (isset($_POST['code']) && strcmp($mode, "update") == 0) {//プレイ中の経過時間更新

        $code = $_POST['code'];
        try {
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');


                $sqldata = $db->prepare("SELECT data1, data2 FROM `{$code}` WHERE id = 0");
                $sqldata->execute();

                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                                'data1' => $row['data1'],
                                'data2' => $row['data2']
                        );
                }

                $start_time = explode("_", $db_data[0]["data1"]);
                $last_time = explode("_", $db_data[0]["data2"]);
                $pass_time = array(
                        "hour" => $now_time["hour"] - $start_time[0],
                        "minute" => $now_time["minute"] - $start_time[1],
                        "second" => $now_time["second"] - $start_time[2]
                );

                if ($pass_time["second"] < 0) {
                        $pass_time["minute"]--;
                        $pass_time["second"] = 60 + $pass_time["second"];
                }
                if ($pass_time["minute"] < 0) {
                        $pass_time["hour"]--;
                        $pass_time["minute"] = 60 + $pass_time["minute"];
                }
                if ($pass_time["hour"] < 0) {
                        $pass_time["hour"] = 24 + $pass_time["hour"];
                }

                $pass_time_conect = implode("_", $pass_time);
                $last_time = implode("_", $last_time);
                $sqldata = $db->prepare("UPDATE  `{$code}` set data2 = '$pass_time_conect', data3 = '$last_time' WHERE  `{$code}`.id = 0");
                $sqldata->execute();
                header("Content-type: application/json; charset=UTF-8");
                echo json_encode($pass_time);
                $db = null;// 切断
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
                exit;
        }
} else if (isset($_POST['code']) && strcmp($mode, "finish") == 0) {//ゲーム終了合図データの登録
        $code = $_POST['code'];

        $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
        $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $stmt = $db->prepare("SELECT * FROM `{$code}`");
        $stmt->execute();
        $count = $stmt->rowCount();
        $write = $db->prepare("INSERT INTO `{$code}` (num, id, data1, data2, data3, data4, data5) VALUES(:num, :id, :data1, :data2, :data3, :data4, :data5)");
        $write->bindvalue(':num', $count);
        $write->bindvalue(':id', "6");
        $write->bindvalue(':data1', "");
        $write->bindvalue(':data2', "");
        $write->bindvalue(':data3', "");
        $write->bindvalue(':data4', "");
        $write->bindvalue(':data5', "");
        $write->execute();

        $sqldata = $db->prepare("UPDATE  eventcode set stage = 2 WHERE code = '$code'");
        $sqldata->execute();
}

?>