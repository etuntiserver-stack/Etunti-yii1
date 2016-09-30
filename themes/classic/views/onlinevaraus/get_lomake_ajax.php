<?php

 $m = array();

 $attr = Kohteet::model()->getAttributes();
 foreach($attr as $key=>$val)
 {
    $m[$key] = $model->$key;
 }


 echo json_encode($m);

?>
