<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
/* @var $form CActiveForm */

if(isset($_POST['pvm']))
  $model->pvm = date("Y-m-d",strtotime($_POST['pvm']));
else
  $model->pvm = date("Y-m-d",strtotime($model->pvm));


if(isset($_POST['tid']))
  $model->tid = $_POST['tid'];


		  $ohje = '';
if(isset($model->id))
{

	$m = Kohteet::model()->findbypk($model->kohde);
		
	if(isset($m->id))
	{

		$k = explode("//",$m->kenella_on_avain);

		  $ohje = '';
		if(isset($k[1]))
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."\n";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."\n\n";
		if(!empty($m->aikataulu))
		  $ohje .= "\nAikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet: ".$m->toimenpiteet;
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja: ".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut: ".$m->muut;

	}

}
echo '<input type="hidden" id="alkuperainenID" value="'.$model->id.'">';
echo '<input type="hidden" id="alkuperainenDID" value="'.date("Ymd", strtotime($model->pvm)).'_'.$model->tid.'">';
?>

<div class="section">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyovuoroot-form',
	'enableAjaxValidation'=>false,

)); ?>


	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'id',array('id'=>$model->id)); ?>
	<?php echo $form->hiddenField($model,'tid'); ?>
	<?php echo $form->hiddenField($model,'ruokatauko'); ?>
	<?php echo $form->hiddenField($model,'alku_r'); ?>
	<?php echo $form->hiddenField($model,'kesto'); ?>
	<?php echo $form->hiddenField($model,'osoiteOnline'); ?>
	<?php echo $form->hiddenField($model,'time'); ?>
	<?php echo $form->hiddenField($model,'toistuva_id'); ?>
	<?php echo $form->error($model,'tid'); ?>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepicker'));//,'readonly'=>'yes' ?>
		<?php echo $form->error($model,'pvm'); ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'alku'); ?>
		<input type="text" name="Tyovuoroot[alku]" class="form-control laske timeVuorot" id="alku" value="<?php echo $model->alku; ?>" autofocus>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<input type="text" name="Tyovuoroot[loppu]" class="form-control laske timeVuorot" id="loppu" value="<?php echo $model->loppu; ?>">
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pituus'); ?>
		<input type="text" name="Tyovuoroot[pituus]" id="pituus" class="form-control timeVuorot" value="<?php echo $model->pituus; ?>">
  </div>
</div>


<div class="row">
<!--
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<?php
        	$tal = Valikkoot::model()->findAll(" select_type='tyoajanlaatu' ", array('order' => 'select_type'));
		echo '<select name="Tyovuoroot[tyoajanlaatu]" class="form-control">';

		 if(!empty($model->tyoajanlaatu)){
		   $expl1 = explode("/",$model->tyoajanlaatu);
		   $value1 = (isset($expl1[0])) ? $expl1[0] : '';
		   echo '<option value="'.$model->tyoajanlaatu.'">'.$value1.'</option>';
		 }

		 foreach($tal as $v)
		 {
		   $expl1 = explode("/",$v->value);
		   $color = (isset($expl1[1])) ? $expl1[1] : '';
		   $value1 = (isset($expl1[0])) ? $expl1[0] : '';
		   echo '<option style="color:'.$color.'" value="'.$v->value.'">'.$value1.'</option>';
		 }
		echo '</select>';
        	?>
  </div>
-->
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php
        	$tal = Valikkoot::model()->findAll(" select_type='tyoajanmerkinta' ", array('order' => 'select_type'));
		echo '<select name="Tyovuoroot[tyoajanmerkinta]" class="form-control">';

		 if(!empty($model->tyoajanmerkinta)){
		   $expl = explode("/",$model->tyoajanmerkinta);
		   $value = (isset($expl[0])) ? $expl[0] : '';
		   echo '<option value="'.$model->tyoajanmerkinta.'">'.$value.'</option>';
		 }

		 foreach($tal as $v)
		 {
		   $expl = explode("/",$v->value);
		   $color = (isset($expl[1])) ? $expl[1] : '';
		   $value = (isset($expl[0])) ? $expl[0] : '';
		   echo '<option style="color:'.$color.'" value="'.$v->value.'">'.$value.'</option>';
		 }
		echo '</select>';
        	?>

  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
        	$l = $this->tilanteet();
		echo $form->dropDownList($model,'status', $l, 
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control')) ?>

  </div>

  <div class="col-sm-3">
		<label><?php echo Yii::t('main', 'Asiakas'); ?></label><br>
		<?php 

		$criteria=new CDbCriteria;
		$criteria->order =" yrityksen_nimi!='' DESC,yhteyshenkilo!='' DESC";
		$criteria->condition =" aktiivinen=1 ";

 		$as = Asiakkaat::model()->findAll($criteria);
		$nm = array();
		if(isset($as[0]))
		{

			foreach($as as $a)
			{
				if(!empty($a->yrityksen_nimi))
				$nm[$a->yrityksen_nimi] = $a->id;
				elseif(!empty($a->yhteyshenkilo))
				$nm[$a->yhteyshenkilo] = $a->id;
				else
				$nm[$a->osoite] = $a->id;

			}
			ksort($nm);

			echo '<select name="asiakas" id="asiakas" class="form-control">';
				echo '<option value=""></option>';

			foreach($nm as $k=>$v)
				echo '<option value="'.$v.'">'.$k.'</option>';

			echo '</select>';
		}
		?>

  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php
		if($model->onlinevaraus_id == 0)
		{
        		$list = CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite')), 'id', 'osoite');
        		echo $form->dropDownList($model, 'kohde', $list,array('empty'=>'Valitse','class'=>'form-control kohde'));

		} else {

	 		$ov = Onlinevaraus::model()->findbypk($model->onlinevaraus_id);
			if(isset($ov->id))
			echo '<input type="text" class="form-control" value="'.$ov->osoite.'">';

		}
        	?>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php 

	 		$ov = Onlinevaraus::model()->findbypk($model->onlinevaraus_id);
			if(isset($ov->id))
				$model->tietoja = $ov->lisatietoja;
			elseif(!isset($ov->id) and isset($model->tietoja))
				$model->tietoja = $model->tietoja;

			echo $form->textarea($model,'tietoja',array('rows'=>4,'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'tietoja'); ?>
  </div>
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'ohje'); ?>
		<textarea class="form-control ohje" rows="4"><?php echo $ohje; ?></textarea>
  </div>
</div>



<div class="row">
  <div class="col-sm-6">
		<label><?php echo Yii::t('main', 'Työpari'); ?></label><br>
		<?php 
		$tyopaari = json_decode($model->tyopaari, true);

		$criteria=new CDbCriteria;
		$criteria->order =" tekijan_nimi ";
		$criteria->condition =" aktiivinen=1 and id!='".$model->tid."' ";

 		$tt = Tyontekijat::model()->findAll($criteria);
		if(isset($tt[0]))
		{
			echo '<select name="tyopaari[]" id="tyopaari" class="mult" multiple>';
			foreach($tt as $tekija)
			{
			  if(is_array($tyopaari) and in_array($tekija->id,$tyopaari, true))
			    echo '<option value="'.$tekija->id.'" selected>'.$tekija->tekijan_nimi.'</option>';
			  else
			    echo '<option value="'.$tekija->id.'">'.$tekija->tekijan_nimi.'</option>';
			}
			echo '</select>';
		}
		?>

  </div>
  <div class="col-sm-6">


<?php 
$t = Tyontekijat::model()->findbypk($model->tid);
if(!empty($t->gcm_reg_id)) :
?>
    <div class="pull-right">
    <br>
		<?php echo Yii::t('main','Ilmoita työntekijää viestillä'); ?> 
			<input type="checkbox" name="Tyovuoroot[PushNotify]" class="sw" id="Tyovuoroot_PushNotify">
    </div>
<?php endif; ?>
  </div>
</div>

<br>

<?php
  if(isset($model->id) and $model->toistuva_id != 0)
  {
    $to = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id);
    $pfrom = $to->pfrom;
    $viikkoja = $to->viikkoja;
    $viikko_paivat = json_decode($to->viikko_paivat, true);
    $pto = $to->pto;
    $classCol = 'collapse in';
    $toistuvaID =  '<span id="toistuvaID">'.$model->toistuva_id.'</span>';
  } else {
    $pfrom = $model->pvm;
    $pto = '';
    $viikkoja = '';
    $viikko_paivat = array();
    $classCol = 'collapse';
    $toistuvaID =  '<span id="toistuvaID"></span>';
  }
?>
<div class="row">
 <div class="col-sm-12">

	<?php if((isset($model->id) and $model->toistuva_id != 0) or !isset($model->id)) : ?>
  	<a href="#" class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"> <?php echo Yii::t('main','Toistuva työvuoro'); ?></a>
	<?php endif; ?>

	<div class="<?php echo $classCol; ?>" id="collapseExample">
	<br>
	<b><?php echo Yii::t('main','Suorita'); ?></b>
	<input type="checkbox" class="sw" name="ToistuvatTyovuorot[toistuva_aktiivinen]" id="toistuva_aktiivinen">

<div class="row">
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Alkaen'); ?></label>
	<input type="text" class="form-control datepicker" name="ToistuvatTyovuorot[pfrom]" value="<?php echo $pfrom; ?>">
  </div>
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Loppuen'); ?></label>
	<input type="text" class="form-control datepicker" name="ToistuvatTyovuorot[pto]" id="pto" value="<?php echo $pto; ?>">
  </div>
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Työvuorojen viikkoväli'); ?></label>
	<select class="form-control" name="ToistuvatTyovuorot[viikkoja]">
	<?php
	if(!empty($viikkoja)) echo '<option value="'.$viikkoja.'">'.$viikkoja.'</option>';
	?>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	</select>
  </div>
</div>

<br>
<div class="row">
  <div class="col-sm-12 col-sm-offset-1">
  <label><?php echo Yii::t('main', 'Ma'); ?></label>

  <?php if(in_array(1, $viikko_paivat)): ?>
  <input type="checkbox" class="sw" name="P[1]" id="ma" value="1" checked>
  <?php else: ?>
  <input type="checkbox" class="sw" name="P[1]" id="ma" value="1">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'Ti'); ?></label>

  <?php if(in_array(2, $viikko_paivat)): ?>
  <input type="checkbox" class="sw" name="P[2]" id="ti" value="2" checked>
  <?php else: ?>
  <input type="checkbox" class="sw" name="P[2]" id="ti" value="2">
  <?php endif; ?>


  <label><?php echo Yii::t('main', 'Ke'); ?></label>

  <?php if(in_array(3, $viikko_paivat)): ?>
  <input type="checkbox" class="sw" name="P[3]" id="ke" value="3" checked>
  <?php else: ?>
  <input type="checkbox" class="sw" name="P[3]" id="ke" value="3">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'To'); ?></label>

  <?php if(in_array(4, $viikko_paivat)): ?>
  <input type="checkbox" class="sw" name="P[4]" id="to" value="4" checked>
  <?php else: ?>
  <input type="checkbox" class="sw" name="P[4]" id="to" value="4">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'Pe'); ?></label>

  <?php if(in_array(5, $viikko_paivat)): ?>
  <input type="checkbox" class="sw" name="P[5]" id="pe" value="5" checked>
  <?php else: ?>
  <input type="checkbox" class="sw" name="P[5]" id="pe" value="5">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'La'); ?></label>

  <?php if(in_array(6, $viikko_paivat)): ?>
  <input type="checkbox" class="sw" name="P[6]" id="la" value="6" checked>
  <?php else: ?>
  <input type="checkbox" class="sw" name="P[6]" id="la" value="6">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'Su'); ?></label>

  <?php if(in_array(7, $viikko_paivat)): ?>
  <input type="checkbox" class="sw" name="P[7]" id="su" value="7" checked>
  <?php else: ?>
  <input type="checkbox" class="sw" name="P[7]" id="su" value="7">
  <?php endif; ?>

  </div>
</div>

	</div>
 </div>
</div>





</div>

		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->

<br>

	<div class="modal-footer">
		<?php 
	   	$site = Yii::app()->createController('Site');

	   	$checkPoista = "tyovuorot_3_".Yii::app()->user->adminStatus;
	   	$poista = $site[0]->checkOikeusFields($checkPoista);

			if(isset($model->id) and $poista == 1)
			{
			$doit = date("Ymd",strtotime($model->pvm))."_".$model->tid; 
			echo CHtml::Button('Poista',array('class'=>'btn btn-danger', 'id'=>'poistaTv', 'for'=>$doit, 'model'=>$model->id, 'data-dismiss'=>'modal'));
			}
		?>
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php 


	   	$checkLuo = "tyovuorot_1_".Yii::app()->user->adminStatus;
	   	$luo = $site[0]->checkOikeusFields($checkLuo);

	   	$checkTallenna = "tyovuorot_2_".Yii::app()->user->adminStatus;
	   	$tallenna = $site[0]->checkOikeusFields($checkTallenna);

		if(!isset($model->id) and $luo == 1)
		echo CHtml::submitButton('Luo',array('class'=>'btn btn-primary','id'=>'submitButton'));
		elseif(isset($model->id) and $tallenna == 1)
		echo CHtml::submitButton('Tallenna',array('class'=>'btn btn-primary','id'=>'submitButton')); 
		?>
	</div>		



<?php $this->endWidget(); ?>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>


<script type="text/javascript">
$(document).ready(function(){

  if($('#Tyovuoroot_kohde').val() !== '')
  {
	var kohdeOn = $('#Tyovuoroot_kohde option:selected').val();
	  	 $.ajax({
			url: 'getAsiakasByKohde',
			type:'GET',
			data: { "id" : kohdeOn },
			  success:function(data){
			     if(data)
			     {
				data = JSON.parse(data);
			  	console.log(data);
				$('#asiakas').val(data);
			     } else {
			  	console.log('ei ole asiakas id');
			     }

			  },
			  error:function(data){
			  	console.log(data);
			  }
	 	});
  }

  $('#asiakas').change(function(){
	var thisVal = $(this).val();

	  	 $.ajax({
			url: 'getKohdeByAsiakas',
			type:'GET',
			data: { "id" : thisVal },
			  success:function(data){
				data = JSON.parse(data);
			  	console.log(data);
				$('#Tyovuoroot_kohde').html(data);

			  },
			  error:function(data){
			  	console.log(data);
			  }
	 	});
  });




$('.mult').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
});



  $('.timeVuorot').mask('00:00',{
        placeholder: "__:__"
  });

  $(".sw").bootstrapSwitch({
	size: "mini",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });

	$('#submitButton').click(function(){
		$('#tyovuoroot-form').submit();
		return false;
	});

	$('#tyovuoroot-form').on('submit',function(e) {

	$('#showres').modal('hide');

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );
	var str = '';
	var thisDataReturn = [];

	if( e.target[0].value != '')
	{

	// paivita vanhat
	  var toistuva_aktiivinen = $('#toistuva_aktiivinen').is(':checked');
	  if(toistuva_aktiivinen === true)
	  {
	  //alert(e.target[7].value);
	  $.ajax({
		  url: 'paivita_laatikot',
		  data:{ toistuva_id : e.target[7].value },
		  type:'POST',
		  success:function(data){
			data = JSON.parse(data);
			//console.log(data);
			laatikonPaivays(data);

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  	  }
	// paivita vanhat


	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update?id='+e.target[0].value,
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			thisDataReturn = JSON.parse(data);
			//console.log(thisDataReturn);
			laatikonPaivays(thisDataReturn);

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });

	} else {


	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/create',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			thisDataReturn = JSON.parse(data);
			//console.log(thisDataReturn);
			laatikonPaivays(thisDataReturn);

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });



	}










	//var pvmFromPost = e.target[7].value; 
	//alert(pvmFromPost)

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/viikko',
			type:'GET',
			data: { "tid" : "<?php echo $model->tid; ?>", "viikko" : "<?php echo date('W',strtotime($model->pvm)); ?>", "year" : "<?php echo date('Y',strtotime($model->pvm)); ?>" },
			  success:function(data){
			  console.log(data);
			  $('#vk_<?php echo date("W",strtotime($model->pvm))."_".$model->tid; ?>').html(data);
			  return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});




	e.preventDefault(); 
	});



// Poistaminen
$('#poistaTv').click(function(){

	var thisID = 'checkThis_'+$(this).attr('for');
	var model = $(this).attr('model');
	var toistuva_aktiivinen = $('#toistuva_aktiivinen').is(':checked');

	var r = confirm('Haluatko varmasti poistaa?');
	if(r)
	{

	// paivita vanhat
	  if(toistuva_aktiivinen === true)
	  {
	  var toistuva_id = $('#Tyovuoroot_toistuva_id').val();
	  $.ajax({
		  url: 'paivita_laatikot',
		  data:{ toistuva_id : toistuva_id },
		  type:'POST',
		  success:function(data){
			data = JSON.parse(data);
			//console.log(data);
			laatikonPaivays(data);

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  	  }
	// paivita vanhat


        $.ajax({
           url: 'poistaTv',
	   type:'POST',
	   data: { "poistaTv" : model, toistuva_aktiivinen : toistuva_aktiivinen },
           success: function(data){
        	//console.log(data);
		parent.postMessage( "doit//"+thisID, "*");

    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
	}

});



function laatikonPaivays(thisDataReturn){

		var splDID = [];
		var did = '';
		$(thisDataReturn).each(function( iarr, arr ) {
		 $(arr).each(function( i, d ) {
		 //console.log(d['pvm']);

	  	    $.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did',
			type:'GET',
			data: { "pvm" : d['pvm'], "tid" : d['tid'], "from" : "ajax" },
			  success:function(data){
			  //console.log(data);


			  if( $('#'+d['ymd']+'_'+d['tid']).length )
			  {
			    $('#'+d['ymd']+'_'+d['tid']).html(JSON.parse(data));
			    if(parent.location.href.match(/index/))
			    {
				var ThisHeight = $('#'+d['ymd']+'_'+d['tid']).height();
				var FirstHeight = $('#first_'+d['tid']).height(ThisHeight);
			    }
			    if(parent.location.href.match(/tv2/))
			    {
				var ThisHeight = $('#'+d['ymd']+'_'+d['tid']).height();
				var FirstHeight = $('#first_'+d['ymd']).height(ThisHeight);
			    }

			    // <-- poista latikosta tvuoro jos id ei sama
			    splDID = d['pvm'].split(".");
			    did = $('#alkuperainenID').val()+'_'+splDID[2]+''+splDID[1]+''+splDID[0]+'_'+d['tid'];
			    if($('#alkuperainenID').val()+'_'+$('#alkuperainenDID').val() !== did)
			    {
				$('#'+$('#alkuperainenID').val()+'_'+$('#alkuperainenDID').val()).remove();
			    }
			    // poista latikosta tvuoro jos id ei sama -->

			  }


			  },
			  error:function(data){
			  console.log(data);
			  }
	 	    });

		 });
		});

}


  function laskePituus(){

	var alku = $("#alku").val().split(':');
	var loppu = $("#loppu").val().split(':');

	if(loppu[0] < alku[0])
	var d2 = new Date(2016, 0, 21, loppu[0], loppu[1]);
	else
	var d2 = new Date(2016, 0, 20, loppu[0], loppu[1]);

	var d1 = new Date(2016, 0, 20, alku[0], alku[1]);
	var seconds =  (d2- d1)/1000;
	var sec = seconds;
	var h = sec/3600 ^ 0 ;
	var m = (sec-h*3600)/60 ^ 0 ;

	$("#pituus").val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
  }

  $('#alku').blur(function(){
	var alku = $("#alku").val().split(':');
	if(!alku[1])
	{
		var h = $("#alku").val() ^ 0 ;
		var m = 0 ^ 0 ;
		$("#alku").val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
		laskePituus();
	}
  });

  $('#loppu').blur(function(){
	var alku = $("#loppu").val().split(':');
	if(!alku[1])
	{
		var h = $("#loppu").val() ^ 0 ;
		var m = 0 ^ 0 ;
		$("#loppu").val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
		laskePituus();
	}
  });


  $('#alku').keyup(function(){
	laskePituus();
  });

  $('#loppu').keyup(function(){
	laskePituus();
  });

  $('#alku').change(function(){
	laskePituus();
  });

  $('#loppu').change(function(){
	laskePituus();
  });



  $('#Tyovuoroot_kohde').change(function(){

	var thisID = $(this).val();

	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/showohje?id='+thisID,
		  success:function(data){
			//console.log(data);
			var d = JSON.parse(data).split("//");
			$('.ohje').text(d[0]);
			$('#Tyovuoroot_tietoja').val(d[1]);

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  });


  $('#tekijanVaihdo').change(function(){
	var thisId = $(this).val();
	$('#Tyovuoroot_tid').val(thisId);
  });



});
</script>
