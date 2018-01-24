<?php


$asetukset = Asetukset::model()->findbypk(1);

if($asetukset->onlinevaraus_alku == 0){
Asetukset::model()->updatebypk(1, array('onlinevaraus_alku'=>8));
}

if($asetukset->onlinevaraus_loppu == 0){
Asetukset::model()->updatebypk(1, array('onlinevaraus_loppu'=>18));
}

// clear
  if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
	Onlinevaraus::model()->deletebypk($_SESSION['onlinevaraus']['onlinevarausID']);

  if(isset($_SESSION['onlinevaraus']['modelTV']))
	Tyovuoroot::model()->deletebypk($_SESSION['onlinevaraus']['modelTV']);

  unset($_SESSION['onlinevaraus']);

//
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_ver2.css">

<div class="container-fluid ">
<div class="row ">
    <div class="col-sm-6 col-sm-offset-3 select-service">
        <center>
	<div class="row">
	  <div class="col-sm-6 col-sm-offset-3">

		<h4>Valitse palvelu</h4>

		<select class="form-control input-lg" id="palvelu">
		<option value="">Valitse palvelu</option>

		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " nayta_sivuilla=1 ";
	       	$criteria->order = " nimike ";
		$onlineTuotteet = TuotteetPalvelut::model()->findAll($criteria);
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
	  <div class="col-sm-6 col-sm-offset-3">
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
  </div>

</div>

    <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/onlinevaraus/rekisteriseloste" target="_blank"><?php echo Yii::t('main','Onlinevaraus tietosuoja- ja rekisteriseloste'); ?> </a>

   <?php
   foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/onlinevarausehdot.*')) as $file) 
   {
		$explNimi = explode("/",$file);
		// <-- file_safe_opener
		$filepath = 'tiedostot/firma/'.Yii::app()->user->domain.'/'.end($explNimi);
		echo '<br>'.CHtml::link(Yii::t('main', 'Onlinevarausehdot'),
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
	
   }

   foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/Konevuokraus_toimitusehdot.*')) as $file) 
   {
		$explNimi = explode("/",$file);
		// <-- file_safe_opener
		$filepath = 'tiedostot/firma/'.Yii::app()->user->domain.'/'.end($explNimi);
		echo '<br>'.CHtml::link(Yii::t('main', 'Konevuokraus toimitusehdot'),
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
	
   }
   ?>
   
   
   
</div>



<div class="onlinevaraus-order-summary">
  <div class="col-sm-6 col-sm-offset-3 order-summary">Order Summary</div>
</div>

<script type="text/javascript">
$(document).ready(function(){

  localStorage.clear();


$("#lispalvimg").click(function(){
	$('#lisapalvelulista').show('slow');
});


$(document).delegate("#kupongi_add","click",function(){


   $.ajax({
	url: 'kupongi_checker?kupongi='+$('#kupongi_id').val(),
	//data:{ kupongi : $('#kupongi_id').val() },
	type:'GET',
	success:function(data){
		console.log(data);
		if(data !== '')
		{
			$("#kupongi_result").html('<span class="text-success">Alennuskoodi on voimassa.</span>');
			ajaaPalveluSave();

		} else {
			$("#kupongi_result").html('<span class="text-danger">Alennuskoodi ei ole voimassa.</span>');
		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
   
});

$("#palvelu").change(function(){

   clearAll();
   var id = $(this).val();

var t = setTimeout( function() {
   $.ajax({
	url: 'palvelu_ajax',
	data:{ "id" : id },
	type:'POST',
	success:function(data){
		data = JSON.parse(data);
		//console.log(data);

		if(data[0] != ''){
			$("#toinen_valiko").html(data[0]).show('slow');
		} else {
			$("#toinen_valiko").html('').hide('slow');
		}
			//checker();
		if(data[1] != ''){
			$("#lispalvimg").show('slow');
			$('#lisapalvelulista').html(data[1]);
		} else {
			$("#lispalvimg").hide('slow');
		}

		ajaaPalveluSave();

   	},
	error:function(data){
		console.log(data);
    	}
    });

}, 100 );

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


$(document).delegate("#toinen_valiko_values","change",function(){

	var thisVal 	= $(this).val().split("//");
	var otsikko 	= thisVal[0];
	var nimike 	= thisVal[1];
	var hinta 	= parseFloat(thisVal[2]);
	var kesto 	= 0;
	if(thisVal[3])
	kesto = parseFloat(thisVal[3]);
	var tyo_toimialue = $('#tyo_toimialue').val();

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ toinen_valiko : "true", otsikko : otsikko, nimike : nimike, hinta : hinta, kesto : kesto, tyo_toimialue : tyo_toimialue },
	type:'POST',
	success:function(data){
		console.log(kesto);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
		}
		tuntienTarkistus();

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
		tuntienTarkistus();
   	},
	error:function(data){
		console.log(data);
    	}
    });
});



$(document).delegate(".lisat","click",function(){

   var lisapalvelut = $(this).attr("for").split("//");
   //console.log(lisapalvelut);
   var fordata = $(this).attr("fordata");

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
	data:{ fordata : fordata, lisapalvelut : lisapalvelut, checked : checked },
	type:'POST',
	success:function(data){
		//console.log(data);
		ajaaPalveluSave();
   	},
	error:function(data){
		console.log(data);
    	}
    });
    }

});


 function ajaaPalveluSave(){

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ toinen_valiko : "true" },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
		}
		tuntienTarkistus();
   	},
	error:function(data){
		console.log(data);
    	}
    });
  }

  function tuntienTarkistus(){
	var clock = parseFloat($('#clock').attr('val'));
	if((clock > 0) && ( $('#toinen_valiko_values option:selected').val() !== '' ))
	$('.seuraava').removeClass('disabled');

	if( $('#toinen_valiko_values option:selected').val() === '' )
	$('.seuraava').addClass('disabled');
  }

});
</script>



