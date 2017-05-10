<?php
/* @var $this DigistenTunnitKkController */
/* @var $data DigistenTunnitKk */
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
		<?php if(isset($data->domainit->yritys)) echo $data->domainit->yritys; ?>
	</td>
	<td>
		<?php echo $data->year.' / '.$data->month; ?>
	</td>
	<td>
		<?php echo $data->tunnit; ?>
	</td>
	<td>
		<?php 
			echo number_format($this->tuntihinta_laskin($data->id), 2, ',', ' ').' &euro;';
		?>
	</td>
	<td>
		<?php 
			$dh = DigistenHinnasto::model()->findByPk(1);
			$adm = Administrators::model()->findAll();
			$jarj_valv_kpl = 0;
			$jarj_valv = 0;
			if( isset($dh->id) and count($adm) > 2 )
			{
				echo (count($adm)).' kpl (2 ilmaista)<br>'
				.$dh->jarjestelmanvalvoja*(count($adm)-2).' &euro;';
				$jarj_valv += $dh->jarjestelmanvalvoja*(count($adm)-2);
				$jarj_valv_kpl = count($adm);
			}
		?>
	</td>
	<td>
		<?php 
			$sum = ($this->tuntihinta_laskin($data->id)*$data->tunnit)+$jarj_valv;
			echo number_format($sum, 2, ',', ' ').' &euro;';
		?>
	</td>
	<td>
		<?php if($data->laskutettu == 0 and $data->lasku_id == 0) : ?>
		<?php echo CHtml::link(Yii::t('main', 'Luo lasku'), 
				array('//lasku/create', 'digisten_tunnit_id'=>$data->id, 'jv' => $jarj_valv_kpl), 
				array(
					'class'=>'btn btn-warning', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Luo lasku') 
				)
			); 
		?>
		<?php else : ?>
		<?php echo CHtml::link(Yii::t('main', 'Laskutettu'), 
				array('//lasku/update', 'id'=>$data->lasku_id), 
				array(
					'class'=>'btn btn-success', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Luo lasku') 
				)
			); 
		?>
		<?php endif; ?>
	</td>
</tr>

