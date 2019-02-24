<?php

?>
<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update_autolahetteet', 'id'=>$data->id), 
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
	<td><?=date("d.m.Y H:i", strtotime($data->time))?></td>
	<td>
	<?php
	if(isset($data->asiakkaat->id) and $data->asiakkaat->tyyppi == 'yritys'){
		echo $data->asiakkaat->yrityksen_nimi;
	}
	if(isset($data->asiakkaat->id) and $data->asiakkaat->tyyppi == 'henkilo'){
		echo $data->asiakkaat->yhteyshenkilo;
	}
	?>
	</td>

	<td><b><?=date("d.m.Y", strtotime($data->from_date))?> - <?=date("d.m.Y", strtotime($data->to_date))?></b></td>
	<td><?=($data->laskutettu == 1)? 'Laskutettu':'Ei laskutettu' ?></td>
</tr>
