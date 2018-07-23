<?php

if (isset($_POST['theme_id']) && isset($_POST['mail']) && isset($_POST['open'])) {
        $loop = 1;
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

                if (empty($$db_data)) {
                        // 挿入***********************************************
                        $loop = 0;
                }
        }
}
?>