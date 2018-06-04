
<?php
try {
        $db = new PDO('mysql:host=192.168.0.159;dbname=HUG', 'miyashita', 'sonicdance');

        $db = $db->prepare('SELECT *FROM share');
        $db->execute();

        while ($row = $db->fetch()) {
                $db_data[] = array(
                'info_id'=>$row['info_id'],
                'number'=>$row['number'],
                'data1'=>$row['data1'],
                'data2'=>$row['data2'],
                'data3'=>$row['data3'],
                'data4'=>$row['data4']
        );
      }

    //JSONデータ出力
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($db_data);
    $db=null;
  } catch (Exception $e) {
}
?>