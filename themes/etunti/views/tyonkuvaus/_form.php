<?php
/* @var $this TyonkuvausController */
/* @var $model Tyonkuvaus */
/* @var $form CActiveForm */
/*  laatutasot muutetaan viikkovaliksi */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyonkuvaus-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


<div class="row">
  <div class="col-sm-4">


	<?php echo $form->errorSummary($model); ?>


	<div class="section fill mb5">

		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php
			$asiakas_selected[Yii::app()->request->getParam('asiakas_id')] = array('selected' => 'selected');

			$a_controller = Yii::app()->createController('Asiakkaat');
			$list = $a_controller[0]->asiakkaatArrHelper(true);

        		echo $form->dropDownList($model, 'asiakas_id', $list,
			array('empty'=>'Valitse','class'=>'form-control', 'options'=>$asiakas_selected));
		
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5">

			    <?php echo $form->labelEx($model,'asiakas_id'); ?>
			    <div class="input-group">
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				if(isset($_GET['asiakas_id']))
				{
			      	$la = Asiakkaat::model()->findByPk($_GET['asiakas_id']);

					if(isset($la->id))
						$postvalue = $la->Fullname; 

				} else {
					$postvalue='';
				}
		 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			      <span class="input-group-btn">
			        <button class="btn btn-default go" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
			      </span>
			    </div>
			<!-- Autocomplete -->

			<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

<script type="text/javascript">
$(document).ready(function(){

 $("#Tyonkuvaus_asiakas_id").change(function(){
	window.location.href= "create?asiakas_id=" + $(this).val();
 });

 $(".go").click(function(){

	var thisVal = $("#yrityksen_nimi").val()
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/getasiakasidbynimi?nimi="+ thisVal,
           success: function(data){
		if(data !== '0'){
		  window.location.href= "create?asiakas_id=" + data;
		}
           }
        });

 });


});
</script>

	<?php if(isset($_GET['asiakas_id'])) : ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohde_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		$criteria->condition=" asiakas_id='".$_GET['asiakas_id']."' ";
      		$l = Kohteet::model()->findAll($criteria);
		if( $l != null )
		{
		    foreach($l as $v)
		    {
			$list[$v->id] = $v->osoite;
		    }
		}

		if(count($list) > 0)
		{
        		echo $form->dropDownList($model, 'kohde_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));
		}		
        	?>
		<?php echo $form->error($model,'kohde_id'); ?>
	</div>
<script type="text/javascript">
$(document).ready(function(){

	checkKohde();
 $("#Tyonkuvaus_kohde_id").change(function(){
	checkKohde();
 });

 function checkKohde(){
	if( $("#Tyonkuvaus_kohde_id option:selected").val() !== '' )
	    $('#openTable').addClass('in');
	else
	    $('#openTable').removeClass('in');
 }

});
</script>
	<?php endif; ?>


<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'otsikko'); ?>
		<?php echo $form->textField($model,'otsikko',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'otsikko'); ?>
	</div>
*/ ?>

	<?php if(isset($model->id)) : ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php 
        	$tal = array(0=>'Ei',1=>'Kyllä');
		echo $form->dropDownList($model,'aktiivinen', $tal, 
		array('class'=>'form-control','id'=>'osoite')) ?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>
	<?php endif; ?>

 </div>
</div>

	<div id="openTable" class="collapse">
	<hr>
	<div class="section fill mb5">


	<?php 
	$a = Valikkoot::model()->findAll(" select_type='tyonkuvaus_tilat' ");
	if(!isset($a[0]))
	{
		$a = new Valikkoot;
		$a->select_type = 'tyonkuvaus_tilat';
		$a->value = 'DEMO';
		if($a->save())
			$a = Valikkoot::model()->findAll(" select_type='tyonkuvaus_tilat' ");
	}
        $tal = array();
	foreach($a as $v){
	   $tal[$v->value] = $v->value;
	}
	
	$pvm_arr = array(
		'MA' => 'MA',
		'TI' => 'TI',
		'KE' => 'KE',
		'TO' => 'TO',
		'PE' => 'PE',
		'LA' => 'LA',
		'SU' => 'SU',
	);

	$asetukset = Asetukset::model()->findByPk(1);
	$viikkovali = array(1 => 1, 2 => 2, 3 => 3, 4 => 4);
	?>


		<table class="table table-bordered authors-list">
		    <tr>
		        <th></td><td><?php echo Yii::t('main','Tilat'); ?></th>
			<th><?php echo Yii::t('main','Työtehtävät ja päivät'); ?></th>
			<th><?php echo Yii::t('main','Kommenti'); ?></th>
		    </tr>
		<?php
		if(isset($model->id))
		{
			$criteria=new CDbCriteria;
			$criteria->condition="tyonkuvaus_id='".$model->id."'";
			$rivit = TyonkuvausRivit::model()->findAll($criteria);
		}
		?>
		<?php if( isset($model->id) and isset($rivit) and count($rivit) > 0 ) : ?>
		<?php
		foreach($rivit as $key=>$r)
		{


		$sv = explode("\n", json_decode($r->tilat, true));
		$selectedValues = array();
		foreach($sv as $itm)
		{
			$selectedValues[trim($itm)] = array('selected' => 'selected');
		}


		echo '
		    <tr class="rivi" num="'.$key.'">
		        <td>
		            <i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i>
		        </td>
		        <td>
			    <div class="tyonkuvaus_tilat">
	   			<div class="input-group">
					'.CHtml::dropDownList('', '', $tal, 
						array('multiple' => 'multiple', 
						'class'=> 'form-control mult tyonkuvaus_tilat_selecter',
						'options' => $selectedValues,
						)
					).'
					<span class="input-group-btn">
					  <span class="btn btn-primary myBgColors muokaValiko" for="tyonkuvaus_tilat"><i class="fa fa-pencil-square-o"></i></span>
					  <span class="btn btn-primary myBgColors" data-toggle="collapse" data-target="#tilat_caret_'.$key.'"><i class="caret"></i></span>
					</span>
				</div>
			    </div>
			    <div class="collapse" id="tilat_caret_'.$key.'">
		            <textarea class="form-control tilat" rows="4" name="TyonkuvausRivit[tilat]['.$key.']" />'.json_decode($r->tilat).'</textarea>
			    </div>
		        </td>
		        <td class="tyotehtavatVkoPvmTD">

		        <a href="#" title="" class="add-author-tyotehtavat" num="0"><i class="fa fa-plus" aria-hidden="true"></i></a>
			<table class="table authors-list-tyotehtavat">
			   <tr>
			    <th></th>
			    <th>'.Yii::t('main', 'Työtehtävä').'</th>
			    <th>'.Yii::t('main', 'Vko. Pvm').'</th>
			    <th>'.Yii::t('main', 'Viikkoväli').'</th>
			   </tr>
			';

			$tyontehtavat = json_decode($r->tyontehtavat, true);
			foreach($tyontehtavat as $k2=>$r2)
			{

			if(isset($r2['vkopvm']) and isset($r2['vkovali']))
			{
			} else {
			continue;
			}


			$svVko = array();
			$svVko = explode(",", $r2['vkopvm']);
			$selectedValuesVkoPvm = array();
			foreach($svVko as $itmVko)
			{
				$selectedValuesVkoPvm[trim($itmVko)] = array('selected' => 'selected');
			}

			$selectedValuesViikkovali = array();
			$selectedValuesViikkovali[$r2['vkovali']] = array('selected' => 'selected');

			echo '
			    <tr class="rivi-tyotehtavat" num="'.$key.'">
			        <td>
			            <i class="link fa fa-trash poistaAuthorRiviT2" aria-hidden="true"></i>
			        </td>
			        <td>
			            <input class="form-control" type="text" name="TyonkuvausRivit[tyotehtava]['.$key.']['.$k2.']" value="'.$r2['tyotehtava'].'" />
			        </td>
			        <td>
					<div class="vkopvm_tilat">
					'.CHtml::dropDownList('', '', $pvm_arr, 
					array(
						'multiple' => 'multiple',
						'class'=> 'selectpicker vkoPvm_selecter',
						'options' => $selectedValuesVkoPvm,
					)).'
			            	<input type="hidden" name="TyonkuvausRivit[vkopvm]['.$key.']['.$k2.']" value="'.$r2['vkopvm'].'"/>
					</div>

			        </td>
			        <td>
					<div class="viikkovali_tilat">
					'.CHtml::dropDownList('', '', $viikkovali, 
					array(
						'empty'=>'Valitse viikkoväli',
						'class'=> 'viikkovali_selecter form-control',
						'options' => $selectedValuesViikkovali,
					)).'
					<input type="hidden" name="TyonkuvausRivit[vkovali]['.$key.']['.$k2.']" value="'.$r2['vkovali'].'"/>
					</div>

			        </td>
			    </tr>';
			}


		echo '
			</table>
		        </td>
		        <td>
		            <textarea class="form-control" rows="1" name="TyonkuvausRivit[kommenti]['.$key.']" />'.$r->kommenti.'</textarea>
		        </td>
		    </tr>
		';
		}
		?>
		<?php else : ?>
		    <tr class="rivi" num="0">
		        <td>
		            <i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i>
		        </td>
		        <td>
		    	    <div class="input-group">
				<?php echo CHtml::dropDownList('', '', $tal, 
					array('multiple' => 'multiple', 'class'=> 'form-control mult tyonkuvaus_tilat_selecter')
				); ?>
				<span class="input-group-btn">
				  <span class="btn btn-primary myBgColors muokaValiko" for="tyonkuvaus_tilat"><i class="fa fa-pencil-square-o"></i></span>
				  <span class="btn btn-primary myBgColors" data-toggle="collapse" data-target="#tilat_caret_0"><i class="caret"></i></span>
				</span>
			    </div>
			    <div class="collapse" id="tilat_caret_0">
		            <textarea class="form-control tilat" rows="4" name="TyonkuvausRivit[tilat][0]" /></textarea>
			    </div>
		        </td>
		        <td class="tyotehtavatVkoPvmTD">

		        <a href="#" title="" class="add-author-tyotehtavat" num="0"><i class="fa fa-plus" aria-hidden="true"></i></a>
			<table class="table authors-list-tyotehtavat">
			    <tr class="rivi-tyotehtavat" num="0">
			        <td>
			            <i class="link fa fa-trash poistaAuthorRiviT2" aria-hidden="true"></i>
			        </td>
			        <td>
			            <input class="form-control" type="text" name="TyonkuvausRivit[tyotehtava][0][0]" />
			        </td>
			        <td>
					<div class="vkopvm_tilat">
					<?php echo CHtml::dropDownList('', '', $pvm_arr, 
						array('multiple' => 'multiple', 'class'=> 'selectpicker vkoPvm_selecter')
					); ?>
			            	<input type="hidden" class="form-control" name="TyonkuvausRivit[vkopvm][0][0]" />
					</div>
			        </td>
			        <td>
					<div class="viikkovali_tilat">
					<?php echo CHtml::dropDownList('', '', $viikkovali, 
						array('empty'=>'Valitse viikkoväli','class'=> 'viikkovali_selecter form-control'
					)); ?>
			            	<input type="hidden" class="form-control" name="TyonkuvausRivit[vkovali][0][0]" />
					</div>

			        </td>
			    </tr>
			</table>
		        </td>
		        <td>
		            <textarea class="form-control" rows="1" name="TyonkuvausRivit[kommenti][0]" /></textarea>
		        </td>
		    </tr>
		<?php endif; ?>
		</table>
		<br>

		<p style="margin-left: 7px"><a href="#" title="" class="add-author"><i class="fa fa-plus fa-2x" aria-hidden="true"></i></a></p>

		<div class="input-group" id="kuvauksetTuoteesta" style="display:none">
		 <?php echo CHtml::dropDownList("", "", $tal, array("multiple" => "multiple", "class"=> "form-control mult tyonkuvaus_tilat_selecter")); ?>
		 <span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="tyonkuvaus_tilat"><i class="fa fa-pencil-square-o"></i></span>
		  <span class="btn btn-primary myBgColors for_tilat_collapse" data-toggle="collapse"><i class="caret"></i></span>
		 </span>
		</div>

		<div class="" id="vkoPvm" style="display:none">
			<?php echo CHtml::dropDownList('', '', $pvm_arr, 
				array('multiple' => 'multiple', 'class'=> 'selectpicker vkoPvm_selecter')
			); ?>
		</div>

		<div id="viikovaliAlasveto" style="display:none"> 
			<?php echo CHtml::dropDownList('', '', $viikkovali, 
				array('empty'=>'Valitse viikkoväli', 'class'=> 'viikkovali_selecter form-control')
			); ?>
		</div>

<script>
jQuery(function(){
    var counter = parseInt($('.authors-list tr:last').attr('num'))+1;
    var vkoPvm = $('#vkoPvm').html();
    var viikovaliAlasveto = $('#viikovaliAlasveto').html();
    var kuvauksetTuoteesta = $('#kuvauksetTuoteesta').html();

    $('a.add-author').click(function(event){
        event.preventDefault();

        var newRow = jQuery('<tr class="rivi" num="'+ counter +'">' +
	    '<td><i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i></td>' +
	    '<td><div class="input-group">'+ kuvauksetTuoteesta +'</div>' +
		'<div class="collapse" id="tilat_caret_'+ counter +'">' +
		'<textarea class="form-control tilat" rows="4" name="TyonkuvausRivit[tilat][' + counter + ']"/></textarea>' +
		'</div>' +
	    '</td>' +
	    '<td class="tyotehtavatVkoPvmTD">' +

		        '<a href="#" title="" class="add-author-tyotehtavat" num="'+ counter +'"><i class="fa fa-plus" aria-hidden="true"></i></a>' +
			'<table class="table authors-list-tyotehtavat">' +
			    '<tr class="rivi-tyotehtavat" num="'+ counter +'">' +
			        '<td>' +
			            '<i class="link fa fa-trash poistaAuthorRiviT2" aria-hidden="true"></i>' +
			        '</td>' +
			        '<td>' +
			            '<input class="form-control" type="text" name="TyonkuvausRivit[tyotehtava]['+ counter +'][0]" />' +
			        '</td>' +
			        '<td>' +
				    '<div class="vkopvm_tilat">' + vkoPvm +
			            	'<input type="hidden" class="form-control" name="TyonkuvausRivit[vkopvm]['+ counter +'][0]" />' +
				    '</div>' +
			        '</td>' +
			        '<td>' +
				    '<div class="viikkovali_tilat">' + viikovaliAlasveto +
			            	'<input type="hidden" class="form-control" name="TyonkuvausRivit[vkovali]['+ counter +'][0]" />' +
				    '</div>' +
			        '</td>' +
			    '</tr>' +
			'</table>' +
	    '</td>' +
	    '<td><textarea class="form-control" rows="1" name="TyonkuvausRivit[kommenti][' + counter + ']"/></textarea></td>' +
	    '</tr>');

        counter++;
        jQuery('table.authors-list').append(newRow);

	var num = jQuery(newRow).find('.rivi-tyotehtavat').attr('num');
	jQuery(newRow).find('.for_tilat_collapse').attr('data-target', '#tilat_caret_' + num);


 	$(".mult").multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Valitse"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
	}); 

	$('.selectpicker').selectpicker({
	  //style: 'btn-info',
	  title: 'Vko. pvm',
	  size: 4
	});

    });
    $(document).delegate(".poistaAuthorRivi","click",function(){
	$(this).closest('tr.rivi').remove();
    });
});
</script>

<script>
jQuery(function(){

    var vkoPvm = $('#vkoPvm').html();
    var viikovaliAlasveto = $('#viikovaliAlasveto').html();

    $(document).delegate(".add-author-tyotehtavat","click",function(event){

	var trLength = $(this).closest('.tyotehtavatVkoPvmTD').find('table.authors-list-tyotehtavat tr.rivi-tyotehtavat').length;

	var counter = parseInt(trLength);
	var num = $(this).attr('num');

        event.preventDefault();

        var newRow = jQuery('<tr class="rivi-tyotehtavat" num="'+ num +'">' +
	    '<td><i class="link fa fa-trash poistaAuthorRiviT2" aria-hidden="true"></i></td>' +
	    '<td><input type="text" class="form-control" name="TyonkuvausRivit[tyotehtava][' + num + ']['+ counter +']"/></td>' +
	    '<td><div class="vkopvm_tilat">'+ vkoPvm +'<input type="hidden" class="form-control" name="TyonkuvausRivit[vkopvm][' + num + ']['+ counter +']"/></div></td>' +
	    '<td><div class="viikkovali_tilat">'+ viikovaliAlasveto +'<input type="hidden" class="form-control" name="TyonkuvausRivit[vkovali][' + num + ']['+ counter +']"/></div></td>' +
	    '</tr>');
            counter++;
        jQuery(this).closest('.tyotehtavatVkoPvmTD').find('table.authors-list-tyotehtavat').append(newRow);


	$('.selectpicker').selectpicker({
	  //style: 'btn-info',
	  title: 'Vko. pvm',
	  size: 4
	});


    });
    $(document).delegate(".poistaAuthorRiviT2","click",function(){
	$(this).closest('tr.rivi-tyotehtavat').remove();
    });
});
</script>

	</div>
	</div>


	<br>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors submitKuvaus')); ?>
	</div>

<?php $this->endWidget(); ?>



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
		//console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */


 $(".mult").multiselect({

	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Valitse"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',

	numberDisplayed: 0,
	buttonWidth: '100%',
 }); 

 $('.selectpicker').selectpicker({
	  //style: 'btn-info',
	  title: 'Vko. pvm',
	  size: 4
 });

 $(document).delegate(".tyonkuvaus_tilat_selecter","change",function(){
	var thisVal = $(this).val().join('\n');
    	$(this).closest('tr').find('textarea.tilat').val(thisVal);
 });

 $(document).delegate(".vkoPvm_selecter","change",function(){
	var thisVal = $(this).val();
    	$(this).closest('div.vkopvm_tilat').find('input').val(thisVal);
 });


 $(document).delegate(".viikkovali_selecter","change",function(){
	var thisVal = $(this).val();
    	$(this).closest('div.viikkovali_tilat').find('input').val(thisVal);
 });

 $(document).delegate(".submitKuvaus","click",function(e){
	e.preventDefault();
	var returnThis = true;
	$( ".authors-list-tyotehtavat" ).find('.viikkovali_selecter').removeClass('btn-danger');
	$( ".authors-list-tyotehtavat" ).find('.viikkovali_selecter').each(function( index ) {
	   if($(this).val() === '')
	   {
		$(this).addClass('btn-danger').focus();
		returnThis = false;
	   }
	});

	if(returnThis)
	$('#tyonkuvaus-form').submit();
 });

});
</script>

