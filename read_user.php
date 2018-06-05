
<?php
try {
    $db =new PDO('mysql:host=192.168.0.159;dbname=HUG;','miyashita','sonicdance');

   $sqldata = $db->prepare('SELECT name, role FROM user');
   $sqldata->execute();

   while ($row = $sqldata->fetch()) {
        $db_data[] = array(
                'name'=>$row['name'],
                'role'=>$row['role']
                    );
      }

    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($db_data);
    $db=null;
  } catch (Exception $e) {
}
?>