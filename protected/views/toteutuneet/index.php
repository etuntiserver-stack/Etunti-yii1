<div class="row">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Toteuma'),
);

?>

<h1><?php echo Yii::t('main', 'Toteuma'); ?></h1>


<div class="row">
  <form action="#" id="yhtveto" method="POST">
  <div class="col-md-12">
   <?php
    $list = CHtml::listData(Mobile::model()->findAll(array('order' => 'tekijan_nimi','group'=>'tekijan_nimi')), 'tid', 'tekijan_nimi');

    echo '<select name="tekija" id="nimi" class="btn btn-default">';
    if(Yii::app()->session['tekija']){
       $explTekija = explode("//",Yii::app()->session['tekija']);
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

    echo '<select name="ilman[]" class="selectpicker ilman"  multiple="multiple"  title="Ilman...">';
    echo '<option value="Lounastauko" '.$lounas.'>Lounastauko</option>';
    echo '<option value="MATKA" '.$matka.'>MATKA</option>';
    echo '</select>';
   ?>

   <input type="date" name="from" id="from" class="btn btn-default" value="<?php echo Yii::app()->session['from']; ?>">
   <input type="date" name="to" id="to" class="btn btn-default" value="<?php echo Yii::app()->session['to']; ?>">

   <select name="tulosta" class="btn btn-default">
     <option value="web"><?php echo Yii::t('main', 'WEB muoto'); ?></option>
     <option value="pdf"><?php echo Yii::t('main', 'PDF muoto'); ?></option>
   </select>

   <input type="submit" class="btn btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">
   </form>
  </div>
</div>

<br>

<?php if(Yii::app()->session['tekija']) : ?>

  <table class="table table-striped table-bordered">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunnitellut'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luettu'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
  )); ?>
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
  </tr>
  </tfoot>

  </table>
<?php endif; ?>

</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<?php Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>
	<?php Yii::app()->clientScript->registerPackage('toteuma'); ?>



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
