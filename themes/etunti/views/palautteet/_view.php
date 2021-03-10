<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

		$as = Asiakkaat::model()->findbypk($data->asiakas_id);
			
		$nimi = '';
		if(isset($as->id))
		$nimi = $as->Fullname;


		$criteria=new CDbCriteria;
		$criteria->order = " DATE(time) DESC ";
		$criteria->condition = "
			asiakas_id='".$data->asiakas_id."' 
			AND keskustelu_id='".$data->keskustelu_id."'
			AND keskustelu_id!=id
		";
		$p_juttelu = Palautteet::model()->findAll($criteria);
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
	<td style="width: 20%">
		<?php echo $data->viimeinen_tyo; ?>
	</td>
	<td>
		<?php if(file_exists( Yii::app()->basePath.'/../lib/img/emoji/'.$data->emoji_tila.'.png' )) : ?>
		<p><img src="<?php echo '../../lib/img/emoji/'.$data->emoji_tila.'.png'; ?>" height="100"></p>
		<?php endif; ?>
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $nimi; ?>
	</td>
	<td>
		<?php $cat = Valikkoot::model()->findbyPk($data->kategoria); ?>
		<?=(isset($cat->id))? $cat->value: ''?>
	</td>

<?php
	  	$bod = '';
	  	$bod .= '<td>';
		$bod .= '<p>'.$data->teksti.'</p>';
		   foreach($p_juttelu as $data2)
		   {
			$bod .= '<p>'.$data2->teksti.'</p>';
		   }


		if($data->status == 0)
		{

			$bod .= '<form action="#" class="palautteet-form-vastaus" method="POST">'; 
			$bod .= '<input type="hidden" name="palaute_id" value="'.$data->id.'" class="form-control">';
			$bod .= '<input type="hidden" name="PalautteetVastaus[keskustelu_id]" value="'.$data->keskustelu_id.'">';
			$bod .= '<input type="hidden" name="PalautteetVastaus[lahettaja]" value="admin">';
			$bod .= '<textarea name="PalautteetVastaus[teksti]" rows=4 class="form-control"></textarea>';
			$bod .= '<input type="checkbox" name="sisainen"> '.Yii::t('main', 'Sisäinen vastaus').'<br>';
			$bod .= CHtml::submitButton('Lähetä vastaus',array('class'=>'btn btn-primary myBgColors'));
			$bod .= '</form>'; 
		}

	  	$bod .= '</td>';

	  	$bod .= '<td>';
		if($data->status == 0)
	  	$bod .= '<span class="btn btn-sm btn-warning btn-block">'.Yii::t('main', 'Avoinna').'</span>';
		elseif($data->status == 3)
	  	$bod .= '<span class="btn btn-sm btn-success btn-block">'.Yii::t('main', 'Suljettu').'</span>';

		if(isset(Yii::app()->user->adminID) and $data->status != 3)
		{
		$bod .= CHtml::link('Sulje', '#', array(
		'submit'=>array('index', "suljeJuttelu"=>$data->keskustelu_id, "id"=>$data->asiakas_id), 
		'class'=>'btn btn-danger btn-sm btn-block', 'style'=>'color:white'
		));
		}

	  	$bod .= '</td>';
		echo $bod;
?>


</tr>


