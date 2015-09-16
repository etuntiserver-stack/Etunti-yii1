<div class="row">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Luetut kohteet'),
);
/*
$this->menu=array(
	//array('label'=>'Create Mobile', 'url'=>array('create')),
	array('label'=>'Luetut Hallinta', 'url'=>array('admin')),
);
*/
?>

<h1><?php echo Yii::t('main', 'Mobiili luetut'); ?></h1>

<div class="pull-right">
  <?php echo CHtml::link(Yii::t('main', 'Luo työntekijä'),'/index.php/tyontekijat/create',array('target'=>'_blank','class'=>'btn btn-success glyphicon-plus')); ?>
  <?php echo CHtml::link(Yii::t('main', 'Luo kohde'),'/index.php/kohteet/create',array('target'=>'_blank','class'=>'btn btn-success glyphicon-plus')); ?>
</div>

<div class="row">

  <div class="col-md-2">
   <?php
    $model=new Mobile;
    $list = CHtml::listData(Mobile::model()->findAll(array('group' => 'tid','order' => 'tekijan_nimi')), 'tekijan_nimi', 'tekijan_nimi');

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
    $model=new Mobile;
    $list = CHtml::listData(Mobile::model()->findAll(array('group' => 'kohdenID','order' => 'kohde_kannasta')), 'kohde_kannasta', 'kohde_kannasta');

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

  <div id="tb"></div>

</div>


  <input type="hidden" id="dataChange" >


<script type="text/javascript">
$(document).ready(function(){

/* koko taulukko päivittä joka 60 sek, ja uuden rivin tarkistaminen on 10 sek kuluttua */

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

var mobnum = getParameterByName('Mobile_page');

function tableAjax(e){

   $.ajax({
      url: 'index?Mobile_page='+mobnum,
      type: "POST",
      data: { index_ajax : "true" },
      success: function(data){
	  $('#tb').html(data);
      }
   });

}

   tableAjax();
   setInterval(tableAjax, "60000");


    updateRivi();
    function updateRivi() {
        $.ajax({
           url: 'index_ajax',
           success: function(data){
		if(data)
		{
		  if(($("#dataChange").val() != data) & ($("#dataChange").val() != ''))
		  {
                    //console.log("new "+data);
		    var e = data;

		   $.ajax({
		      url: 'index?Mobile_page='+mobnum,
		      type: "POST",
		      data: { index_ajax : "true" },
		      success: function(data){		
			  $('#tb').html(data);		
			  if(e){
			    console.log(e);
			    $('#rivi_'+e).hide('slow');
		  	    $('#rivi_'+e).show('slow');
			  }		
		      }
		   });

		  }
		}
		$("#dataChange").val(data);
           }
        });
    }
    setInterval(updateRivi, "5000");


});
</script>
