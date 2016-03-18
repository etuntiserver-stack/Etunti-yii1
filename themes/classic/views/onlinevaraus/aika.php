<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);
?>

<div class="container-fluid">

<div class="container-fluid">
<br><br>
<div class="row">
 <div class="form-inline col-sm-12">
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
   </div><div class="form-group col-sm-offset-3">
	<h2>Online-Varaus</h2>
   </div>
 </div>
</div>

<ul class="nav nav-pills nav-justified">
  <li role="presentation"><?php echo CHtml::link('Palvelu','index'); ?></li>
  <li role="presentation" class="active"><?php echo CHtml::link('Aika','aika'); ?></li>
  <li role="presentation"><?php echo CHtml::link('Osoite','osoite'); ?></li>
  <li role="presentation"><?php echo CHtml::link('Maksu','maksu'); ?></li>
</ul>


<br><br>
<div class="row">
 <div class="col-sm-3">
 <?php
     unset($_SESSION['ajaanReika']); // clear

     $dateArray = array();
     $dateComponents = getdate();

     $month = date('m');
     $year = date('Y');
     echo $this->build_calendar($month,$year,$dateArray);

     echo '<hr>';

     $month = date('m',strtotime("+1 month"));
     $year = date('Y',strtotime("+1 month"));
     echo $this->build_calendar($month,$year,$dateArray);
 ?>
 </div>

 <div class="col-sm-5">
	<div id="aikoja"></div>
 </div>

 <div class="col-sm-4">
   <?php 
   if(isset($_SESSION['onlinevaraus']['paapalvelu']))
   {
	$model = OnlinevarausTuotteet::model()->findbypk($_SESSION['onlinevaraus']['paapalvelu']);
	$return = $this->renderPartial('palvelu_save_ajax', array('model'=>$model,'sivu'=>'aika'), true); 
   	echo json_decode($return, true);
   }
   ?>
 </div>
</div>







</div>



<script type="text/javascript">
$(document).ready(function(){

$(".cal").click(function(){

   var pvm = $(this).attr("pvm");
   $.ajax({
	url: 'ajaat_ajax',
	data:{ "pvm" : pvm },
	type:'POST',
	success:function(data){
		console.log(data);
		$('#aikoja').html(JSON.parse(data));
   	},
	error:function(data){
		console.log(data);
    	}
    });
});


});
</script>
