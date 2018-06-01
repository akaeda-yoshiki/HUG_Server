
<?php
if(isset($_POST['id']) && isset($_POST['data1'])){//新規ユーザ登録
        try{
                // 接続
                $db = new PDO('mysql:host=192.168.0.159;dbname=HUG', 'miyashita', 'sonicdance');
                $write=$db->prepare('INSERT INTO share (id, data1, data2, data3, data4, data5) VALUES(:id, :data1, :data2, :data3, :data4, :data5)');
                $write->bindvalue(':id',$_POST['id']);
                $write->bindvalue(':data1',$_POST['data1']);
                $write->bindvalue(':data2',$_POST['data2']);
                $write->bindvalue(':data3',$_POST['data3']);
                $write->bindvalue(':data4',$_POST['data4']);
                $write->bindvalue(':data5',$_POST['data5']);

                $write->execute();
                $db = null;// 切断
        } catch(PDOException $e){ //データベース接続失敗
            echo $e->getMessage();
            exit;
        }
}
print_r($_POST)
        ?>