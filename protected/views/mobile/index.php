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


<legend>
  <div class="pull-right form-inline">
  	<span class="form-control form-group klo"></span>
  	<button class="btn btn-info" data-toggle="collapse"  data-target="#haku"><?php echo Yii::t('main', 'Haku'); ?> <b class="caret"></b></button>
  </div>
  <h1> 
	<?php echo Yii::t('main', 'TUNNIT'); ?> <i class="glyphicon glyphicon-phone"></i> 
  </h1>
</legend>



 <div class="row collapse" id="haku">
   <div class="col-sm-12">

   <div class="pull-right">
     <?php echo CHtml::link(' +','/index.php/tyontekijat/create',array('target'=>'_blank','class'=>'btn btn-default glyphicon glyphicon-user')); ?>
     <?php echo CHtml::link(' +','/index.php/kohteet/create',array('target'=>'_blank','class'=>'btn btn-default glyphicon glyphicon-home')); ?>
   </div>

   <form id="mobForm" action="#" class="form-inline" method="POST">
   <input type="hidden" name="mob_hae">
   <?php
    $model=new Mobile;
    $list = CHtml::listData(Mobile::model()->findAll(array('group' => 'tekijan_nimi','order' => 'tekijan_nimi')), 'tekijan_nimi', 'tekijan_nimi');

    echo '<select class="form-control form-group" name="etsi_tekijan_nimi">';
    if(Yii::app()->session['etsi_tekijan_nimi'])
       echo '<option value="'.Yii::app()->session['etsi_tekijan_nimi'].'">'.Yii::app()->session['etsi_tekijan_nimi'].'</option>';
    else
       echo '<option value="kaikki">'.Yii::t('main', 'Työntekijät').'</option>';

       echo '<option value="kaikki">Kaikki</option>';

    foreach($list as $val){
    echo '<option value="'.$val.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>

   <?php
    $model=new Mobile;
    $list = CHtml::listData(Mobile::model()->findAll(array('group' => 'kohdenID','order' => 'kohde_kannasta')), 'kohde_kannasta', 'kohde_kannasta');

    echo '<select class="form-control form-group" name="etsi_kohteet">';
    if(Yii::app()->session['etsi_kohteet'])
       echo '<option value="'.Yii::app()->session['etsi_kohteet'].'">'.Yii::app()->session['etsi_kohteet'].'</option>';
    else
       echo '<option value="kaikki">'.Yii::t('main', 'Kohteet').'</option>';

       echo '<option value="kaikki">Kaikki</option>';

    foreach($list as $val){
    echo '<option value="'.$val.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>
   <input type="text" class="form-control form-group datepicker" name="etsi_pvm" value="<?php echo Yii::app()->session['etsi_pvm']; ?>">

     <div class="form-group input-group-btn">
        <button class="btn btn-primary haemob" type="button"><i class="glyphicon glyphicon-search"> Hae</i></button>
     </div>
   </div>
  </form>
 </div>


<!--
  <div class="row">
   <div class="col-sm-4">
        <div class="input-group">
            <input type="text" class="form-control hakusana" placeholder="Hakusana" name="srch-term" id="srch-term" value="<?php echo Yii::app()->session['hakusana']; ?>">
            <div class="input-group-btn">
                <button class="btn btn-primary hae" type="button"><i class="glyphicon glyphicon-search"> Hae</i></button>
            </div>
        </div>
   </div>
  </div>
-->

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


$(".haemob").click(function(){
	$("#mobForm").submit();
});


// Send form by ajax
$('#mobForm').on('submit',function(e) {

  $.ajax({
  url: 'index?Mobile_page='+mobnum,
  data:$(this).serialize(),
  type:'POST',
  success:function(data){
  	console.log(data);
	tableAjax();
	return false;
  },
  error:function(data){
  	console.log(data); 
  }
  });

e.preventDefault(); 
});



function tableAjax(){

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

        var date = new Date();
        var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + ":" + seconds;


	  	$('.klo').text( time );	
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
			    $('#rivi_'+e).fadeOut(1000).fadeIn(1000).fadeOut(1000).fadeIn(1000);
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
