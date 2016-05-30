<?php

 $m = array();

 $attr = Kohteet::model()->getAttributes();
 foreach($attr as $key=>$val)
 {
    $m[$key] = $model->$key;
 }



if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
{

 $ov = Onlinevaraus::model()->findbypk($_SESSION['onlinevaraus']['onlinevarausID']);
 if(isset($ov->id))
 {
 $m['asiakas_id'] = $ov->asiakas_id;
 $m['etu_suku_nimet'] = $ov->yhteyshenkilo;
 $m['puh_nro'] = $ov->puhelin;
 $m['osoite'] = $ov->osoite;
 $m['pnumero'] = $ov->postinumero;
 $m['kaupunki'] = $ov->kaupunki;
 $m['tietoja'] = $ov->lisatietoja;
 }

}



 echo json_encode($m);

?>
