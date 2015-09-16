<div class="row">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet'),
);

?>

<legend>
<button class="pull-right btn btn-info" data-toggle="collapse"  data-target="#haku"><?php echo Yii::t('main', 'Haku'); ?> <b class="caret"></b></button>
<h1> <?php echo Yii::t('main', 'YHTEENVETO KOHTEET'); ?> <i class="glyphicon glyphicon-home"></i></h1>
</legend>



<div class="row collapse" id="haku">

  <form action="#" id="yhtveto" class="form-inline" method="POST">
  <div class="col-md-12">
   <?php
    $model=new Mobile;
    $list = CHtml::listData(Mobile::model()->findAll(array('group' => 'kohde_kannasta','order' => 'kohde_kannasta')), 'kohde_kannasta', 'kohde_kannasta');

    echo '<select name="kohteet" class="form-control form-group" id="kohteet">';
       	 echo '<option value="kaikki">Kaikki</option>';
    foreach($list as $key=>$val){

	   $strlen = strlen($val);
	   if($strlen > 27)
	    $val = substr($val,0,27).'..';
	   else
	    $val = $val;

       	 echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>

   <input type="date" name="from" id="from" class="form-control form-group" value="<?php echo Yii::app()->session['from']; ?>">
   <input type="date" name="to" id="to" class="form-control form-group" value="<?php echo Yii::app()->session['to']; ?>">


   <div class="form-group input-group-btn">
      <input type="submit" class="btn btn-primary" value="<?php echo Yii::t('main', 'Hae'); ?>">
   </div>

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
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunniteltu tunnit'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luetut tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></th>
  </tr>
  </thead>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_kyhteenveto',
)); ?>

  </table>
<?php endif; ?>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>




<script type="text/javascript">
$(document).ready(function(){

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
