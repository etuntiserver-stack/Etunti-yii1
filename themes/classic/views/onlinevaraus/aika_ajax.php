<?php
    
     $asetukset = Asetukset::model()->findByPk(1);
     $body = '';

     ($asetukset->onlinevaraus_viikonlopput == 0)? $numOfWeek = 7 : $numOfWeek = 5; 
     $dateArray = array();
     $dateComponents = getdate();

     $month = date('m');
     $year = date('Y');

     $body .= $this->build_calendar($month, $year, $dateArray, $asetukset->onlinevaraus_aikaisintaan_paivamaara, $numOfWeek);

     $month = date('m',strtotime("last day of +1 month"));
     $year = date('Y',strtotime("last day of +1 month"));


     $body .= $this->build_calendar($month, $year, $dateArray, $asetukset->onlinevaraus_aikaisintaan_paivamaara, $numOfWeek);


     echo json_encode($body);
?>
