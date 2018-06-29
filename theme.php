
<?php
//if()
        try {
                $db =new PDO('mysql:host=192.168.0.159;dbname=HUG;','miyashita','sonicdance');

                $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); 
                $sql = 'SELECT * FROM theme';
                $stmt = $db->query($sql);
                $stmt->execute();
                $count=$stmt->rowCount();

                $word = array('id', 'title', 'category', 'situation', 'time', 'area', 'image', 'target', 'aim', 'open', 'create_day', 'play_count', 'assessment0', 'assessment1', 'assessment2', 'assessment3', 'assessment4', 'assessment5', 'assessment6', 'assessment7', 'assessment8', 'assessment9');
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
// else
//         try {
//         $db =new PDO('mysql:host=192.168.0.159;dbname=HUG;','miyashita','sonicdance');

//         $sqldata = $db->prepare("SELECT word FROM master_category");
//         $sqldata->execute();
//         while ($row = $sqldata->fetch()) {
//         $db_data[] = array(
//                 'word'=>$row['word']
//                 );
//         }
//         //JSONデータ出力
//         header("Content-type: application/json; charset=UTF-8");
//         echo json_encode($db_data);
//         $db=null;
//         } catch (Exception $e) {
//         }
?>