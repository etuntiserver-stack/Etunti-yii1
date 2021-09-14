<?php

?>
<tr>
	<td width="15%" style="vertical-align: top" class="closest_asiakas_td">
		<?php
		echo $data->Fullname;

		if(isset($la_AsIds['la_'.$kk.'_'.$data->id]))
		{
			echo '<br><br><span class="tehdyt" asiakas_id="'.$data->id.'">Tehdyt laskut: ('.count($la_AsIds['la_'.$kk.'_'.$data->id]).') kpl</span>';
		}
		?>
	</td>
	<td width="75%" class="closest_td">
		<div class="form-inline">
		  <div class="form-group">
			<label>Päivämäärä</label><br>
			<input type="text" class="form-control datepickerFI laskun_paivays" value="<?=date("d.m.Y")?>" data-toggle="tooltip" title="<?=Yii::t('main', 'Laskun päiväys')?>">
		  </div>
		  <div class="form-group">
			<label>Laskurivit</label><br>
			<select class="form-group form-control rivi_muoto">
				<option value="rivi_per_kirjaus">Kirjaukset rivittäin</option>
				<option value="rivi_per_kohde">TUOTE</option>
			</select>
		  </div>
		  <div class="form-group">
		  	<br>
			<button class="btn btn-primary nayta_collapse" type="button" data-toggle="collapse" data-target="#collapse_id_<?=$data->id?>" aria-expanded="false" aria-controls="collapseExample" asiakas_id="<?=$data->id?>">
				Näytä laskurivit <i class="caret"></i>
			</button>
		  </div>
		  <button class="btn btn-info paivita pull-right" style="display:none"><i class="fa fa-refresh"></i></button>
		</div>
		<div class="collapse" id="collapse_id_<?=$data->id?>"></div>
	</td>
</tr>
