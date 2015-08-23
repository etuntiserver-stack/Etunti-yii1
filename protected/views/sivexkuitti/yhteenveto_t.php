<div class="row">
<?php
/* @var $this SivexkuittiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Luetut kohteet'),
);

?>

<h1><?php echo Yii::t('main', 'Yhteenveto toteutuneet'); ?></h1>


<div class="row">
  <div class="col-md-2">
   <?php
    $model=new Sivexkuitti;
    $list = CHtml::listData(Sivexkuitti::model()->findAll(array('group' => 'tid','order' => 'tekijan_nimi')), 'tekijan_nimi', 'tekijan_nimi');

    echo '<select class="btn btn-info etsi_tekijan_nimi form-control">';
    if(Yii::app()->session['etsi_tekijan_nimi'])
       echo '<option value="'.Yii::app()->session['etsi_tekijan_nimi'].'">'.Yii::app()->session['etsi_tekijan_nimi'].'</option>';
    else
       echo '<option>'.Yii::t('main', 'Työntekijät').'</option>';

       echo '<option value="kaikki">Kaikki</option>';

    foreach($list as $val){
    echo '<option value="'.$val.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>
  </div>
  <div class="col-md-2">
   <input type="date" class="btn btn-info form-control etsi_pvm" value="<?php echo Yii::app()->session['etsi_pvm']; ?>">
  </div>
</div>

<br>

  <table class="table table-striped table-bordered">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  </tr>
  </thead>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_yhteenveto_t',
)); ?>

  </table>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<?php Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>

<script type="text/javascript">
$(document).ready(function(){


$(".etsi_tekijan_nimi").change(function(){
	var thisVal = $(this).val();
        $.ajax({
           url: "yhteenveto_l",
	   type:'POST',
	   data: { "etsi_tekijan_nimi" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});


$(".etsi_pvm").on('blur', function() {
	var thisVal = $(this).val();
	if(!thisVal)
	var thisVal = 'kaikki';

        $.ajax({
           url: "yhteenveto_l",
	   type:'POST',
	   data: { "etsi_pvm" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

});
</script>
