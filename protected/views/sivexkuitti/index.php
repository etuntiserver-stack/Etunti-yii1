<div class="row">
<?php
/* @var $this SivexkuittiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Luetut kohteet',
);

$this->menu=array(
	//array('label'=>'Create Sivexkuitti', 'url'=>array('create')),
	array('label'=>'Luetut Hallinta', 'url'=>array('admin')),
);
?>

<h1>Luetut kohteet</h1>


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
   <?php
    $model=new Sivexkuitti;
    $list = CHtml::listData(Sivexkuitti::model()->findAll(array('group' => 'kohdenID','order' => 'kohde_kannasta')), 'kohde_kannasta', 'kohde_kannasta');

    echo '<select class="btn btn-info etsi_kohteet form-control">';
    if(Yii::app()->session['etsi_kohteet'])
       echo '<option value="'.Yii::app()->session['etsi_kohteet'].'">'.Yii::app()->session['etsi_kohteet'].'</option>';
    else
       echo '<option>'.Yii::t('main', 'Kohteet').'</option>';

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



  <table class="table table-striped">
  <tbody>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
  	'template'=>'<table class="table table-striped table-bordered table-condensed">{items}</table><br/>{pager}',


'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 

)); ?>
  </tbody>
  </table>
</div>


<script type="text/javascript">
$(document).ready(function(){

$(".pvmupdate").blur(function(){

	var st = $(this).attr("status");
	var pvm = $(this).attr("pvm");
	var thisID = $(this).attr("id").split("_");
	var request = $(this).attr("request");
	var thisVal = $(this).val();

	if((request == 'loppui') && (st == '1') && (thisVal != ''))
	var status = '3';
	if(thisVal == ''){
	alert("Error");
	return false;
	}
	
alert(thisVal)
/*
        $.ajax({
           url: 'index.php?r=sivexkuitti/updatetime&id='+thisID[1]+'&request='+request,
           type: "POST",
           data: { "pvm" : pvm, "value" : thisVal, "status" : status },
           success: function(html){
		$('#'+thisID[0]+'_'+thisID[1]).removeClass("btn-default").addClass("btn-success");
           }
        });
*/
});


$(".etsi_tekijan_nimi").change(function(){
	var thisVal = $(this).val();
        $.ajax({
           url: "index.php?r=sivexkuitti/index",
	   type:'POST',
	   data: { "etsi_tekijan_nimi" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

$(".etsi_kohteet").change(function(){
	var thisVal = $(this).val();
        $.ajax({
           url: "index.php?r=sivexkuitti/index",
	   type:'POST',
	   data: { "etsi_kohteet" : thisVal },
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
           url: "index.php?r=sivexkuitti/index",
	   type:'POST',
	   data: { "etsi_pvm" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

});
</script>
