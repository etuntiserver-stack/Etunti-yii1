<?php
/* @var $this KohteetController */
/* @var $data Kohteet */
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
		<?php echo $this->etuSukunimi($data->tid); ?>
	</td>
	<td>
		<?php echo $data->teksti; ?>
	</td>
	<td>
	<?php
		if(file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku().'/'.$data->tiedosto.'.docx' ))
		{
		// <-- file_safe_opener
		$ext = 'docx';
		$filepath = Yii::app()->baseUrl.$this->valmiit_polkku().'/'.$data->tiedosto.'.'.$ext;
		echo CHtml::link($data->tiedosto.'.'.$ext,
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
		}

		echo '<br>';
		if(file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku().'/'.$data->tiedosto.'.pdf' ))
		{
		// <-- file_safe_opener
		$ext = 'pdf';
		$filepath = Yii::app()->baseUrl.$this->valmiit_polkku().'/'.$data->tiedosto.'.'.$ext;
		echo CHtml::link($data->tiedosto.'.'.$ext,
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
		}
	?>
	</td>
</tr>


