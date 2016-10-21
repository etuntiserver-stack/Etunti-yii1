<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
/* @var $form CActiveForm */

?>

<div class="section">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyovuoroot-form',
	'enableAjaxValidation'=>false,

)); ?>


	<?php echo $form->errorSummary($model); ?>


<legend><h2><?php echo Yii::t('main', 'Asiakas'); ?></h2></legend>
<div class="row">
  <div class="col-sm-3">
    <div class="section">
		<label>Asiakkaan tyyppi</label>
		<select name="asiakas_tyyppi" class="form-control">
		<option value="yritys"><?php echo Yii::t('main', 'Yritys'); ?></option>
		<option value="henkilo"><?php echo Yii::t('main', 'Henkilö'); ?></option>
		</select>
    </div>
    <div class="section">
		<label><?php echo Yii::t('main', 'Hinta'); ?></label>
		<input type="text" name="hinta" class="form-control">
    </div>
  </div>
  <div class="col-sm-3">
    <div class="section">
		<label><?php echo Yii::t('main', 'Asiakkaan nimi'); ?></label>
		<input type="text" name="yhteyshenkilo" class="form-control">
    </div>
    <div class="section">
		<label><?php echo Yii::t('main', 'Asiakkaan osoite'); ?></label>
		<input type="text" name="osoite" class="form-control">
    </div>
  </div>
  <div class="col-sm-3">
    <div class="section">
		<label><?php echo Yii::t('main', 'Asiakkaan postinumero'); ?></label>
		<input type="text" name="postinumero" class="form-control">
    </div>
    <div class="section">
		<label><?php echo Yii::t('main', 'Asiakkaan postitoimipaikka'); ?></label>
		<input type="text" name="kaupunki" class="form-control">
    </div>
  </div>
  <div class="col-sm-3">
    <div class="section">
		<label><?php echo Yii::t('main', 'Asiakkaan puhelin'); ?></label>
		<input type="text" name="puhelin" class="form-control">
    </div>
    <div class="section">
		<label><?php echo Yii::t('main', 'Asiakkaan sähköposti'); ?></label>
		<input type="text" name="sahkoposti" class="form-control">
    </div>
  </div>
</div>




<legend><h2><?php echo Yii::t('main', 'Työvuoro'); ?></h2></legend>
<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('value'=>date("d.m.Y"),'size'=>20,'maxlength'=>20,'class'=>'form-control datepickerFI'));//,'readonly'=>'yes' ?>
		<?php echo $form->error($model,'pvm'); ?>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'alku'); ?>
		<input type="text" name="Tyovuoroot[alku]" class="form-control laske timeVuorot" id="alku" autofocus>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<input type="text" name="Tyovuoroot[loppu]" class="form-control laske timeVuorot" id="loppu">
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pituus'); ?>
		<input type="text" name="Tyovuoroot[pituus]" id="pituus" class="form-control timeVuorot">
  </div>

</div>


<div class="row">

  <div class="col-sm-6">
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
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php
		$list = array(0=>Yii::t('main', 'VARAUS'));
        	$list[] = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'tekijan_nimi');
        	echo $form->dropDownList($model, 'tid', $list,array('empty'=>'Valitse','class'=>'form-control'));
        	?>
  </div>

  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textarea($model,'tietoja',array('rows'=>4,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
  </div>
</div>

		<br>
		<input type="checkbox" name="vieposti"> <?php echo Yii::t('main','Ilmoita asiakkaalle sähköpostilla'); ?>

</div>

		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->

<br>

	<div class="modal-footer">
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php 
		if(Yii::app()->user->adminStatus != 2)
		echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn  btn-primary','id'=>'submitButton')); 
		?>
	</div>		




<?php $this->endWidget(); ?>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>


<script type="text/javascript">
$(document).ready(function(){

  $('.timeVuorot').mask('00:00',{
        placeholder: "__:__"
  });

  $(".sw").bootstrapSwitch({
	size: "small",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });

	$('#submitButton').click(function(){
		var r = confirm('Olet myös luomassa uuden asiakkaan ja kohteen.\n Haluatko jatkaa?');
		if(r)
		$('#tyovuoroot-form').submit();
		else
		return false;
	});

	$('#tyovuoroot-form').on('submit',function(e) {

	//console.log( $( this ).serializeArray() );
	//console.log( e.target[0].value );
	var str = '';


	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/uusitilaus',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			thisDataReturn = JSON.parse(data);
			console.log(data);
			laatikonPaivays(thisDataReturn);
			$('#showres').modal('hide');
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });



	e.preventDefault(); 
	});



function laatikonPaivays(thisDataReturn){

		var splDID = [];
		var did = '';
		var ilmoitus = '';
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

	var d2 = new Date(2014, 0, 31, loppu[0], loppu[1]);
	var d1 = new Date(2014, 0, 31, alku[0], alku[1]);
	var seconds =  (d2- d1)/1000;
	var sec = seconds;
	var h = sec/3600 ^ 0 ;
	var m = (sec-h*3600)/60 ^ 0 ;

	$("#pituus").val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
  }


  $('#alku').blur(function(){
	var alku = $("#alku").val().split(':');
	if(!alku[1] & $("#alku").val() !== '')
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






});
</script>
