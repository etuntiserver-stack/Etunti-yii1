<?php
//echo 'huoltokatko';
//exit;
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */

  if(!isset($_SESSION['domain']))
  {
    echo '  <link rel="stylesheet" type="text/css" href="'.Yii::app()->request->baseUrl.'/css/bootstrap.min.css">';

    echo '
    <div class="container-fluid">
     <div class="col-sm-4 col-sm-offset-4">
      <form action="#" class="form-signin" method="GET">
        <h2 class="form-signin-heading">DOMAIN</h2>
        <label for="domain" class="sr-only">'.Yii::t('main', 'Domain').'</label>
        <input type="text" id="domain" name="domain" class="form-control input-lg" placeholder="" required autofocus>
	<br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">'.Yii::t('main', 'Jatka').'</button>
      </form>
     </div>
    </div> <!-- /container -->
    ';


    exit;
  }


$asetukset = Asetukset::model()->findbypk(1);

if($asetukset->onlinevaraus_alku == 0){
Asetukset::model()->updatebypk(1, array('onlinevaraus_alku'=>8));
}

if($asetukset->onlinevaraus_loppu == 0){
Asetukset::model()->updatebypk(1, array('onlinevaraus_loppu'=>18));
}

// clear
  if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
	$this->loadModel($_SESSION['onlinevaraus']['onlinevarausID'])->delete();

  if(isset($_SESSION['onlinevaraus']['modelTV']))
	Tyovuoroot::model()->deletebypk($_SESSION['onlinevaraus']['modelTV']);

  unset($_SESSION['onlinevaraus']);
//
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_2.css">

<div class="container-fluid">
<br>

<div class="row">
 <div class="form-inline col-sm-12">
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
    <li class="active"><?php echo CHtml::link('PALVELU','index'); ?></li>
    <li class="disabled"><?php echo CHtml::link('AIKA','aika'); ?></li>
    <li class="disabled"><?php echo CHtml::link('OSOITE','osoite'); ?></li>
    <li class="disabled"><?php echo CHtml::link('MAKSU','maksu'); ?></li>
</ul>

<br><br>




                            <!-- Modal -->
                            <div class="modal fade kysymys">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4>Palvelun varaaminen</h4>
                                  </div>
                                  <div class="modal-body" style="text-align: left">
                                  <p>

1.       Valitse alasvetovalikosta pääpalvelu.<br>
2.       Valitse huoneiston koko.<br>
3.       Valitse haluamasi lisäpalvelut.<br>
4.       Siirry eteenpäin valitsemaan palvelulle ajankohtaa.<br>

<br><br> 

<p>Jokaiselle palvelulle on määritelty palvelusisältö ja ne näkyvät palvelun yhteydessä. Lisäpalveluja voidaan valita rajaton määrä. Pääpalvelun ja lisäpalveluiden kesto ja kokonaishinta tulevat näkyviin yhteenvetoon. Asiakaspalvelun yhteystiedot ovat näkyvillä sivustolla. Ole yhteydessä asiakaspalveluun, mikäli sinulla on jotain kysyttävää.</p>

				  </p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->


<?php 
$asetukset = Asetukset::model()->findbypk(1);
if(isset($asetukset->checkout_id) and !empty($asetukset->checkout_id) and !empty($asetukset->checkout_salasana))
{

} else {
		echo '<h3 class="alert alert-danger"><center>Checkout tunnukset puuttuu!</center></h3>';
}
?>

<div class="row">
 <div class="col-sm-8">
  <div class="well">
   <center>

	<div class="row">
	  <div class="col-sm-4 col-sm-offset-4">

		<h4>Valitse palvelu</h4>

		<select class="form-control input-lg" id="palvelu">
		<?php
		if(isset($_SESSION['onlinevaraus']['paapalvelu']))
		{
		  $onlineTuotteet = OnlinevarausTuotteet::model()->findbypk($_SESSION['onlinevaraus']['paapalvelu']);
		    if(isset($onlineTuotteet->id))
		  	echo '<option value="'.$onlineTuotteet->nimike.'">'.$onlineTuotteet->nimike.'</option>';
		}
		?>

		<option value="">Valitse palvelu</option>

		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " nayta_sivuilla=1 ";
	       	$criteria->order = " nimike ";
		$onlineTuotteet = OnlinevarausTuotteet::model()->findAll($criteria);
		foreach($onlineTuotteet as $data)
		{
		  echo 
		  '
			<option value="'.$data->id.'">'.$data->nimike.'</option>
		  ';
		}
		?>
		</select>
	  </div>
	</div>

	<div class="row" id="toinen_valiko"></div>


	<div class="row" id="toimialueRow">
	  <div class="col-sm-4 col-sm-offset-4">
		<h4><?php echo Yii::t('main', 'Valitse toimialue'); ?></h4>
		<?php
		$exists = Valikkoot::model()->find(" select_type='tyo_toimialue' ");
		if(!isset($exists->id))
		{
		    $valiko = new Valikkoot;
		    $valiko->select_type = 'tyo_toimialue';
		    $valiko->value = 'Test';
		    $valiko->save();
		}

		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='tyo_toimialue' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->value] = $v->value;

        	echo CHtml::dropDownList('tyo_toimialue', 'tyo_toimialue', $list,
		array('class'=>'form-control input-lg'));
        	?>
	  </div>
	</div>

<br>
	<div class="row" id="lispalvimg">
	  <div class="col-sm-4 col-sm-offset-4">
		<h4>Haluaisitko lisäpalveluita?</h4>
	   	<img src="<?php echo Yii::app()->request->baseUrl; ?>/ylapalkki/haluan.png">
	  </div>
	</div>
<br>

	<div id="lisapalvelulista"></div>

   </center>
  </div>
 </div>
 <div class="col-sm-4">
 
	      <div id="panGetContent"></div>

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

	     <?php if(!empty($asetukset->onlinevaraus_laatu_luotettavuus)) : ?>
	      <div>
		<div class="well sininen">
		  <center><h4><?php echo Yii::t('main', 'Laatu ja luotettavuus'); ?></h4></center>
		  <p class="small"><?php echo str_replace("\n", "<br>", $asetukset->onlinevaraus_laatu_luotettavuus); ?></p>
		</div>
	      </div>
	     <?php endif; ?>

	     <?php if(!empty($asetukset->onlinevaraus_takuu_turvallisuus)) : ?>
	      <div>
		<div class="well sininen">
		  <center><h4><?php echo Yii::t('main', 'Takuu ja turvallisuus'); ?></h4></center>
		  <p class="small"><?php echo str_replace("\n", "<br>", $asetukset->onlinevaraus_takuu_turvallisuus); ?></p>
		</div>
	      </div>
	     <?php endif; ?>

	     <?php if(!empty($asetukset->onlinevaraus_asiakaspalvelu)) : ?>
	      <div>
		<div class="well sininen">
		  <center><h4><?php echo Yii::t('main', 'Asiakaspalvelu'); ?></h4></center>
		  <p class="small"><?php echo str_replace("\n", "<br>", $asetukset->onlinevaraus_asiakaspalvelu); ?></p>
		</div>
	      </div>
	     <?php endif; ?>

<?php if(!empty($asetukset->onlinevaraus_arvio_siivouksesta)) : ?>
<div class="">
  <div class="well sininen">
	<center><h4><?php echo Yii::t('main', 'Arvio siivouksesta'); ?></h4></center>
        <p class="small"><?php echo str_replace("\n", "<br>", $asetukset->onlinevaraus_arvio_siivouksesta); ?></p>	
  </div>
</div>
<?php endif; ?>


 </div>
</div>

<br>




    <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/onlinevaraus/rekisteriseloste" target="_blank"><?php echo Yii::t('main','Onlinevaraus tietosuoja- ja rekisteriseloste'); ?> </a>
   <br>
   <?php
   foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/onlinevarausehdot.*')) as $file) 
   {
	$explNimi = explode("/",$file);
 	echo '<a href="../../'.$file.'">'.end($explNimi).'</a>';
	
   }
   ?>

</div>


<?php echo $this->renderPartial('_footer'); ?>


<script type="text/javascript">
$(document).ready(function(){

  localStorage.clear();


$("#lispalvimg").click(function(){
	$('#lisapalvelulista').show('slow');
});

/*
$( "#lispalvimg" ).hover(
  function() {
	$('#lispalvimg img').replaceWith('<img src="<?php echo Yii::app()->request->baseUrl; ?>/ylapalkki/haluan.png">');



  }, function() {
	$('#lispalvimg img').replaceWith('<img src="<?php echo Yii::app()->request->baseUrl; ?>/ylapalkki/lisapalv.png">');
  }
);
*/





$("#palvelu").change(function(){

   clearAll();
   var id = $(this).val();
   $.ajax({
	url: 'palvelu_ajax',
	data:{ "id" : id },
	type:'POST',
	success:function(data){
		data = JSON.parse(data);
		console.log(data);

		if(data[0])
		$("#toinen_valiko").html(data[0]).show('slow');
			//checker();
		if(data[0]){
			$("#lispalvimg").show('slow');
			$('#lisapalvelulista').html(data[1]);
		}

   	},
	error:function(data){
		console.log(data);
    	}
    });

});



function clearAll(){

   $.ajax({
	url: 'palvelu_ajax',
	data:{ "clear" : "all" },
	type:'POST',
	success:function(data){
		//console.log(data);
   		$('.checkbox').removeAttr('checked');
		$('#panGetContent').html('');
   	},
	error:function(data){
		console.log(data);
    	}
    });

}


/*
checker();
function checker(){

   var id = $("#palvelu").val();
   var tyo_toimialue = $('#tyo_toimialue').val();

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ "id" : id, "tyo_toimialue" : tyo_toimialue },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
		}
   	},
	error:function(data){
		console.log(data);
    	}
    });

}
*/


$(document).delegate("#toinen_valiko_values","change",function(){

	var thisVal 	= $(this).val().split("//");
	var otsikko 	= thisVal[0];
	var nimike 	= thisVal[1];
	var hinta 	= thisVal[2];
	var kesto 	= thisVal[3];
	var tyo_toimialue = $('#tyo_toimialue').val();

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ toinen_valiko : "true", nimike : nimike, hinta : hinta, kesto : kesto, tyo_toimialue : tyo_toimialue },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
		}
   	},
	error:function(data){
		console.log(data);
    	}
    });

});


$("#tyo_toimialue").change(function(){
	var tyo_toimialue = $(this).val();

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ toinen_valiko : "true", tyo_toimialue : tyo_toimialue },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
});



$(document).delegate(".lisat","click",function(){

   var lisapalvelut = $(this).attr("for").split("//");
   var checked = '';
   if ($(this).is(':checked')) {
	checked = 1;
   } else {
	checked = 0;
   }

   if(lisapalvelut)
   {
   $.ajax({
	url: 'lisat_ajax',
	data:{ "lisapalvelut" : lisapalvelut, "checked" : checked },
	type:'POST',
	success:function(data){
		console.log(data);

   	},
	error:function(data){
		console.log(data);
    	}
    });
    }

});




});
</script>



