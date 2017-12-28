<?php

?>

<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>
	<td>
		<?php echo date("d.m.Y", strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $data->voimassa; ?>
	</td>
	<td>
		<?php echo $data->kohteen_osoite; ?>
	</td>
	<td>
		<?php if($data->tyonkuvaus_id != 0): ?>
		<?php echo CHtml::link('<i class="fa fa-file-pdf-o btn btn-primary myBgColors" aria-hidden="true"></i>', 
				array('tyonkuvaus/pdf', 'id'=>$data->tyonkuvaus_id, 'open_status' => 'openPDF'), 
				array(
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'PDF'),
					'target' => '_blank'
				)
			); 
		?>
		<?php endif; ?>
	</td>
	<td>
	<div class="row">
	<div class="form-inline">
	<?php
		if(file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku().'/'.$data->liite.'.docx' ))
		{
		// <-- file_safe_opener
		$ext = 'docx';
		$filepath = Yii::app()->baseUrl.$this->valmiit_polkku().'/'.$data->liite.'.'.$ext;
		echo CHtml::link('<i class="fa fa-file-word-o btn btn-primary myBgColors" style="margin-right: 5px"></i>',
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
			array('target'=>'_blank', 'class' => 'form-group'
		));
		//     file_safe_opener -->
		}

		if(file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku().'/'.$data->liite.'.pdf' ))
		{
		// <-- file_safe_opener
		$ext = 'pdf';
		$filepath = Yii::app()->baseUrl.$this->valmiit_polkku().'/'.$data->liite.'.'.$ext;
		echo CHtml::link('<i class="fa fa-file-pdf-o btn btn-primary myBgColors"></i>',
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
			array('target'=>'_blank', 'class' => 'form-group'
		));
		//     file_safe_opener -->
		}
	?>
	</div>
	</div>
	</td>
	<td>
		<?php if(isset($data->tarjoukset->id) and $data->tarjoukset->id == $data->tarjous_id) : ?>
		<?php echo CHtml::link('<span class="btn btn-success btn-block">'.Yii::t('main', 'Katso tarjous').'</span>',Yii::app()->request->baseUrl.'/index.php/crmTarjoukset/update?id='.$data->tarjoukset->id); ?>
		<?php else : ?>
		<?php echo Yii::t('main', 'Ei tarjousta'); ?>
		<?php endif; ?>
	</td>
	<td>
		<?php 
			if(!empty($data->asiakkaan_sahkoposti) and $data->status == 0)
			{
				echo '<button class="btn btn-primary btn-block laheta" for="'.$data->id.'">'.Yii::t('main', 'Lähetä').'</button>';
			} elseif($data->status == 1){
				echo '<button class="btn btn-warning btn-block laheta" for="'.$data->id.'">'.Yii::t('main', 'Lähetetty').'</button>';
			} elseif($data->status == 2){
				echo '<button class="btn btn-success btn-block">'.Yii::t('main', 'Hyväksytty').'</button>';
			} elseif($data->status == 3){
				echo '<button class="btn btn-danger btn-block">'.Yii::t('main', 'Hylätty').'</button>';
			}
		?>
	</td>
</tr>


