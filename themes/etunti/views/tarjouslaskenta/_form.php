<?php
/* @var $this TarjouslaskentaController */
/* @var $model Tarjouslaskenta */
/* @var $form CActiveForm */


		$mail = new YiiMailer();
		//$mail->clearLayout();//if layout is already set in config
		$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		$mail->setTo('laptopsr@gmail.com');
		$mail->setSubject('test subj');
		$mail->setBody('testi viesti');
		$mail->send();
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tarjouslaskenta-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class="row">
  <div class="col-sm-4">


	<?php echo $form->errorSummary($model); ?>


	<div class="section fill mb5 yhteystiedot">
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
			array('empty'=>'Valitse','class'=>'form-control valittu'));
		
        	?>
		<?php echo $form->error($model,'yhteystiedot_id'); ?>
	</div>

	<div class="section fill mb5 asiakas">
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
			array('empty'=>'Valitse','class'=>'form-control valittu'));
		
        	?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tuote_palvelu_id'); ?>
		<?php         		
			echo $form->dropDownList($model, 'tuote_palvelu_id', CHtml::listData(LaskutusTuotteet::model()->findAll(), 'id', 'tuotenimi'),
			array('empty'=>'Valitse','class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'tuote_palvelu_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_tyyppi'); ?>
		<?php
		$list = array('kk'=>Yii::t('main', 'kk'), 'tunti'=>Yii::t('main', 'tunti'));

        		echo $form->dropDownList($model, 'hinta_tyyppi', $list,
			array('class'=>'form-control'));
		
        	?>
		<?php echo $form->error($model,'hinta_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'neliot'); ?>
		<?php echo $form->numberField($model,'neliot', array('class'=>'form-control', "step" => "any")); ?>
		<?php echo $form->error($model,'neliot'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kayntikerrat'); ?>
		<?php echo $form->numberField($model,'kayntikerrat', array('class'=>'form-control', "step" => "any")); ?>
		<?php echo $form->error($model,'kayntikerrat'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tuntien_maara'); ?>
		<?php echo $form->numberField($model,'tuntien_maara', array('class'=>'form-control', "step" => "any")); ?>
		<?php echo $form->error($model,'tuntien_maara'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yhteensa'); ?>
		<?php echo $form->numberField($model,'yhteensa', array('class'=>'form-control', "step" => "any")); ?>
		<?php echo $form->error($model,'yhteensa'); ?>
	</div>

  </div><div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tavoite_myyntikate'); ?>
		<?php echo $form->numberField($model,'tavoite_myyntikate', array('class'=>'form-control', "step" => "any")); ?>
		<?php echo $form->error($model,'tavoite_myyntikate'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palkkakustannus'); ?>
		<?php echo $form->numberField($model,'palkkakustannus', array('class'=>'form-control', "step" => "any")); ?>
		<?php echo $form->error($model,'palkkakustannus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'matkat'); ?>
		<?php echo $form->numberField($model,'matkat', array('class'=>'form-control', "step" => "any")); ?>
		<?php echo $form->error($model,'matkat'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'iltalisa'); ?>
		<?php echo $form->numberField($model,'iltalisa', array('class'=>'form-control', "step" => "any")); ?>
		<?php echo $form->error($model,'iltalisa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yolisa'); ?>
		<?php echo $form->numberField($model,'yolisa', array('class'=>'form-control', "step" => "any")); ?>
		<?php echo $form->error($model,'yolisa'); ?>
	</div>

  </div><div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'muut_kulut'); ?>
		<?php
			$asetukset = Asetukset::model()->findByPk(1);
		?>

		<table class="table authors-list">
		    <tr>
		        <td></td><td><?php echo Yii::t('main','Otsikko'); ?></td><td><?php echo Yii::t('main','Hinta'); ?></td>
		    </tr>
		<?php if(is_array(json_decode($model->muut_kulut, true))) : ?>
		<?php
		$tb = json_decode($model->muut_kulut, true);
		ksort($tb['otsikko']);
		foreach($tb['otsikko'] as $key=>$items)
		{
		echo '
		    <tr class="rivi" num="'.$key.'">
		        <td>
		            <i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i>
		        </td>
		        <td>
		            <input class="form-control" type="text" name="Tarjouslaskenta[muut_kulut][otsikko]['.$key.']" value="'.$items.'" />
		        </td>
		        <td>
		            <input class="form-control" type="text" name="Tarjouslaskenta[muut_kulut][hinta]['.$key.']" value="'.$tb['hinta'][$key].'" />
		        </td>
		    </tr>';

		}
		?>
		<?php elseif((!isset($model->id) or empty($model->muut_kulut)) and is_array(json_decode($asetukset->edico_muut_kulut, true))) : ?>
		<?php
		$tb = json_decode($asetukset->edico_muut_kulut, true);
		ksort($tb['otsikko']);
		foreach($tb['otsikko'] as $key=>$items)
		{
		echo '
		    <tr class="rivi" num="'.$key.'">
		        <td>
		            <i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i>
		        </td>
		        <td>
		            <input class="form-control" type="text" name="Tarjouslaskenta[muut_kulut][otsikko]['.$key.']" value="'.$items.'" />
		        </td>
		        <td>
		            <input class="form-control" type="text" name="Tarjouslaskenta[muut_kulut][hinta]['.$key.']" value="'.$tb['hinta'][$key].'" />
		        </td>
		    </tr>';

		}
		?>
		<?php else : ?>
		    <tr class="rivi" num="0">
		        <td>
		            <i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i>
		        </td>
		        <td>
		            <input class="form-control" type="text" name="Tarjouslaskenta[muut_kulut][otsikko][0]" />
		        </td>
		        <td>
		            <input class="form-control" type="text" name="Tarjouslaskenta[muut_kulut][hinta][0]" />
		        </td>
		    </tr>
		<?php endif; ?>
		</table>


		<a href="#" title="" class="add-author"><i class="fa fa-plus" aria-hidden="true"></i></a>

<script>
jQuery(function(){
    var counter = parseInt($('.authors-list tr:last').attr('num'))+1;
    $('a.add-author').click(function(event){
        event.preventDefault();

        var newRow = jQuery('<tr class="rivi" num="'+ counter +'"><td><i class="link fa fa-trash poistaAuthorRivi" aria-hidden="true"></i></td><td><input type="text" class="form-control" name="Tarjouslaskenta[muut_kulut][otsikko][' +
            counter + ']"/></td><td><input type="text" class="form-control" name="Tarjouslaskenta[muut_kulut][hinta][' +
            counter + ']"/></td></tr>');
            counter++;
        jQuery('table.authors-list').append(newRow);

    });
    $(document).delegate(".poistaAuthorRivi","click",function(){
	$(this).closest('tr.rivi').remove();
    });
});
</script>

		<?php echo $form->error($model,'muut_kulut'); ?>
	</div>

 </div>
</div>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>





<script>
jQuery(function(){

    $(".valittu").change(function(){
	var valittu = $(this).attr('id');
	if(valittu == 'Tarjouslaskenta_yhteystiedot_id')
		$('.asiakas').hide();
	if(valittu == 'Tarjouslaskenta_asiakas_id')
		$('.yhteystiedot').hide();
    });
});
</script>
