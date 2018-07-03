
<?php
$word = array('id', 'title', 'category', 'situation', 'time', 'area', 'image', 'target', 'aim', 'open', 'create_day', 'play_count', 'assessment0', 'assessment1', 'assessment2', 'assessment3', 'assessment4', 'assessment5', 'assessment6', 'assessment7', 'assessment8', 'assessment9');
$f = 1;
for($i = 1; $i < 4; $i++){
        if(!(isset($_POST[$word[$i]])))
                $f = 0;
}
if(!(isset($_POST[$word[9]])))
        $f = 0;
if(!(isset($_POST[$word[12]])))
        $f = 0;
if($f == 1)
        try {
                $db =new PDO('mysql:host=192.168.0.159;dbname=HUG;','miyashita','sonicdance');

                $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); 
                $sql = 'SELECT * FROM theme';
                $stmt = $db->query($sql);
                $stmt->execute();
                $count=$stmt->rowCount();

                $write=$db->prepare("INSERT INTO theme (id, title, category, situation, time, area, image, target, aim, open, create_day, play_count, assessment0, assessment1, assessment2, assessment3, assessment4, assessment5, assessment6, assessment7, assessment8, assessment9) VALUES(:id, :title,:category, :situation, :time, :area, :image, :target, :aim, :open, :create_day, :play_count, :assessment0, :assessment1, :assessment2, :assessment3, :assessment4, :assessment5, :assessment6, :assessment7, :assessment8, :assessment9)");
                $write->bindvalue(":id", $count + 1);
                for($i = 1; $i < count($word); $i++){
                        switch ($word[$i]) {
                                case 'create_day':
                                        $write->bindvalue(':' . $word[$i], date("Y/m/d"));
                                        break;
                                case 'play_count':
                                        $write->bindvalue(':' . $word[$i], 0);
                                        break;
                                case 'assessment0':case 'assessment1':case 'assessment2':case 'assessment3':case 'assessment4':
                                case 'assessment5':case 'assessment6':case 'assessment7':case 'assessment8':case 'assessment9':
                                        if(empty($_POST[$word[$i]]))
                                                $write->bindvalue(':' . $word[$i], ""); 
                                        else
                                                $write->bindvalue(':' . $word[$i], $_POST[$word[$i]]);  
                                        break;
                                default:
                                        $write->bindvalue(':' . $word[$i], $_POST[$word[$i]]);   
                                        break;
                        }
                }
                $write->execute();
                $db=null;
        } catch (Exception $e) {
                echo "EE";

        }
else if(isset($_POST["mode"]))
        try {
                $db =new PDO('mysql:host=192.168.0.159;dbname=HUG;','miyashita','sonicdance');

                $sqldata = $db->prepare("SELECT id, title, category, create_day, play_count FROM theme ORDER BY create_day DESC LIMIT 5
                ");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                        'id'=>$row['id'],
                        'title'=>$row['title'],
                        'category'=>$row['category'],
                        'create_day'=>$row['create_day'],
                        'play_count'=>$row['play_count']
                        );
                }
                //JSONデータ出力
                header("Content-type: application/json; charset=UTF-8");
                echo json_encode($db_data);
                $db=null;
        } catch (Exception $e) {
        }
else {
        try {
                $db =new PDO('mysql:host=192.168.0.159;dbname=HUG;','miyashita','sonicdance');

                $sqldata = $db->prepare("SELECT id, title, category, situation, time, area, image, target, aim, open, create_day, play_count, assessment0, assessment1, assessment2, assessment3, assessment4, assessment5, assessment6, assessment7, assessment8, assessment9 FROM theme
                ");
                $sqldata->execute();
                while ($row = $sqldata->fetch()) {
                        $db_data[] = array(
                        'id'=>$row['id'],
                        'title'=>$row['title'],
                        'category'=>$row['category'],
                        'situation'=>$row['situation'],
                        'time'=>$row['time'],
                        'area'=>$row['area'],
                        'image'=>$row['image'],
                        'target'=>$row['target'],
                        'aim'=>$row['aim'],
                        'open'=>$row['open'],
                        'create_day'=>$row['create_day'],
                        'play_count'=>$row['play_count'],
                        'assessment0'=>$row['assessment0'],
                        'assessment1'=>$row['assessment1'],
                        'assessment2'=>$row['assessment2'],
                        'assessment3'=>$row['assessment3'],
                        'assessment4'=>$row['assessment4'],
                        'assessment5'=>$row['assessment5'],
                        'assessment6'=>$row['assessment6'],
                        'assessment7'=>$row['assessment7'],
                        'assessment8'=>$row['assessment8'],
                        'assessment9'=>$row['assessment9'],
                );
                }
                //JSONデータ出力
                header("Content-type: application/json; charset=UTF-8");
                echo json_encode($db_data);
                $db=null;
        } catch (Exception $e) {
        }
}
?>