<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet'),
);

?>

<legend>
<h1> <?php echo Yii::t('main', 'Tuntiyhteenveto kohteet (tuntemattomat)'); ?> <i class="glyphicon glyphicon-home"></i></h1>
</legend>



<div class="row" id="haku">

  <form action="#" id="yhtveto" class="form-inline" method="POST">
  <div class="col-md-12">
   <?php
   ?>
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="from" id="from" class="form-control form-group input-sm datepicker" value="<?php echo $from; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="to" id="to" class="form-control form-group input-sm datepicker" value="<?php echo $to; ?>">


   <div class="form-group input-group-btn">
      <input type="submit" class="btn btn-primary btn-sm" value="<?php echo Yii::t('main', 'Hae'); ?>">
   </div>

   </form>

   <!-- tulostus -->
<!--
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="submit" name="tulosta" class="btn btn-success btn-sm" value="PDF">
     </form>
   </div>
-->
   <!-- tulostus -->

  </div>
</div>

<br>

<?php if($from and $to) : ?>
  <table class="table table-striped table-bordered small">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Pvm'); ?></th>
  <th><?php echo Yii::t('main', 'Luetut tunnit'); ?></th>
  </tr>
  </thead>

  <?php 
  foreach($model as $data)
  {
	$this->renderPartial('_kyhteenveto_tuntemattomat',array('data'=>$data));
  }
  ?>

  <tfoot>
  </table>
<?php endif; ?>


	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>

	<input type="hidden" id="from" value="<?php echo $from; ?>">
	<input type="hidden" id="to" value="<?php echo $to; ?>">


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
