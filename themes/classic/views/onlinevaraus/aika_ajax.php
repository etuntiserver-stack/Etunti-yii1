<?php
    
     $asetukset = Asetukset::model()->findByPk(1);
     $body = '';

     $dateArray = array();
     $dateComponents = getdate();

     $month = date('m');
     $year = date('Y');

     $body .= $this->build_calendar($month, $year, $dateArray, $asetukset->onlinevaraus_aikaisintaan_paivamaara);

     $month = date('m',strtotime("last day of +1 month"));
     $year = date('Y',strtotime("last day of +1 month"));


     $body .= $this->build_calendar($month, $year, $dateArray, $asetukset->onlinevaraus_aikaisintaan_paivamaara);


     echo json_encode($body);
?>
