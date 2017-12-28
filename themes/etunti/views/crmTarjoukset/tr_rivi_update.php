<?php
	$lasku = Yii::app()->createController('Lasku');
?>

     <tr class="kaikkitr" id="trRivi_<?php echo $num; ?>">
	<?php if(!isset($_POST['CrmTarjoukset']) and !isset($_POST['CrmSopimukset'])) : ?>
	<td><b class="link text-danger poista" for="poista_<?php echo $num; ?>" style="font-size:150%"><i class="fa fa-times"></i></b></td>
	<?php endif; ?>

	<td>
		<input type="hidden" size="1" name="tuoteID[<?php echo $num; ?>]" id="tuoteID_<?php echo $num; ?>" value="<?php echo $rivi['tuoteID']; ?>">
		<?php if(isset($_POST['CrmTarjoukset']) or isset($_POST['CrmSopimukset'])) : ?>
		<?=$rivi['tkoodi']?>
		<?php else : ?>
		<input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control" value="<?php echo $rivi['tkoodi']; ?>">
		<?php endif; ?>
	</td>

	<td>
		<?php if(isset($_POST['CrmTarjoukset']) or isset($_POST['CrmSopimukset'])) : ?>
		<?=$rivi['kpl']?>
		<?php else : ?>
		<input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $rivi['kpl']; ?>">
		<span class="errmsg"></span>
		<?php endif; ?>
	</td>
	<td>
		<?php if(isset($_POST['CrmTarjoukset']) or isset($_POST['CrmSopimukset'])) : ?>
		<?=$rivi['yksikko']?>
		<?php else : ?>
		<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control">
		<option value="<?php echo $rivi['yksikko']; ?>"><?php echo $rivi['yksikko']; ?></option>
		<?php echo $lasku[0]->yksikkot(null); ?>
		</select>
		<?php endif; ?>
	</td>
	<td>
		<?php if(isset($_POST['CrmTarjoukset']) or isset($_POST['CrmSopimukset'])) : ?>
		<?=$rivi['hinta']?>
		<?php else : ?>
		<input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $rivi['hinta']; ?>" step="0.01">
		<span class="errmsg"></span>
		<?php endif; ?>
	</td>
	<td>
		<?php if(isset($_POST['CrmTarjoukset']) or isset($_POST['CrmSopimukset'])) : ?>
		<?=$rivi['alv']?>
		<?php else : ?>
		<select type="text" name="alv[<?php echo $num; ?>]" id="alv_<?php echo $num; ?>" class="form-control">
		<option value="<?php echo $rivi['alv']; ?>"><?php echo $rivi['alv']; ?></option>
		<?php echo $lasku[0]->alv(null); ?>
		</select>
		<?php endif; ?>
	</td>
	<td>
		<?php if(isset($_POST['CrmTarjoukset']) or isset($_POST['CrmSopimukset'])) : ?>
		<?=$rivi['hinta_alv']?>
		<?php else : ?>
		<input class="yhteensa_total_verot form-control" size="10" type="text" name="hinta_alv[<?php echo $num; ?>]" id="hinta_alv_<?php echo $num; ?>" value="<?php echo $rivi['hinta_alv']; ?>" readonly>
		<?php endif; ?>
	</td>
	<td>
		<?php if(isset($_POST['CrmTarjoukset']) or isset($_POST['CrmSopimukset'])) : ?>
		0
		<?php else : ?>
		<input type="text" size="10" name="ale[<?php echo $num; ?>]" id="ale_<?php echo $num; ?>" value="<?php echo $rivi['ale']; ?>" class="onlyDigits form-control">
		<span class="errmsg"></span>
		<?php endif; ?>
	</td>
	<td>
		<?php if(isset($_POST['CrmTarjoukset']) or isset($_POST['CrmSopimukset'])) : ?>
		<?=$rivi['veroton']?>
		<?php else : ?>
		<input class="yhteensa_total_veroton form-control" type="text" size="10" name="veroton[<?php echo $num; ?>]" id="veroton_<?php echo $num; ?>" value="<?php echo $rivi['veroton']; ?>" readonly>
		<?php endif; ?>
	</td>
	<td>
		<?php if(isset($_POST['CrmTarjoukset']) or isset($_POST['CrmSopimukset'])) : ?>
		<?=$rivi['yhteensa_alv']?>
		<?php else : ?>
		<input class="yhteensa_total form-control" type="text" size="10" name="yhteensa_alv[<?php echo $num; ?>]" id="yhteensa_alv_<?php echo $num; ?>" value="<?php echo $rivi['yhteensa_alv']; ?>" readonly>
		<?php endif; ?>
	</td>
     </tr>
