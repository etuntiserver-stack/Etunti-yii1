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
	?>

	<?php
	$tyonkuvaus_tilat = '
	   <div class="input-group" id="kuvauksetTuoteesta">
		'.CHtml::dropDownList('', '', $tal, 
			array('multiple' => 'multiple', 'class'=> 'form-control mult tyonkuvaus_tilat_selecter')
		).'
		<span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="tyonkuvaus_tilat"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>';
	?>

	<?php
	$vko_pvm = '
	   <div class="" id="vkoPvm">
		'.CHtml::dropDownList('', '', $pvm_arr, 
			array('multiple' => 'multiple', 'class'=> 'selectpicker vkoPvm_selecter')
		).'
	   </div>';
	?>


	      <a href="#" title="" class="add-author"><i class="fa fa-plus" aria-hidden="true"></i></a>

		<table class="table table-bordered authors-list">
		    <tr>
		        <td></td><td><?php echo Yii::t('main','Tilat'); ?></td>
			<td><?php echo Yii::t('main','Työtehtävät'); ?></td>
			<td><?php echo Yii::t('main','Laatutaso'); ?></td>
			<td><?php echo Yii::t('main','Kommenti'); ?></td>
		    </tr>
		<?php if(isset($rivit)) : ?>
		<?php
		$tb = json_decode($model->muut_kulut, true);
		ksort($tb['otsikko']);
		foreach($tb['otsikko'] as $key=>$items)
		{
		echo '
		';

		}
		?>
		<?php else : ?>
		    <tr class="rivi" num="0">
		        <td>
		            <i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i>
		        </td>
		        <td>
			    <div class="tyonkuvaus_tilat"><?php echo $tyonkuvaus_tilat; ?></div>
		            <textarea class="form-control tilat" name="TyonkuvausRivit[tilat][0]" /></textarea>
		        </td>
		        <td>


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
				    <div class="vkopvm_tilat"><?php echo $vko_pvm; ?>
			            	<input type="hidden" class="form-control" type="text" name="TyonkuvausRivit[vkopvm][0][0]" />
				    </div>
			        </td>
			    </tr>
			</table>


		        </td>
		        <td>
		            <textarea class="form-control" name="TyonkuvausRivit[laatutaso][0]" /></textarea>
		        </td>
		        <td>
		            <textarea class="form-control" name="TyonkuvausRivit[kommenti][0]" /></textarea>
		        </td>
		    </tr>
		<?php endif; ?>
		</table>




<script>
jQuery(function(){
    var counter = parseInt($('.authors-list tr:last').attr('num'))+1;
    var kuvauksetTuoteesta = $('#kuvauksetTuoteesta').html();
    var vkoPvm = $('#vkoPvm').html();

    $('a.add-author').click(function(event){
        event.preventDefault();

        var newRow = jQuery('<tr class="rivi" num="'+ counter +'">' +
	    '<td><i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i></td><td><div class="input-group">'+ kuvauksetTuoteesta +'</div><textarea class="form-control tilat" name="TyonkuvausRivit[tilat][' + counter + ']"/></textarea></td>' +
	    '<td>' +

		        '<a href="#" title="" class="add-author-tyotehtavat" num="0"><i class="fa fa-plus" aria-hidden="true"></i></a>' +
			'<table class="table authors-list-tyotehtavat">' +
			    '<tr>' +
			        '<td></td><td><?php echo Yii::t('main','Työtehtävä'); ?></td><td><?php echo Yii::t('main','Vko. päivämäärät'); ?></td>' +
			    '</tr>' +
			    '<tr class="rivi-tyotehtavat" num="0">' +
			        '<td>' +
			            '<i class="link fa fa-trash poistaAuthorRiviT2" aria-hidden="true"></i>' +
			        '</td>' +
			        '<td>' +
			            '<input class="form-control" type="text" name="TyonkuvausRivit[tyotehtava][0][0]" />' +
			        '</td>' +
			        '<td>' +
				    '<div class="vkopvm_tilat">' + vkoPvm +
			            	'<input type="hidden" class="form-control" type="text" name="TyonkuvausRivit[vkopvm][0][0]" />' +
				    '</div>' +
			        '</td>' +
			    '</tr>' +
			'</table>' +
	    '</td>' +
	    '<td><textarea class="form-control" name="TyonkuvausRivit[laatutaso][' + counter + ']"/></textarea></td>' +
	    '<td><textarea class="form-control" name="TyonkuvausRivit[kommenti][' + counter + ']"/></textarea></td>' +
	    '</tr>');
            counter++;
        jQuery('table.authors-list').append(newRow);


 	$(".mult").multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
	}); 

	$('.selectpicker').selectpicker({
	  //style: 'btn-info',
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
    var counter = parseInt($('.authors-list-tyotehtavat tr:last').attr('num'))+1;
    var vkoPvm = $('#vkoPvm').html();

    $('a.add-author-tyotehtavat').click(function(event){
	var num = $(this).attr('num');

        event.preventDefault();

        var newRow = jQuery('<tr class="rivi-tyotehtavat" num="'+ counter +'">' +
	    '<td><i class="link fa fa-trash poistaAuthorRiviT2" aria-hidden="true"></i></td>' +
	    '<td><input type="text" class="form-control" name="TyonkuvausRivit[tyotehtava][' + num + ']['+ counter +']"/></td>' +
	    '<td><div class="vkopvm_tilat">'+ vkoPvm +'<input type="hidden" class="form-control" name="TyonkuvausRivit[vkopvm][' + num + ']['+ counter +']"/></div></td>' +
	    '</tr>');
            counter++;
        jQuery('table.authors-list-tyotehtavat').append(newRow);


	$('.selectpicker').selectpicker({
	  //style: 'btn-info',
	  size: 4
	});


    });
    $(document).delegate(".poistaAuthorRiviT2","click",function(){
	$(this).closest('tr.rivi-tyotehtavat').remove();
    });
});
</script>

	</div>




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
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
 }); 

 $('.selectpicker').selectpicker({
	  //style: 'btn-info',
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



});
</script>

