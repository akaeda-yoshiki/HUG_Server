<?php
// 新規登録
if (isset($_POST['word0']) || isset($_POST['word1']) || isset($_POST['word2']) || isset($_POST['word3']))
        try {
        $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

        for ($i = 0; $i < 4; $i++)//データが送られてきてればデータベースへ挿入
                if (!empty($_POST['word' . "$i"])) {
                $write = $db->prepare('INSERT INTO master_assessment (word) VALUES(:word)');
                $write->bindvalue(':word', $_POST['word' . "$i"]);
                $write->execute();
        }

        $db = null;
} catch (Exception $e) {
} else//評価項目の読み込み
try {
        $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

        $sqldata = $db->prepare("SELECT word FROM master_assessment");
        $sqldata->execute();
        while ($row = $sqldata->fetch()) {
                $db_data[] = array(
                        'word' => $row['word']
                );
        }
        //JSONデータ出力
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode($db_data);
        $db = null;
} catch (Exception $e) {
}
?>