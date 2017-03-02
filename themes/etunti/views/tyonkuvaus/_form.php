<?php
/* @var $this TyonkuvausController */
/* @var $model Tyonkuvaus */
/* @var $form CActiveForm */
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
		<?php echo $form->labelEx($model,'yhteystiedot_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		//$criteria->condition="";
      		$l = Yhteystiedot::model()->findAll($criteria);
		foreach($l as $v)
		{
			if(!empty($v->yrityksen_nimi) and empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yrityksen_nimi;
			elseif(empty($v->yrityksen_nimi) and !empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yhteyshenkilo;
		}

        		echo $form->dropDownList($model, 'yhteystiedot_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));
		
        	?>
		<?php echo $form->error($model,'yhteystiedot_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		//$criteria->condition="";
      		$l = Asiakkaat::model()->findAll($criteria);
		foreach($l as $v)
		{
			if(!empty($v->yrityksen_nimi) and empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yrityksen_nimi;
			elseif(empty($v->yrityksen_nimi) and !empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yhteyshenkilo;
		}

        		echo $form->dropDownList($model, 'asiakas_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));
		
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'otsikko'); ?>
		<?php echo $form->textField($model,'otsikko',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'otsikko'); ?>
	</div>


 </div>
</div>

	<?php if( isset($model->id)) : ?>
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
	$laatutasot = array(
			$asetukset->edico_laatutaso_1 => $asetukset->edico_laatutaso_1,
			$asetukset->edico_laatutaso_2 => $asetukset->edico_laatutaso_2,
			$asetukset->edico_laatutaso_3 => $asetukset->edico_laatutaso_3);
	?>


	      <a href="#" title="" class="add-author"><i class="fa fa-plus" aria-hidden="true"></i></a>

		<table class="table table-bordered authors-list">
		    <tr>
		        <td></td><td><?php echo Yii::t('main','Tilat'); ?></td>
			<td><?php echo Yii::t('main','Työtehtävät'); ?></td>
			<td><?php echo Yii::t('main','Laatutaso'); ?></td>
			<td><?php echo Yii::t('main','Kommenti'); ?></td>
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
					</span>
				   </div>
			    </div>
		            <textarea class="form-control tilat" rows="4" name="TyonkuvausRivit[tilat]['.$key.']" />'.json_decode($r->tilat).'</textarea>
		        </td>
		        <td class="tyotehtavatVkoPvmTD">

		        <a href="#" title="" class="add-author-tyotehtavat" num="0"><i class="fa fa-plus" aria-hidden="true"></i></a>
			<table class="table authors-list-tyotehtavat">
			    <tr>
			        <td></td><td>'.Yii::t('main','Työtehtävä').'</td><td>'.Yii::t('main','Vko. päivämäärät').'</td>
			    </tr>';

			$tyontehtavat = json_decode($r->tyontehtavat, true);
			foreach($tyontehtavat as $k2=>$r2)
			{

			$svVko = array();
			$svVko = explode(",", $r2['vkopvm']);
			$selectedValuesVkoPvm = array();
			foreach($svVko as $itmVko)
			{
				$selectedValuesVkoPvm[trim($itmVko)] = array('selected' => 'selected');
			}

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
			            	<input type="hidden" class="form-control" name="TyonkuvausRivit[vkopvm]['.$key.']['.$k2.']" value="'.$r2['vkopvm'].'"/>
					</div>
				    </div>
			        </td>
			    </tr>';
			}


		$svLT = array();
		$svLT = explode("\n",json_decode($r->laatutaso, true));
		$selectedValuesLaatutasot = array();
		foreach($svLT as $itmVko)
		{
			$selectedValuesLaatutasot[trim($itmVko)] = array('selected' => 'selected');
		}

		$laatutaso = json_decode($r->laatutaso, true);

		echo '
			</table>


		        </td>
		        <td>
		    	    <div class="laatutasot_tilat">
				'.CHtml::dropDownList('', '', $laatutasot, 
					array(
						'multiple' => 'multiple',
						'class'=> 'selectpickerTasot laatutasot_selecter',
						'options' => $selectedValuesLaatutasot,
				)).'
		            <textarea class="form-control" rows="4" name="TyonkuvausRivit[laatutaso][0]" />'.$laatutaso.'</textarea>
			    </div>
		        </td>
		        <td>
		            <textarea class="form-control" name="TyonkuvausRivit[kommenti][0]" />'.$r->kommenti.'</textarea>
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
				</span>
			    </div>
		            <textarea class="form-control tilat" rows="4" name="TyonkuvausRivit[tilat][0]" /></textarea>
		        </td>
		        <td class="tyotehtavatVkoPvmTD">

		        <a href="#" title="" class="add-author-tyotehtavat" num="0"><i class="fa fa-plus" aria-hidden="true"></i></a>
			<table class="table authors-list-tyotehtavat">
			    <tr>
			        <td></td><td><?php echo Yii::t('main','Työtehtävä'); ?></td><td><?php echo Yii::t('main','Vko. päivämäärät'); ?></td>
			    </tr>
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
			    </tr>
			</table>


		        </td>
		        <td>
		    	    <div class="laatutasot_tilat">
				<?php echo CHtml::dropDownList('', '', $laatutasot, 
					array('multiple' => 'multiple', 'class'=> 'selectpickerTasot laatutasot_selecter')
				); ?>
		            	<textarea class="form-control" rows="4" name="TyonkuvausRivit[laatutaso][0]" /></textarea>
			    </div>
		        </td>
		        <td>
		            <textarea class="form-control" name="TyonkuvausRivit[kommenti][0]" /></textarea>
		        </td>
		    </tr>
		<?php endif; ?>
		</table>



		<div class="input-group" id="kuvauksetTuoteesta" style="display:none">
		 <?php echo CHtml::dropDownList("", "", $tal, array("multiple" => "multiple", "class"=> "form-control mult tyonkuvaus_tilat_selecter")); ?>
		 <span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="tyonkuvaus_tilat"><i class="fa fa-pencil-square-o"></i></span>
		 </span>
		</div>

		<div class="" id="vkoPvm" style="display:none">
			<?php echo CHtml::dropDownList('', '', $pvm_arr, 
				array('multiple' => 'multiple', 'class'=> 'selectpicker vkoPvm_selecter')
			); ?>
		</div>

		<div id="laatutasotAlasveto" style="display:none">
			<?php echo CHtml::dropDownList('', '', $laatutasot, 
				array('multiple' => 'multiple', 'class'=> 'selectpickerTasot laatutasot_selecter')
			); ?>
		</div>

<script>
jQuery(function(){
    var counter = parseInt($('.authors-list tr:last').attr('num'))+1;
    var kuvauksetTuoteesta = $('#kuvauksetTuoteesta').html();
    var vkoPvm = $('#vkoPvm').html();
    var laatutasotAlasveto = $('#laatutasotAlasveto').html();

    $('a.add-author').click(function(event){
        event.preventDefault();

        var newRow = jQuery('<tr class="rivi" num="'+ counter +'">' +
	    '<td><i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i></td>' +
	    '<td><div class="input-group">'+ kuvauksetTuoteesta +'</div>' +
		'<textarea class="form-control tilat" rows="4" name="TyonkuvausRivit[tilat][' + counter + ']"/></textarea>' +
	    '</td>' +
	    '<td class="tyotehtavatVkoPvmTD">' +

		        '<a href="#" title="" class="add-author-tyotehtavat" num="'+ counter +'"><i class="fa fa-plus" aria-hidden="true"></i></a>' +
			'<table class="table authors-list-tyotehtavat">' +
			    '<tr>' +
			        '<td></td><td><?php echo Yii::t('main','Työtehtävä'); ?></td><td><?php echo Yii::t('main','Vko. päivämäärät'); ?></td>' +
			    '</tr>' +
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
			    '</tr>' +
			'</table>' +
	    '</td>' +
	    '<td>' +
		    	'<div class="laatutasot_tilat">' +
				laatutasotAlasveto +
				'<textarea class="form-control" rows="4" name="TyonkuvausRivit[laatutaso][' + counter + ']"/></textarea>' +
			'</div>' +
	    '</td>' +
	    '<td><textarea class="form-control" name="TyonkuvausRivit[kommenti][' + counter + ']"/></textarea></td>' +
	    '</tr>');
            counter++;
        jQuery('table.authors-list').append(newRow);


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

	$('.selectpickerTasot').selectpicker({
	  //style: 'btn-info',
	  title: 'Laatutasot',
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

    $(document).delegate(".add-author-tyotehtavat","click",function(event){

	var trLength = $(this).closest('.tyotehtavatVkoPvmTD').find('table.authors-list-tyotehtavat tr.rivi-tyotehtavat').length;

	var counter = parseInt(trLength);
	var num = $(this).attr('num');

        event.preventDefault();

        var newRow = jQuery('<tr class="rivi-tyotehtavat" num="'+ num +'">' +
	    '<td><i class="link fa fa-trash poistaAuthorRiviT2" aria-hidden="true"></i></td>' +
	    '<td><input type="text" class="form-control" name="TyonkuvausRivit[tyotehtava][' + num + ']['+ counter +']"/></td>' +
	    '<td><div class="vkopvm_tilat">'+ vkoPvm +'<input type="hidden" class="form-control" name="TyonkuvausRivit[vkopvm][' + num + ']['+ counter +']"/></div></td>' +
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
	<?php endif; ?>



	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
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

 $('.selectpickerTasot').selectpicker({
	  //style: 'btn-info',
	  title: 'Laatutasot',
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

 $(document).delegate(".laatutasot_selecter","change",function(){
	var thisVal = $(this).val().join('\n');
    	$(this).closest('div.laatutasot_tilat').find('textarea').val(thisVal);
 });



});
</script>

