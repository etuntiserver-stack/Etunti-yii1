<div class="row">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Toteuma'),
);

?>





<legend>
<h1> <?php echo Yii::t('main', 'TUNTIEN HYVÄKSYNTÄ'); ?> <i class="glyphicon glyphicon-time"></i></h1>
</legend>

<div class="row" id="haku">

  <form action="#" id="yhtveto" class="form-inline" method="POST">
  <div class="col-md-12">
   <?php
    $list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'tekijan_nimi');
    echo '<select name="tekija" id="nimi" class="form-control form-group input-sm">';
    $explTekija = explode("//",Yii::app()->session['tekija']);

    if(isset($explTekija[0]) and isset($explTekija[1])){
       echo '<option value="'.$explTekija[0].'//'.$explTekija[1].'">'.$explTekija[1].'</option>';
    } else {
       echo '<option>'.Yii::t('main', 'Työntekijät').'</option>';
    }

    foreach($list as $key=>$val){
    echo '<option value="'.$key.'//'.$val.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>

   <?php
    $lounas = '';
    $lounas = ( isset(Yii::app()->session['Lounastauko']))  ? 'selected' : '';
    $matka = '';
    $matka = ( isset(Yii::app()->session['MATKA']))  ? 'selected' : '';

    echo '<select name="ilman[]" class="selectpicker ilman btn-sm "  multiple="multiple"  title="Ei lasketa">';
    echo '<option value="Lounastauko" '.$lounas.'>Lounastauko</option>';
    echo '<option value="MATKA" '.$matka.'>MATKA</option>';
    echo '</select>';
   ?>
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="from" id="from" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['from']; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="to" id="to" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['to']; ?>">


   <div class="form-group input-group-btn">
        <input type="submit" class="btn btn-primary btn-sm" value="<?php echo Yii::t('main', 'Hae'); ?>">
   </div>

   </form>

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="submit" name="tulosta" class="btn btn-success btn-sm" value="PDF">
     </form>
   </div>
   <!-- tulostus -->

  </div>
</div>

<br>

<?php if(Yii::app()->session['tekija']) : ?>

<?php

function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}

		if(isset(Yii::app()->session['from']))
		$from = date("d.m.Y",strtotime(Yii::app()->session['from']));
		if(isset(Yii::app()->session['to']))
		$to = date("d.m.Y",strtotime(Yii::app()->session['to']));

		$dateDiff = dateDiff($from, $to);
?>

  <table class="table table-striped table-bordered">
  <thead>
  <tr>
  <th width=1><?php echo Yii::t('main', 'Pvm'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunnitellut'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luettu'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  <th><?php echo Yii::t('main', 'Ma'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php 
  $arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
  $yhtMatka = 0;
  $yhtIlta = 0;

  for ($i = 0; $i <= $dateDiff; $i++) 
  {
  
    $plus = "+$i day";
    $date = '';
    $date = date("d.m.Y",strtotime($from." ".$plus));

    $columnDate = date("N/d.m",strtotime($date));
    $explColDate = explode("/",$columnDate);
    $did = date("Ymd",strtotime($date));

    echo '
    <tr>
  	<td width=1 class="small">'.$arrDate[$explColDate[0]].', '.$date.'</td>';
  
    $tas = explode(",",Yii::app()->user->adminPaketti);
    if(in_array('2',$tas)) 
    echo '<td>'.$this->renderPartial('//tyovuoroot/did',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>';

    echo '
  	<td>'.$this->renderPartial('luetutpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>
  	<td id="'.$did.'_'.$explTekija[0].'">'.$this->renderPartial('totpvmtid',array('pvm'=>$date,'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>
  	<td id="yht_'.$did.'_'.$explTekija[0].'">'.$this->renderPartial('yhteensapvm',array('pvm'=>date("Y-m-d",strtotime($date)),'tid'=>$explTekija[0],'from'=>'mobiili'),true).'</td>';

    $matka = '';
    $matka = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>date("Y-m-d",strtotime($date)),
		'to'=>date("Y-m-d",strtotime($date)),
		'tid'=>$explTekija[0]
		),true);
    $yhtMatka += $matka;
    echo '<td>'.$this->sprint($matka).'</td>';

    $tyoIlta = '';
    $tyoIlta = $this->tyoIlta($explTekija[0],date("Y-m-d",strtotime($date)));
    $yhtIlta += $tyoIlta;
    echo '<td>'.$this->sprint($tyoIlta).'</td>';
    echo '</tr>';

  }
  ?>
  </tbody>

  <tfoot>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas) and isset($explTekija[0])){
  $total_sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$explTekija[0],'kohde_tid'=>'tid'),true);
  echo '<th><center>'.sprint($total_sunniteltu).'</center></th>';
  }
  ?>

  <?php
  $total_luettu = $this->renderPartial('//mobile/total_luettu',array('tid'=>$explTekija[0]),true);
  echo '<th><center>'.sprint($total_luettu).'</center></th>';
  ?>

  <?php
  $total_toteutu = $this->renderPartial('//mobile/total_toteutu',array('tid'=>$explTekija[0]),true);
  echo '<th><center>'.sprint($total_toteutu).'</center></th>';
  ?>

  <th></th>
  <th><?php echo $this->sprint($yhtMatka); ?></th>
  <th><?php echo $this->sprint($yhtIlta); ?></th>
  </tr>
  </tfoot>

  </table>
<?php endif; ?>

</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<?php Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>
	<?php Yii::app()->clientScript->registerPackage('toteuma'); ?>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/totrivi_poista.js"></script>

<script type="text/javascript">
$(document).ready(function(){



$("#yhtveto").on('submit',function(e){

  var from = $("#from").val();
  var to = $("#to").val();
  var nimi = $("#nimi").val();

    if (from  === '') {
        $('#from').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (to  === '') {
        $('#to').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (nimi  === '') {
        $('#nimi').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

});

});
</script>
