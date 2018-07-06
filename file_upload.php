<?php
// 一時アップロード先ファイルパス
// $file_tmp = $_FILES["image"]["tmp_name"];

// // 正式保存先ファイルパス
// $file_save = "image/" . $_FILES["image_file"]["name"];

// echo $file_savs;
// // ファイル移動
// $result = @move_uploaded_file($_FILES["image"]["tmp_name"], "image/test.png");
// if ($result === true) {
//         echo "UPLOAD OK";
// } else {
//         echo "UPLOAD NG";
// }

// $url = $_POST["file"];
// $data = file_get_contents($url);
// file_put_contents('image/test.PNG', $url);
if (strlen($_FILES["pic"]["name"]) > 0 ) {
        try {
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG;', 'miyashita', 'sonicdance');

                $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                $sql = 'SELECT * FROM theme';
                $stmt = $db->query($sql);
                $stmt->execute();
                $count = $stmt->rowCount();
                $filename = $_FILES["pic"]["name"];
                if (!move_uploaded_file($_FILES["pic"]["tmp_name"], "image/" . $count . ".jpeg")) {
                        print "アップロードに失敗しました<br>";
                } else {
                        print "name=";
                        print $filename;
                        print "<BR>";
                        print "<IMG src='$filename'>";
                        print "<BR>";
                }
                $db = null;
        } catch (Exception $e) {

        }
}
?>