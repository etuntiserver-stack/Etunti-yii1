<div class="row">
<?php
/* @var $this SivexkuittiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet'),
);

?>

<h1><?php echo Yii::t('main', 'Yhteenveto kohteet'); ?></h1>


<div class="row">
  <form action="#" id="yhtveto" method="POST">
  <div class="col-md-12">
   <?php
    $model=new Sivexkuitti;
    $list = CHtml::listData(Sivexkuitti::model()->findAll(array('group' => 'kohde_kannasta','order' => 'kohde_kannasta')), 'kohde_kannasta', 'kohde_kannasta');

    echo '<select name="kohteet" class="selectpicker" id="kohteet" class="btn btn-default" title="Kohteet">';
       	 echo '<option value="kaikki">Kaikki</option>';
    foreach($list as $key=>$val){
       	 echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>

   <input type="date" name="from" id="from" class="btn btn-default" value="<?php echo Yii::app()->session['from']; ?>">

   <input type="date" name="to" id="to" class="btn btn-default" value="<?php echo Yii::app()->session['to']; ?>">

   <input type="submit" class="btn btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">
   </form>
  </div>
</div>

<br>

<?php if(Yii::app()->session['from'] and Yii::app()->session['to']) : ?>
  <table class="table table-striped table-bordered">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
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
