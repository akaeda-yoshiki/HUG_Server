<?php
if (isset($_POST["mode"])) {//テーマ一覧・5つを表示するのに必要な情報の読み込み、ソート・絞り込み
        try {
                $title = "";
                $category = "";
                $situation = "";
                $time = "";
                $area = "";
                $target = "";
                $aim = "";
                $assessment = "";
                $search_code = "";



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
                if (isset($_POST["search_code"]))
                        $search_code = $_POST["search_code"];

                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');
                if (!(isset($_POST["mail"])) && isset($_POST["not_mail"])) {//全件
                        $not_mail = $_POST["not_mail"];
        //ソート：作成日
                        if (strcmp($_POST["mode"], "day") == 0)
                                $sqldata = $db->prepare("SELECT id, title, category, play_day, play_count,code FROM theme, eventcode WHERE eventcode.stage = 2 AND eventcode.open = 'ok' AND eventcode.theme_id = theme.id AND  eventcode.mail != '$not_mail' AND theme.open = '公開' AND theme.title LIKE '%$title%' AND theme.category LIKE '%$category%' AND theme.situation LIKE '%$situation%' AND theme.time LIKE '%$time%' AND theme.area LIKE '%$area%' AND theme.target LIKE '%$target%' AND eventcode.code LIKE '%$search_code%' AND theme.aim LIKE '%$aim%' AND (theme.assessment0 LIKE '%$assessment%' OR theme.assessment1 LIKE '%$assessment%' OR theme.assessment2 LIKE '%$assessment%' OR theme.assessment3 LIKE '%$assessment%' OR theme.assessment4 LIKE '%$assessment%' OR theme.assessment5 LIKE '%$assessment%' OR theme.assessment6 LIKE '%$assessment%' OR theme.assessment7 LIKE '%$assessment%' OR theme.assessment8 LIKE '%$assessment%' OR theme.assessment9 LIKE '%$assessment%') ORDER BY play_day DESC LIMIT 5");

        //ソート：作成日（全件）
                        else if (strcmp($_POST["mode"], "all_day") == 0)
                                $sqldata = $db->prepare("SELECT id, title, category, play_day, play_count,code FROM theme, eventcode WHERE eventcode.stage = 2 AND eventcode.open = 'ok' AND eventcode.theme_id = theme.id AND  eventcode.mail != '$not_mail' AND theme.open = '公開' AND theme.title LIKE '%$title%' AND theme.category LIKE '%$category%' AND theme.situation LIKE '%$situation%' AND theme.time LIKE '%$time%' AND theme.area LIKE '%$area%' AND theme.target LIKE '%$target%' AND eventcode.code LIKE '%$search_code%' AND theme.aim LIKE '%$aim%' AND (theme.assessment0 LIKE '%$assessment%' OR theme.assessment1 LIKE '%$assessment%' OR theme.assessment2 LIKE '%$assessment%' OR theme.assessment3 LIKE '%$assessment%' OR theme.assessment4 LIKE '%$assessment%' OR theme.assessment5 LIKE '%$assessment%' OR theme.assessment6 LIKE '%$assessment%' OR theme.assessment7 LIKE '%$assessment%' OR theme.assessment8 LIKE '%$assessment%' OR theme.assessment9 LIKE '%$assessment%') ORDER BY play_day DESC");
                
                //ソート：プレイ回数
                        else if (strcmp($_POST["mode"], "play") == 0)
                                $sqldata = $db->prepare("SELECT id, title, category, play_day, play_count,code FROM theme, eventcode WHERE eventcode.stage = 2 AND eventcode.open = 'ok' AND eventcode.theme_id = theme.id AND  eventcode.mail != '$not_mail' AND theme.open = '公開' AND theme.title LIKE '%$title%' AND theme.category LIKE '%$category%' AND theme.situation LIKE '%$situation%' AND theme.time LIKE '%$time%' AND theme.area LIKE '%$area%' AND theme.target LIKE '%$target%' AND eventcode.code LIKE '%$search_code%' AND theme.aim LIKE '%$aim%' AND (theme.assessment0 LIKE '%$assessment%' OR theme.assessment1 LIKE '%$assessment%' OR theme.assessment2 LIKE '%$assessment%' OR theme.assessment3 LIKE '%$assessment%' OR theme.assessment4 LIKE '%$assessment%' OR theme.assessment5 LIKE '%$assessment%' OR theme.assessment6 LIKE '%$assessment%' OR theme.assessment7 LIKE '%$assessment%' OR theme.assessment8 LIKE '%$assessment%' OR theme.assessment9 LIKE '%$assessment%') ORDER BY play_count DESC LIMIT 5");

                //ソート：プレイ回数（全件）
                        else if (strcmp($_POST["mode"], "all_play") == 0)
                                $sqldata = $db->prepare("SELECT id, title, category, play_day, play_count,code FROM theme, eventcode WHERE eventcode.stage = 2 AND eventcode.open = 'ok' AND eventcode.theme_id = theme.id AND  eventcode.mail != '$not_mail' AND theme.open = '公開' AND theme.title LIKE '%$title%' AND theme.category LIKE '%$category%' AND theme.situation LIKE '%$situation%' AND theme.time LIKE '%$time%' AND theme.area LIKE '%$area%' AND theme.target LIKE '%$target%' AND eventcode.code LIKE '%$search_code%' AND theme.aim LIKE '%$aim%' AND (theme.assessment0 LIKE '%$assessment%' OR theme.assessment1 LIKE '%$assessment%' OR theme.assessment2 LIKE '%$assessment%' OR theme.assessment3 LIKE '%$assessment%' OR theme.assessment4 LIKE '%$assessment%' OR theme.assessment5 LIKE '%$assessment%' OR theme.assessment6 LIKE '%$assessment%' OR theme.assessment7 LIKE '%$assessment%' OR theme.assessment8 LIKE '%$assessment%' OR theme.assessment9 LIKE '%$assessment%') ORDER BY play_count DESC");
                        $sqldata->execute();
                        while ($row = $sqldata->fetch()) {
                                $db_data[] = array(
                                        'id' => $row['id'],
                                        'title' => $row['title'],
                                        'category' => $row['category'],
                                        'play_day' => $row['play_day'],
                                        'play_count' => $row['play_count'],
                                        'code' => $row['code']
                                );
                        }
                } else {//自分が作成したテーマ


                        $mail = $_POST["mail"];
                //ソート：作成日
                        if (strcmp($_POST["mode"], "day") == 0)
                                $sqldata = $db->prepare("SELECT id, title, category, play_day, play_count, code  FROM theme, eventcode WHERE eventcode.stage = 2 AND eventcode.open = 'ok' AND eventcode.theme_id = theme.id AND theme.open = '公開' AND theme.title LIKE '%$title%' AND theme.category LIKE '%$category%' AND theme.situation LIKE '%$situation%' AND theme.time LIKE '%$time%' AND theme.area LIKE '%$area%' AND theme.target LIKE '%$target%' AND eventcode.code LIKE '%$search_code%' AND theme.aim LIKE '%$aim%' AND (theme.assessment0 LIKE '%$assessment%' OR theme.assessment1 LIKE '%$assessment%' OR theme.assessment2 LIKE '%$assessment%' OR theme.assessment3 LIKE '%$assessment%' OR theme.assessment4 LIKE '%$assessment%' OR theme.assessment5 LIKE '%$assessment%' OR theme.assessment6 LIKE '%$assessment%' OR theme.assessment7 LIKE '%$assessment%' OR theme.assessment8 LIKE '%$assessment%' OR theme.assessment9 LIKE '%$assessment%') ORDER BY play_day DESC LIMIT 5");

        //ソート：作成日（全件）
                        else if (strcmp($_POST["mode"], "all_day") == 0)
                                $sqldata = $db->prepare("SELECT id, title, category, play_day, play_count, code  FROM theme, eventcode WHERE eventcode.stage = 2 AND eventcode.open = 'ok' AND eventcode.theme_id = theme.id AND theme.open = '公開' AND theme.title LIKE '%$title%' AND theme.category LIKE '%$category%' AND theme.situation LIKE '%$situation%' AND theme.time LIKE '%$time%' AND theme.area LIKE '%$area%' AND theme.target LIKE '%$target%' AND eventcode.code LIKE '%$search_code%' AND theme.aim LIKE '%$aim%' AND (theme.assessment0 LIKE '%$assessment%' OR theme.assessment1 LIKE '%$assessment%' OR theme.assessment2 LIKE '%$assessment%' OR theme.assessment3 LIKE '%$assessment%' OR theme.assessment4 LIKE '%$assessment%' OR theme.assessment5 LIKE '%$assessment%' OR theme.assessment6 LIKE '%$assessment%' OR theme.assessment7 LIKE '%$assessment%' OR theme.assessment8 LIKE '%$assessment%' OR theme.assessment9 LIKE '%$assessment%') ORDER BY play_day DESC");
                
                //ソート：プレイ回数
                        else if (strcmp($_POST["mode"], "play") == 0)
                                $sqldata = $db->prepare("SELECT id, title, category, play_day, play_count, code  FROM theme, eventcode WHERE eventcode.stage = 2 AND eventcode.open = 'ok' AND eventcode.theme_id = theme.id AND theme.open = '公開' AND theme.title LIKE '%$title%' AND theme.category LIKE '%$category%' AND theme.situation LIKE '%$situation%' AND theme.time LIKE '%$time%' AND theme.area LIKE '%$area%' AND theme.target LIKE '%$target%' AND eventcode.code LIKE '%$search_code%' AND theme.aim LIKE '%$aim%' AND (theme.assessment0 LIKE '%$assessment%' OR theme.assessment1 LIKE '%$assessment%' OR theme.assessment2 LIKE '%$assessment%' OR theme.assessment3 LIKE '%$assessment%' OR theme.assessment4 LIKE '%$assessment%' OR theme.assessment5 LIKE '%$assessment%' OR theme.assessment6 LIKE '%$assessment%' OR theme.assessment7 LIKE '%$assessment%' OR theme.assessment8 LIKE '%$assessment%' OR theme.assessment9 LIKE '%$assessment%') ORDER BY play_count DESC LIMIT 5");

                //ソート：プレイ回数（全件）
                        else if (strcmp($_POST["mode"], "all_play") == 0)
                                $sqldata = $db->prepare("SELECT id, title, category, play_day, play_count, code  FROM theme, eventcode WHERE eventcode.stage = 2 AND eventcode.open = 'ok' AND eventcode.theme_id = theme.id AND theme.open = '公開' AND theme.title LIKE '%$title%' AND theme.category LIKE '%$category%' AND theme.situation LIKE '%$situation%' AND theme.time LIKE '%$time%' AND theme.area LIKE '%$area%' AND theme.target LIKE '%$target%' AND eventcode.code LIKE '%$search_code%' AND theme.aim LIKE '%$aim%' AND (theme.assessment0 LIKE '%$assessment%' OR theme.assessment1 LIKE '%$assessment%' OR theme.assessment2 LIKE '%$assessment%' OR theme.assessment3 LIKE '%$assessment%' OR theme.assessment4 LIKE '%$assessment%' OR theme.assessment5 LIKE '%$assessment%' OR theme.assessment6 LIKE '%$assessment%' OR theme.assessment7 LIKE '%$assessment%' OR theme.assessment8 LIKE '%$assessment%' OR theme.assessment9 LIKE '%$assessment%') ORDER BY play_count DESC");
                        $sqldata->execute();
                        while ($row = $sqldata->fetch()) {
                                $db_data[] = array(
                                        'id' => $row['id'],
                                        'title' => $row['title'],
                                        'category' => $row['category'],
                                        'play_day' => $row['play_day'],
                                        'play_count' => $row['play_count'],
                                        'code' => $row['code']
                                );
                        }
                }
                for ($i = 0; $i < count($db_data); $i++) {
                        $code = $db_data[$i]["code"];
                        $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                        $stmt = $db->prepare("SELECT * FROM `{$code}` WHERE id = '7'");
                        $stmt->execute();
                        $count = $stmt->rowCount();
                        $db_data[$i]["comment_sum"] = $count;
                }

       
                //JSONデータ出力
                header("Content-type: application/json; charset=UTF-8");
                echo json_encode($db_data);
                $db = null;
        } catch (Exception $e) {
        }
} else if (isset($_POST['code'])) {
        $code = $_POST['code'];
        try {
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

                $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                $stmt = $db->prepare("SELECT * FROM `{$code}` WHERE id = '7'");
                $stmt->execute();
                $count = $stmt->rowCount();
                echo $count;
                $db = null;// 切断
        } catch (PDOException $e) { //データベース接続失敗
        //     echo $e->getMessage();
                exit;
        }
}
?>