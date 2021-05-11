<?php

?>
<tr>
	<td width="15%" style="vertical-align: top" class="closest_asiakas_td">
		<?php
		echo $data->Fullname.' #'.$data->id;

		if(isset($la_AsIds['la_'.$kk.'_'.$data->id]))
		{
			echo '<h4 class="text-info">Tehdyt laskut. '.count($la_AsIds['la_'.$kk.'_'.$data->id]).'kpl</h3>';
		}
		?>
	</td>
	<td width="75%" class="closest_td">
		<div class="form-inline">
			<input type="text" class="form-control datepickerFI laskun_paivays" value="<?=date("d.m.Y")?>" data-toggle="tooltip" title="<?=Yii::t('main', 'Laskun päiväys')?>">
			<select class="form-group form-control rivi_muoto">
				<option value="rivi_per_kirjaus">KIRJAUS</option>
				<option value="rivi_per_kohde">TUOTE</option>
			</select>
			<button class="btn btn-primary nayta_collapse" type="button" data-toggle="collapse" data-target="#collapse_id_<?=$data->id?>" aria-expanded="false" aria-controls="collapseExample" asiakas_id="<?=$data->id?>">
				<?=Yii::t('main', 'Luo uudet laskut')?> <i class="caret"></i>
			</button>
			<button class="btn btn-info paivita pull-right" style="display:none"><?=Yii::t('main', 'Päivitä / Keskeytä')?></button>
		</div>
		<div class="collapse" id="collapse_id_<?=$data->id?>"></div>
	</td>
</tr>
