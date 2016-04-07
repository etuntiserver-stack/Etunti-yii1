<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus.css">

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


<div class="stepwizard">
    <div class="stepwizard-row">
        <div class="stepwizard-step">
            <button type="button" class="btn btn-default btn-circle"><?php echo CHtml::link('1','index'); ?></button>
            <p>PALVELU</p>
        </div>
        <div class="stepwizard-step">
            <button type="button" class="btn btn-primary btn-circle"><?php echo CHtml::link('2','aika'); ?></button>
            <p>AIKA</p>
        </div>
        <div class="stepwizard-step">
            <button type="button" class="btn btn-default btn-circle"><?php echo CHtml::link('3','osoite'); ?></button>
            <p>OSOITE</p>
        </div> 
              <div class="stepwizard-step">
            <button type="button" class="btn btn-default btn-circle"><?php echo CHtml::link('4','maksu'); ?></button>
            <p>MAKSU</p>
        </div>

    </div>
</div>


<br><br>


<div class="row">
 <div class="col-sm-4">
	<div id="kalenterit"></div>
 </div>
 <div class="col-sm-4">
	<div id="aikoja"></div>
 </div>
 <div class="col-sm-4">
   <div id="panGetContent">
   <?php 
   if(isset($_SESSION['onlinevaraus']['paapalvelu']))
   {
	$return = $this->renderPartial('palvelu_save_ajax', array('sivu'=>'aika'), true); 
   	echo json_decode($return, true);
   }
   ?>
   </div>
 </div>
</div>


</div>



<script type="text/javascript">
$(document).ready(function(){


$(document).delegate(".day b","click",function(){
	$('.day b').removeClass('orangeColor');
	$(this).addClass('orangeColor');
});




kaksiKalenteria();
var count1 = null;
function kaksiKalenteria()
{
   $.ajax({
	url: 'aika_ajax',
	data:{ "nothing" : "true" },
	type:'POST',
	success:function(data){
		//console.log(data);
		count1 += 1;
		console.log('count 1: '+count1);
		$('#kalenterit').html(JSON.parse(data));

	        $(".toolt").tooltip();

		if(count1 > 20)
		window.location.href="index?keskeyta=true";
   	},
	error:function(data){
		console.log(data);
    	}
    });
}
setInterval(kaksiKalenteria, "15000");


$(document).delegate(".ajaanClick","click",function(){


   var pvm = $(this).attr('pvm');
   var tid = $(this).attr('tid');
   var alku = $(this).attr('alku');
   var loppu = $(this).attr('loppu');


   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ "tid" : tid, "pvm" : pvm, "alku" : alku, "loppu" : loppu, "osoiteOnline" : "1" },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
			$('#aikoja').html('');
			aikoja();

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });


});


$(document).delegate(".cal","click",function(){

  localStorage.setItem('valinnuPvm', $(this).attr("pvm"));
  aikoja();
  setInterval(aikoja, "15000");

});


  clearInterval(aikoja);
  localStorage.clear();
  var count2 = null;
  function aikoja()
  {

   var pvm = localStorage.getItem('valinnuPvm');
   $.ajax({
	url: 'ajaat_ajax',
	data:{ "pvm" : pvm },
	type:'POST',
	success:function(data){
		count2 += 1;
		console.log('count 2: '+count2);
		$('#aikoja').html(JSON.parse(data));
		return false;
   	},
	error:function(data){
		console.log(data);
    	}
    });
  }



});

</script>

