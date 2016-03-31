<?php
    
     $body = '';

     $dateArray = array();
     $dateComponents = getdate();

     $month = date('m');
     $year = date('Y');
     $body .= $this->build_calendar($month,$year,$dateArray);

     $body .= '<hr>';

     $month = date('m',strtotime("last day of +1 month"));
     $year = date('Y',strtotime("last day of +1 month"));
     $body .= $this->build_calendar($month,$year,$dateArray);

     echo json_encode($body);
?>
