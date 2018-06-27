<?php
if(isset($_POST['word']))
        try {
                $db =new PDO('mysql:host=192.168.0.159;dbname=HUG;','miyashita','sonicdance');
        
                $write=$db->prepare('INSERT INTO master_category (word) VALUES(:word)');
                $write->bindvalue(':word',$_POST['word']); 
                $write->execute();

                $db=null;
        } catch (Exception $e) {
        }
else
        try {
        $db =new PDO('mysql:host=192.168.0.159;dbname=HUG;','miyashita','sonicdance');

        $sqldata = $db->prepare("SELECT word FROM master_category");
        $sqldata->execute();
        while ($row = $sqldata->fetch()) {
        $db_data[] = array(
                'word'=>$row['word']
                );
        }
        //JSONデータ出力
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode($db_data);
        $db=null;
        } catch (Exception $e) {
        }
?>