<?php
    
     $body = '';

     $dateArray = array();
     $dateComponents = getdate();

     $month = date('m');
     $year = date('Y');

     $body .= '
<div class="row">
 <div class="boxes-info etuntibox">
  <div class="cont">
     '.$this->build_calendar($month,$year,$dateArray).'</div></div>
  <div>
 <div>
</div>
     ';

     $month = date('m',strtotime("last day of +1 month"));
     $year = date('Y',strtotime("last day of +1 month"));


     $body .= '
<div class="row">
 <div class="boxes-info etuntibox">
  <div class="cont">
     '.$this->build_calendar($month,$year,$dateArray).'
  <div>
 <div>
</div>
     ';


     echo json_encode($body);
?>
