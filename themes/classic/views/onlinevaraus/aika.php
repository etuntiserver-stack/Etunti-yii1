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
     $dateArray = array();
     $dateComponents = getdate();

     $month = '3'; 			     
     $year = '2016';
     echo $this->build_calendar($month,$year,$dateArray);

     $month = '4'; 			     
     $year = '2016';
     echo $this->build_calendar($month,$year,$dateArray);
 ?>
 </div>


 <div class="col-sm-9">
 
 </div>
</div>




<div class="row">
 <div class="form-inline col-sm-12">
	<?php echo CHtml::link('<< Edellinen sivu','index',array('class'=>'btn btn-success')); ?>
	<?php echo CHtml::link('Seuraava sivu >>','osoite',array('class'=>'btn btn-success pull-right')); ?>
 </div>
</div>


</div>



<script type="text/javascript">
$(document).ready(function(){
/*
$("#cal").change(function(){

   $.ajax({
	url: location.protocol + "//" + location.host + '/index.php/onlinevaraus/check',
	data:{ "pvm" : $(this).val() },
	type:'POST',
	success:function(data){
		$('#showres').html(data);
   	},
	error:function(data){
		console.log(data);
    	}
    });

});
*/
});
</script>
