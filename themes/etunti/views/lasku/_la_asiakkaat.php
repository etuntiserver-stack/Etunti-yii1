<?php

?>
<tr>
	<td width="15%" style="vertical-align: top">
		<?php echo $data->Fullname.' #'.$data->id; ?>
	</td>
	<td width="75%" class="closest_td">
		<div class="form-inline">
			<select class="form-group form-control rakenne_muoto">
				<option value="mobiili">Mobiilista</option>
				<option value="mobiili">Työvuoroista</option>
			</select>
			<select class="form-group form-control rivi_muoto">
				<option value="rivi_per_kohde">Tuote/Kohde mukaan</option>
				<option value="rivi_per_kirjaus">Rivi per kirjaus</option>
			</select>
			<button class="btn btn-primary nayta_collapse" type="button" data-toggle="collapse" data-target="#collapse_id_<?=$data->id?>" aria-expanded="false" aria-controls="collapseExample" asiakas_id="<?=$data->id?>">
				<?=Yii::t('main', 'Rakenna uusi lasku')?> <i class="caret"></i>
			</button>
			<button class="btn btn-info paivita pull-right" style="display:none"><?=Yii::t('main', 'Päivitä / Keskeytä')?></button>
		</div>
		<div class="collapse" id="collapse_id_<?=$data->id?>"></div>
	</td>

<?php /*
	<td width="20%">
		<?php
			echo '<form action="create?l_asiakkaat=true&asiakasnumero='.$data->asiakasnumero.'" method="POST" target="_blank">';
			echo '<textarea style="display:none" name="la_asiakkaat_mobiili">'.json_encode($mob_tv_arr).'</textarea>';
			echo '<textarea style="display:none" name="la_asiakkaat_kk">'.json_encode($kk_arr).'</textarea>';
			echo '<select class="form-control" name="with_mobile">';
			echo '<option value="1">Mobiili + Kuukausi</option>';
			echo '<option value="2">Vain mobiili</option>';
			echo '<option value="3">Vain kuukausi</option>';
			echo '</select>';
			echo '<input type="submit" class="btn btn-warning btn-block" value="Luo lasku">';
			echo '</form>';
		?>
	</td>
*/ ?>
</tr>
