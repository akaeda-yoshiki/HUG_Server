
<?php
// print_r($_POST);

if(isset($_POST['info_id']) && isset($_POST['data1']) && isset($_POST['number'])){//新規ユーザ登録
        try{
                // 接続
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG', 'miyashita', 'sonicdance');
                $sqldata = $db->prepare('DELETE FROM share WHERE number=?');
                $sqldata->execute(array($_POST["number"]));
                
                
                $write=$db->prepare('INSERT INTO share (info_id, number, data1, data2, data3, data4) VALUES(:info_id, :number, :data1, :data2, :data3, :data4)');
                $write->bindvalue(':info_id',$_POST['info_id']);
                $write->bindvalue(':number',$_POST['number']);
                $write->bindvalue(':data1',$_POST['data1']);
                $write->bindvalue(':data2',$_POST['data2']);
                $write->bindvalue(':data3',$_POST['data3']);
                $write->bindvalue(':data4',$_POST['data4']);

                $write->execute();
                $db = null;// 切断
        } catch(PDOException $e){ //データベース接続失敗
        //     echo $e->getMessage();
            exit;
        }
}
        ?>