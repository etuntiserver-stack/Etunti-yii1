<?php

?>
<?php if(isset($_POST['tulosta'])) : ?>
<style>
table{ width: 640px}
td{ border:1px #333 solid; padding: 3px 7px; }
legend{  padding: 3px 7px; }
</style>
<?php echo Yii::t('main','Tulostus pvm: ').date("d.m.Y"); ?>
<br>
<?php endif; ?>

<legend>
<h1><?php echo Yii::t('main','Avaimet'); ?></h1>
</legend>

<br>

<?php if(!isset($_POST['tulosta'])) : ?>
<div class="row" id="haku">
  <div class="col-md-12">

   <a href="#" id="deselAll" class="glyphicon glyphicon-minus"></a>
   <a href="#" id="selAll" class="glyphicon glyphicon-plus"></a>

  <form action="#" class="form-inline" method="POST">
   <?php
    $list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi','group'=>'tekijan_nimi')), 'id', 'tekijan_nimi');

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

   <input type="submit" class="btn btn-primary btn-sm" value="<?php echo Yii::t('main', 'haku'); ?>">
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
<?php endif; ?>

<br>


<table class="table table-bordered table-stripped">
<thead>
 <tr>
  <th><?php echo Yii::t('main','Työntekijä'); ?></th>
  <th><?php echo Yii::t('main','Avain'); ?></th>
 </tr>
</thead>
<tbody>
  <?php
  foreach($model as $data)
  {
    $k = Kohteet::model()->findAll(" SUBSTRING_INDEX(kenella_on_avain, '//', 1) = '".$data->id."' ");
	$avaimet = '';
    foreach($k as $kohde)
    {
	$avaimet .= '<div class="row">
			<div class="col-sm-6">'.$kohde->osoite.'</div>
			<div class="col-sm-6"><b>'.$kohde->avain.'</b></div>
		     </div>';
    }

    echo '<tr>';
    echo '<td>'.$data->tekijan_nimi.'</td>';
    echo '<td>'.$avaimet.'</td>';
    echo '</tr>';
  }
  ?>
</tbody>
</table>


<?php if(!isset($_POST['tulosta'])) : ?>
<script type="text/javascript">
$(document).ready(function(){

$('.selectpicker').selectpicker({
      style: 'btn-default btn-sm',
      //size: 4
});

$('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
});


$('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
});


});
</script>
<?php endif; ?>
