<div class="row">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Tunnit'),
);

?>

<legend>
<button class="pull-right btn btn-info" data-toggle="collapse"  data-target="#haku"><?php echo Yii::t('main', 'Extrat'); ?> <b class="caret"></b></button>
<h1> <?php echo Yii::t('main', 'PALKKATAULUKKO'); ?> <i class="glyphicon glyphicon-ok"></i></h1>
</legend>

<div class="row collapse" id="haku">
  <div class="col-md-12">
   <br><br><br>
   <?php
	$ko = new Korvaukset;
	echo $this->renderPartial('//korvaukset/_form',array('model'=>$ko)); 
   ?>
   <?php
	$lt = new Lisatyotunnit;
	echo $this->renderPartial('//lisatyotunnit/_form',array('model'=>$lt)); 
   ?>
   <?php
	$en = new Ennakko;
	echo $this->renderPartial('//ennakko/_form',array('model'=>$en)); 
   ?>
  </div>
</div>

<div class="row">
  <form action="#" id="yhtveto" class="form-inline" method="POST">
  <input type="hidden" name="yhtvetoform">
  <div class="col-md-12">
   <a href="#" id="deselAll" class="btn btn-default glyphicon glyphicon-minus"></a>
   <a href="#" id="selAll" class="btn btn-default glyphicon glyphicon-plus"></a>
   <?php
    $list = CHtml::listData(Mobile::model()->findAll(array('order' => 'tekijan_nimi','group'=>'tekijan_nimi')), 'tid', 'tekijan_nimi');

    echo '<select name="Tekija[]" class="selectpicker" id="tyontekijat" multiple class="btn btn-default" title="Työntekijät">';
    foreach($list as $key=>$val){
     if(!empty($val))
     {
       if(isset(Yii::app()->session['Tekija']) and in_array($key,Yii::app()->session['Tekija']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
     }
    }
    echo '</select>';
   ?>
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="from" id="from" class="form-control form-group datepicker" value="<?php echo Yii::app()->session['from']; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="to" id="to" class="form-control form-group datepicker" value="<?php echo Yii::app()->session['to']; ?>">

   <input type="submit" class="btn btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">
   </form>

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="submit" name="tulosta" class="btn btn-success" value="PDF">
     </form>
   </div>
   <!-- tulostus -->

  </div>
</div>




<br>

<?php if(Yii::app()->session['from'] and Yii::app()->session['to']) : ?>
  <table class="table table-striped table-bordered">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Työpäiviä'); ?></th>
  <th><?php echo Yii::t('main', 'Matkat'); ?></th>
  <th><?php echo Yii::t('main', 'Työtunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  <th><?php echo Yii::t('main', 'Korvaus'); ?></th>
  <th><?php echo Yii::t('main', 'Lisätyötunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Ennakko'); ?></th>
  </tr>
  </thead>

  <?php 
  foreach($model as $data)
  {
	$this->renderPartial('_palkkataulukko',array('data'=>$data));
  }
  ?>

  </table>
<?php endif; ?>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>




<script type="text/javascript">
$(document).ready(function(){


$('.selectpicker').selectpicker({
      style: 'btn-default',
      //size: 4
});

$('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
});


$('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
});




$("#yhtveto").on('submit',function(e){

  var from = $("#from").val();
  var to = $("#to").val();

    if (from  === '') {
        $('#from').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (to  === '') {
        $('#to').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

});



});
</script>
