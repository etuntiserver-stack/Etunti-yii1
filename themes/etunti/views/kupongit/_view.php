<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

	$asiakas_lista='<span class="text-success">Avoin</span>';
	if(is_array(json_decode($data->lahetetyt_asiakas_id_lista, true)))
	{
		$asiakas_lista = '';
		$asiakas_id_lista = json_decode($data->lahetetyt_asiakas_id_lista, true);

		$i = 0;
		foreach($asiakas_id_lista as $item)
		{
		    $i++;
		    $a = Asiakkaat::model()->findbypk($item);
		    if(isset($a->id))
		    {

			   $asiakas = $a->Fullname;

			$asiakas_lista .= CHtml::link($i.'. '.$asiakas, 
				array('/asiakkaat/update', 'id'=>$a->id), 
				array(
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Katso') 
				)
			).'<br>';
		    }
		}
	}

	$list = array('0'=>Yii::t('main', 'Ei'),'1'=>Yii::t('main', 'Kyllä'));

	$kaytetty = '';
	if($data->status == 1 and $data->jatkuva == 0)
	$kaytetty = '<br><b class="text-danger">Tämä koodi on käytetty</b>';
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
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
	</td>
	<td>
		<h3><?php echo $data->kupongin_id; ?></h3>
	</td>
	<td>
		<?php if( !empty($data->voimassa) and strtotime($data->voimassa) <= time() ) : ?>
			<span class="text-danger"><?=date("d.m.Y", strtotime($data->voimassa))?></span>
		<?php else : ?>
			<?=date("d.m.Y", strtotime($data->voimassa))?>
		<?php endif; ?>
	</td>
	<td>
		<?php 
			if($data->maara_tyyppi == 'euro')
			echo Yii::t('main', 'Euro') .' <b>'.$data->euro_maara.'&euro;</b>';
			if($data->maara_tyyppi == 'prosentti')
			echo Yii::t('main', 'Prosentti') .' <b>'.$data->prosentti_maara.'%</b>';
		?>
	</td>
	<td>
		<?=$list[$data->jatkuva]?>
	</td>
<?php /*
	<td>
		<?=$list[$data->status]?>
	</td>
*/ ?>
	<td>
		<?php echo $asiakas_lista; ?>
		<?=$kaytetty?>
	</td>
	<td>
		<?php if( strtotime($data->voimassa) <= time() ) : ?>

		<?php else : ?>
		<?php echo CHtml::link('<i class="fa fa-paper-plane-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('laheta', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä') 
				)
			); 
		?>
		<?php endif; ?>
	</td>
</tr>

