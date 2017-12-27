<?php
/* @var $this CrmSopimuksetController */
/* @var $model CrmSopimukset */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'crm-tarjoukset-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


<!-- hattu -->
<div class="row">
     <div class="col-sm-3">
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'template'); ?>
		<?php
		$tmp_list = array();
		foreach(glob(Yii::app()->baseUrl.$this->templates_polkku().'/*.docx') as $file) 
		{
			$explNimi = explode("/",$file);
		 	$tmp_list[end($explNimi)] = end($explNimi);
		}
		?>
		<?php
        		echo $form->dropDownList($model, 'template', $tmp_list,
			array('empty'=>'Valitse', 'class'=>'form-control'));
		?>
		<?php echo $form->error($model,'template'); ?>
	</div>

     </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'voimassa'); ?>
		<?php echo $form->textField($model,'voimassa',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'voimassa'); ?>
	</div>

     </div><div class="col-sm-3">

	<div class="section fill mb5">

		<?php echo $form->labelEx($model,'tuote_palvelu'); ?>
		<?php echo $form->dropDownList($model, 'tuote_palvelu', CHtml::listData(LaskutusTuotteet::model()->findAll(), 'tuotenimi', 'tuotenimi'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'tuote_palvelu'); ?>
	</div>

     </div>
</div>
<hr>
<!-- hattu -->

<div class="row">
  <div class="col-sm-3">

	<legend><?php echo Yii::t('main', 'Perustiedot'); ?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php
			$a_controller = Yii::app()->createController('Asiakkaat');
			$list = $a_controller[0]->asiakkaatArrHelper(true);

        		echo $form->dropDownList($model, 'asiakas_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));
		
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'asiakkaan_sahkoposti'); ?>
		<?php echo $form->textField($model,'asiakkaan_sahkoposti',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asiakkaan_sahkoposti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarjous'); ?>
		<?php echo $form->textArea($model,'tarjous',array('class'=>'form-control', 'rows'=>4)); ?>
		<?php echo $form->error($model,'kohteen_osoite'); ?>
	</div>


	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'tarvikkeet'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='tarjous_tarvikkeet' ",array('order' => "select_type"));
		if(count($l) == 0)
      		{
			$new_val = new Valikkoot;
			$new_val->select_type = "tarjous_tarvikkeet";
			$new_val->value = "Testi tarvike";
			if($new_val->save())
	      			$l = Valikkoot::model()->findAll(" select_type='tarjous_tarvikkeet' ",array('order' => "select_type"));
			else
				var_dump($new_val->getErrors());
		}

			$arr = json_decode($model->tarvikkeet);
			echo '<select name="CrmSopimukset[tarvikkeet][]" class="tarvikkeet form-control" multiple title="Valitse">';
			foreach($l as $data)
			{
				if(is_array($arr) and in_array($data->id, $arr))
			    		echo '<option value="'.$data->id.'" selected>'.$data->value.'</option>';
				elseif(!is_array($arr) and $model->tarvikkeet == $data->id)
			    		echo '<option value="'.$data->id.'" selected>'.$data->value.'</option>';
				else
			    		echo '<option value="'.$data->id.'">'.$data->value.'</option>';
			}
			echo '</select>';
		
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="tarjous_tarvikkeet"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'ryhma'); ?>
	</div>

 </div><div class="col-sm-3">

	<legend><?php echo Yii::t('main', 'Kohde'); ?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohde_id'); ?>
		<?php
		$list = array();

		if( $model->asiakas_id != 0 )
		{
		$criteria=new CDbCriteria;
		$criteria->condition=" asiakas_id='".$model->asiakas_id."' ";
      		$l = Kohteet::model()->findAll($criteria);
		    foreach($l as $v)
			$list[$v->id] = $v->osoite;
		}
        	echo $form->dropDownList($model, 'kohde_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));		
        	?>
		<?php echo $form->error($model,'kohde_id'); ?>
	</div>

	<div class="section fill mb5 tyonkuvaus collapse">
		<?php echo $form->labelEx($model,'tyonkuvaus_id'); ?>
		<?php
		$list = array();

		if( $model->kohde_id != 0 )
		{
			echo '<script>$(document).ready(function(){$(\'.tyonkuvaus\').addClass(\'in\');});</script>';
			$criteria=new CDbCriteria;
			$criteria->condition=" kohde_id='".$model->kohde_id."' AND aktiivinen=1 ";
	      		$l = Tyonkuvaus::model()->findAll($criteria);
			if( $l != null )
			{
			    foreach($l as $v)
			    {
				$k = Kohteet::model()->findByPk($v->kohde_id);
				if(isset($k->id))
				$list[$v->id] = date("d.m.Y", strtotime($v->time)).' - '.$k->osoite;
			    }
			}
		}

        	echo $form->dropDownList($model, 'tyonkuvaus_id', $list,
			array('empty'=>'Valitse','class'=>'form-control required'));
		
        	?>
		<?php echo $form->error($model,'tyonkuvaus_id'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'kohteen_osoite'); ?>
		<?php echo $form->textField($model,'kohteen_osoite',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohteen_osoite'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'kohteen_postinumero'); ?>
		<?php echo $form->textField($model,'kohteen_postinumero',array('maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohteen_postinumero'); ?>
	</div>

	<div class="section fill mb5 kohdeHide">
		<?php echo $form->labelEx($model,'kohteen_postitoimipaikka'); ?>
		<?php echo $form->textField($model,'kohteen_postitoimipaikka',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohteen_postitoimipaikka'); ?>
	</div>



 </div><div class="col-sm-6">

	<legend><?php echo Yii::t('main', 'Asiakkaan tiedot'); ?></legend>
	<div class="section fill mb5 kohdeHide">

		<div id="asiakas_tiedot">
		<?php
		if( $model->asiakas_id != 0 )
		{
			$a = Asiakkaat::model()->findByPk($model->asiakas_id);
			if(isset($a->id))
			{
			echo $this->renderPartial('//asiakkaat/view', 
				array('id'=>$a->id, 'model'=>$a)
			, true);
			}
		}
		?>
		</div>
	</div>

 </div><div class="col-sm-12">

<!-- HINTA -->
<div id="rivit" class="table-responsive">
<TABLE class="table well" id="TableRivit">

     <TR>
     <thead class="myBgColors">
	<TH style="width:1%"><span id="uusiRivi" class="link" style="font-size: 150%;"><i class="fa fa-plus-square"></i></span></TH>
	<TH class="col-sm-2">Tuote/Palvelu</TH>
	<TH class="col-sm-1">Kpl</TH>
	<TH class="col-sm-1">Yksikkö <span class="btn btn-primary btn-xs myBgColors muokaValiko" for="laskutus_yksikko"><i class="fa fa-pencil-square-o"></i></span></TH>
	<TH class="col-sm-1">Hinta</TH>
	<TH class="col-sm-1">ALV %</TH>
	<TH class="col-sm-1">ALV</TH>
	<TH class="col-sm-1">Ale %</TH>
	<TH class="col-sm-1">Veroton</TH>
	<TH class="col-sm-1">Yhteensä</TH>
     </thead>
     </TR>

     <tbody>
	<?php
		if(isset($_GET['sopimus_tarjouksesta']) and isset($_GET['id']))
		$trRivit=TarjousHintaRivit::model()->findAll("tarjous_id='".$_GET['id']."'", array('order'=>'id'));
		else
		$trRivit=SopimusHintaRivit::model()->findAll("sopimus_id='".$model->id."'", array('order'=>'id'));

		if( count($trRivit) == 0 )
		{
			echo $this->renderPartial("tr_rivit_tyhja",array('num'=>0));
		} else {
			$num = 0;
			foreach($trRivit as $rivi){ 
			$num++;
			echo $this->renderPartial("tr_rivi_update",array('num'=>$num,'rivi'=>$rivi));
			}
		}
	?>

     </tbody>

     <tfoot>
     <TR>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD><input type="text" class="form-control" size="10" name="CrmSopimukset[yhteensa_total_verot]" id="yhteensa_total_verot" readonly></TD>
	<TD></TD>
	<TD><input type="text" class="form-control" size="10" name="CrmSopimukset[yhteensa_total_veroton]" id="yhteensa_total_veroton" readonly></TD>
	<TD><input type="text" class="form-control" size="10" name="CrmSopimukset[yhteensa_total]" id="yhteensa_total" readonly></TD>
     </TR>
     </tfoot>
</TABLE>
</div>
<!-- HINTA -->


 </div>
</div><!-- form -->




<!-- HINTA -->
<script type="text/javascript">
$(document).ready(function(){

$("#uusiRivi").click(function() {
    var rivi = $("#samaRivi").html();
    var rowCount = makeid();

        $.ajax({
           url: 'tr_rivit_tyhja',
           type: "POST",
           data: {num : rowCount},
           success: function(html){
         	$("table#TableRivit tbody tr").last().after(html);
	  	Rivi();
           }
        });
});


function makeid()
{
    var text = "";
    var possible = "123456789";

    for( var i=0; i < 7; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function jumpToPageBottom() {
    $('html, body').animate({scrollTop:1000}, 'slow');
    return false;
}



$(document).delegate("table#TableRivit .valitseTuote","change",function(){

    var tuoteID = $(this).val();
    var num = $(this).attr("num");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/valitsetuote',
           type: "POST",
           data: { tuoteID : tuoteID },
           success: function(data){
		var sp = JSON.parse(data);

		if(sp['id'])
		{
			$("#kpl_"+num).val(1);
			$("#tkoodi_"+num).val(sp['tuotenimi']);
			$("#hinta_"+num).val(parseFloat(sp['hinta_alv_0']));
			$("#yksikko_"+num+" option[value="+sp['yksikko']+"]").attr('selected','selected');
			$("#alv_"+num+" option[value="+sp['alv']+"]").attr('selected','selected');
			$("#tuoteID_"+num).val(sp['id']);
		}
		eachLaskenta();
		console.log(data)
           }
        });

});






  $(document).delegate(".poista","click",function(){
	$(this).closest('tr').remove();
	yhteensaTotal();
  });



Rivi();
function Rivi(){

  $(".onlyDigits ").attr('type', 'number').attr('step', '0.01');

}


  eachLaskenta();

  var aleAsiakkaasta = '';
function eachLaskenta(){

  $("#rivit input").each(function() {

	var hinta_alv_0 = 0;
	var alv = 0;
	var kpl = 0;
	var ale = 0;

	var inputKenta = $(this).attr("id").split("_");
	if($("#hinta_"+inputKenta[1]).val()) { hinta_alv_0 = parseFloat($("#hinta_"+inputKenta[1]).val()) };
	if($("#alv_"+inputKenta[1]).val()) { alv = parseFloat($("#alv_"+inputKenta[1]).val()) };
	if($("#kpl_"+inputKenta[1]).val()) { kpl = parseFloat($("#kpl_"+inputKenta[1]).val()) };
	if($("#ale_"+inputKenta[1]).val()) { ale = parseFloat($("#ale_"+inputKenta[1]).val()) };


	inputKenta[1] = parseFloat(inputKenta[1], 10);

	if(ale > 0)
	hinta_alv_0 = hinta_alv_0-((hinta_alv_0/100)*ale);

	var laske = parseFloat(((hinta_alv_0*kpl)/100*alv), 10);
	var veroton = parseFloat(hinta_alv_0, 10)*kpl;
	yhteensa = laske+veroton;

	$("#hinta_alv_"+inputKenta[1]).val((laske).toFixed(2));
	$("#veroton_"+inputKenta[1]).val(veroton.toFixed(2));
	$("#yhteensa_alv_"+inputKenta[1]).val(yhteensa.toFixed(2));

  });
    	yhteensaTotal();

}

function yhteensaTotal(){

	var sum = 0;
	$('.yhteensa_total_verot').each(function(){
	    sum += parseFloat(this.value);
	    $('#yhteensa_total_verot').val(sum.toFixed(2));
	});
	var sum1 = 0;
	$('.yhteensa_total_veroton').each(function(){
	    sum1 += parseFloat(this.value);
	    $('#yhteensa_total_veroton').val(sum1.toFixed(2));
	});
	var sum2 = 0;
	$('.yhteensa_total').each(function(){
	    sum2 += parseFloat(this.value);
	    $('#yhteensa_total').val(sum2.toFixed(2));
	});
}


  $(document).delegate('#rivit input[type="number"]','keyup',function(){
  	eachLaskenta();
    	yhteensaTotal();
  });

});
</script>
<!-- HINTA -->



	<div class="section fill mb5">
		<div id="tyonkuvaus"></div>
	</div>


<br>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors submitButton')); ?>
	</div>

<?php $this->endWidget(); ?>



<?php if(isset($model->tyonkuvaus_id) and !empty($model->tyonkuvaus_id)) : ?>
<script type="text/javascript">
$(document).ready(function(){

        $.ajax({
           url: 'view_tyonkuvaus?id=<?php echo $model->tyonkuvaus_id; ?>',
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		//console.log(data);
		if(data)
		{
			$('#tyonkuvaus').html('<br>' + data);
		}
           }
        });

});
</script>
<?php endif; ?>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>



<script type="text/javascript">
$(document).ready(function(){


/* valikot */
$(".muokaValiko").click(function() {
    var thisFor = $(this).attr("for");
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko",
	   type:'POST',
	   data: { "select_type" : thisFor },
           success: function(data){
		console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */

$('.tarvikkeet').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Tarvikkeet"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
});


 $('#crm-tarjoukset-form').on("submit", function(e){

    $(this).submit();
    e.preventDefault();

 });


 $('#CrmSopimukset_tyonkuvaus_id').change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: 'get_tyonkuvaus?id=' + thisVal,
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		//console.log(data);
		if(data)
		{
			$('#tyonkuvaus').html('<br>' + data);
		}
           }
        });

 });


 $('#CrmSopimukset_kohde_id').change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: 'get_kohteentiedot?id=' + thisVal,
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		console.log(data);
		if(data['kohde'])
		{
			$('#CrmSopimukset_kohteen_osoite').val(data['kohde']['osoite']);
			$('#CrmSopimukset_kohteen_postinumero').val(data['kohde']['pnumero']);
			$('#CrmSopimukset_kohteen_postitoimipaikka').val(data['kohde']['kaupunki']);
		}

		if(data['tk'] !== '')
		{
			$('.tyonkuvaus').addClass('in');
			$('#CrmSopimukset_tyonkuvaus_id').html(data['tk']);
		} else {
			$('.tyonkuvaus').removeClass('in');
			$('#CrmSopimukset_tyonkuvaus_id').html(data['tk']);
		}
           }
        });

 });


 $('#CrmSopimukset_asiakas_id').change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: 'get_kohde?id=' + thisVal,
           //type: "POST",
           //data: { },
           success: function(data){
		var data = JSON.parse(data);
		console.log(data);
		if(data['options'])
		{
			$('#CrmSopimukset_kohde_id').html(data['options']);
		}
		if(data['asiakas_sahkoposti'])
		{
			$('#CrmSopimukset_asiakkaan_sahkoposti').val(data['asiakas_sahkoposti']);
		}
		if(data['asiakas_tiedot'])
		{
			$('#asiakas_tiedot').html(data['asiakas_tiedot']);
		}

           }
        });

 });


});
</script>

