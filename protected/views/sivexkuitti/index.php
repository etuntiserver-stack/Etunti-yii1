<div class="row">
<?php
/* @var $this SivexkuittiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Luetut kohteet'),
);
/*
$this->menu=array(
	//array('label'=>'Create Sivexkuitti', 'url'=>array('create')),
	array('label'=>'Luetut Hallinta', 'url'=>array('admin')),
);
*/
?>

<h1><?php echo Yii::t('main', 'Mobiili luetut'); ?></h1>


<div class="row">
  <div class="col-md-2">
   <?php
    $model=new Sivexkuitti;
    $list = CHtml::listData(Sivexkuitti::model()->findAll(array('group' => 'tid','order' => 'tekijan_nimi')), 'tekijan_nimi', 'tekijan_nimi');

    echo '<select class="btn btn-default etsi_tekijan_nimi form-control">';
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

    echo '<select class="btn btn-default etsi_kohteet form-control">';
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
   <input type="date" class="btn btn-default form-control etsi_pvm" value="<?php echo Yii::app()->session['etsi_pvm']; ?>">
  </div>
</div>

<br>

  <table class="table table-striped">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'T'); ?></th>
  <th><?php echo Yii::t('main', 'Loc'); ?></th>
  <th><?php echo Yii::t('main', 'ID'); ?></th>
  <th><?php echo Yii::t('main', 'Päivä'); ?></th>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Työvuoroot'); ?></th>
  <?php endif; ?>

  <th class="col-sm-3"><?php echo Yii::t('main', 'Osoite/Matka'); ?></th>

  <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
  <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><center><?php echo Yii::t('main', 'M'); ?></center></th>
  <th><center><?php echo Yii::t('main', 'P'); ?></center></th>
  </tr>
  </thead>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


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

  </table>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<?php Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>

<script type="text/javascript">
$(document).ready(function(){

$(".pvmupdate").blur(function(){

	var st = $(this).attr("status");
	var thisID = $(this).attr("id").split("_");
	var request = $(this).attr("request");
	var thisVal = $(this).val().replace("T"," ");

	if((request == 'loppui') && (st == '1') && (thisVal != ''))
	status = 3;
	else
	status = st;

	if(thisVal == ''){
	alert("Error");
	return false;
	}
	
        $.ajax({
           url: 'updatetime',
           type: "POST",
           data: { "id" : thisID, "request" : request, "value" : thisVal, "status" : status },
           success: function(html){
		$('#'+thisID[0]+'_'+thisID[1]).removeClass("btn-default").addClass("btn-success");
           }
        });

});

$(".openkohde").click(function(){

	var thisID = $(this).attr("id");
	var forid = $(this).attr("for");
	var riviid = $(this).attr("for").split("_");
	
        $.ajax({
           url: 'showkohteet/',
           type: "POST",
           data: { "id" : riviid[1], "thisID" : thisID },
           success: function(html){
		$('#'+forid).html(html);
           }
        });

});

$(".poistaKohde").click(function(){

	var forRivi = $(this).attr("for");
	var riviid = $(this).attr("for").split("_");
	if(confirm('Oletko varmaa?'))
	{
        $.ajax({
           url: 'poistaKohde',
           type: "POST",
           data: { "id" : riviid[1] },
           success: function(html){
		$("#"+forRivi).remove();
           }
        });
	}

});

$(".etsi_tekijan_nimi").change(function(){
	var thisVal = $(this).val();
        $.ajax({
           url: "index",
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
           url: "index",
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
           url: "index",
	   type:'POST',
	   data: { "etsi_pvm" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

});
</script>
