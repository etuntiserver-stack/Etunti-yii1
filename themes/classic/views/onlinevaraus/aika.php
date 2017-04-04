<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_2.css">

<div class="container-fluid">
<br>

<div class="row">
 <div class="form-inline col-sm-12">
	<b id="countTimer" class="pull-right"></b>
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">

	&nbsp;<span data-toggle="modal" data-target=".kysymys" class="link"><img src="<?php echo Yii::app()->request->baseUrl; ?>/ylapalkki/kysymys.png" height="30"></span>

   </div><div class="form-group col-sm-offset-4">
	<h3><?php echo Yii::t('main', 'Online-Varaus'); ?><br>
           <p class="small link text-sininen" data-toggle="modal" data-target=".mikaOnOnlinevaraus"><?php echo Yii::t('main', 'Mikä on online-varaus'); ?></p>
	</h3>
   </div>
 </div>
</div>


<ul class="steps expanded even-4">
    <li class="tehtty"><?php echo CHtml::link('PALVELU','index'); ?></li>
    <li class="active"><?php echo CHtml::link('AIKA','aika'); ?></li>
    <li class="disabled"><?php echo Yii::t('main','OSOITE'); ?></li>
    <li class="disabled"><?php echo Yii::t('main','MAKSU'); ?></li>
</ul>

<br><br>



                            <!-- Modal -->
                            <div class="modal fade kysymys">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4>Ajan varaaminen</h4>
                                  </div>
                                  <div class="modal-body" style="text-align: left">
                                  <p>

1.       Valitse kalenterista päivämäärä, jolloin haluat palvelun.<br>
2.       Valitse kellonaika, jolloin haluat palvelun alkavan.<br>
3.       Siirry eteenpäin antamaan osoitetiedot.<br>

<br><br> 

<p>Päivät, joissa on vapaita aikoja valittavana näkyvät vihreällä, harmaalla näkyvät päivät, joita ei voi valita ja oranssilla näkyvä on valitsemasi päivä. Kellonajat, joita päivämäärän valinnan jälkeen näkyy ovat kaikki mahdolliset vapaat ajat kyseiselle päivälle. Yhteenvetoon päivittyy, kun tietoja kirjataan. Asiakaspalvelun yhteystiedot ovat näkyvillä sivustolla. Ole yhteydessä asiakaspalveluun, mikäli sinulla on jotain kysyttävää.</p>

				  </p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->

<div class="row">
 <div class="col-sm-4">
	<div id="kalenterit"></div>
 </div>
 <div class="col-sm-4">
	<div id="aikoja"></div>
	<div id="tidTietoja"></div>
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

<!--
	      <div id="alennuskoodi">
		<div class="well">
		  <center><h4><?php echo Yii::t('main', 'Alennuskoodi'); ?></h4>
			<form class="input-group">
			<input type="text" class="form-control form-group input-lg">
			<span class="input-group-btn">
			  <input type="submit" class="btn btn-lg btn-group btn-warning" value="<?php echo Yii::t('main', 'Aktivoi'); ?>">
			</span>	
			</form>
		  </center>
		</div>
	      </div>
-->

	     <?php if(!empty($asetukset->onlinevaraus_asiakaspalvelu)) : ?>
	      <div>
		<div class="well sininen">
		  <center><h4><?php echo Yii::t('main', 'Asiakaspalvelu'); ?></h4></center>
		  <p class="small"><?php echo str_replace("\n", "<br>", $asetukset->onlinevaraus_asiakaspalvelu); ?></p>
		</div>
	      </div>
	     <?php endif; ?>

 </div>
</div>


</div>


<?php echo $this->renderPartial('_footer'); ?>


<script type="text/javascript">
$(document).ready(function(){

var step = 41;
var count1 = step;

$(document).delegate(".day","click",function(){
	$('.day').removeClass('orangeColor');
	$(this).addClass('orangeColor');

  localStorage.setItem('valinnuPvm', $(this).attr("pvm"));
  aikoja();
  setInterval(aikoja, "15000");
  count1 = step;

	$('html,body').animate({
	   scrollTop: $("#aikoja").offset().top
	});

});




kaksiKalenteria();


function kaksiKalenteria()
{
   $.ajax({
	url: 'aika_ajax',
	data:{ "nothing" : "true" },
	type:'POST',
	success:function(data){
		//console.log(data);
		count1 += -1;

		var time = count1*15;
		var minutes = "0" + Math.floor(time / 60);
		var seconds = "0" + (time - minutes * 60);
		jaljella =  minutes.substr(-2) + ":" + seconds.substr(-2);
		$('#countTimer').text('Aikajäljellä: '+jaljella);

		$('#kalenterit').html(JSON.parse(data));
	        $(".toolt").tooltip();

		if(count1 < 1)
		window.location.href="index?keskeyta=true";
   	},
	error:function(data){
		console.log(data);
    	}
    });
}
setInterval(kaksiKalenteria, "15000");


$(document).delegate(".ajaanClick","click",function(){

   count1 = step;
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
			$('#aikoja').hide('slow');
			//aikoja();

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });


  $.ajax({
	url: 'tidtietoja',
	data:{ "tid" : tid },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#tidTietoja').html(JSON.parse(data));

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });


});

/*
$(document).delegate(".cal","click",function(){

  localStorage.setItem('valinnuPvm', $(this).attr("pvm"));
  aikoja();
  setInterval(aikoja, "15000");
  count1 = step;
});
*/

  clearInterval(aikoja);
  localStorage.setItem('valinnuPvm', null);
  function aikoja()
  {

   var pvm = localStorage.getItem('valinnuPvm');
   $.ajax({
	url: 'ajaat_ajax',
	data:{ "pvm" : pvm },
	type:'POST',
	success:function(data){
		//console.log(JSON.parse(data));
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

