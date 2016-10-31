<?php
/* @var $this TyontekijatController */
/* @var $data Tyontekijat */

	$criteria = new CDbCriteria();
        $criteria->condition = "  tid='".$data->id."' ";
	$ts = Tyosuhdet::model()->find($criteria);

		$tuloraja_ajalle = '';
		$perusprosentti = '';
		$lisaprosentti = '';
		$kuukaudessa = '';
		$kahdessa_viikossa = '';
		$viikossa = '';
		$paivassa = '';
		$atk_varten = '';
		$yksi_tuloraja = '';
	if(isset($ts->id))
	{
		$tuloraja_ajalle = $ts->tuloraja_ajalle;
		$perusprosentti = $ts->perusprosentti;
		$lisaprosentti = $ts->lisaprosentti;
		$kuukaudessa = $ts->kuukaudessa;
		$kahdessa_viikossa = $ts->kahdessa_viikossa;
		$viikossa = $ts->viikossa;
		$paivassa = $ts->paivassa;
		$atk_varten = $ts->atk_varten;
		$yksi_tuloraja = $ts->yksi_tuloraja;
	}
?>


<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */
?>

<tr>
	<td class="col1">
		<?php echo $data->tekijan_nimi; ?>
	</td>
	<td class="col2">
	<input type="text" class="form-control valRiviSuhteet" for="tuloraja_ajalle" tid="<?php echo $data->id; ?>" value="<?php echo $tuloraja_ajalle; ?>">
	</td>
	<td class="col2">
	<input type="text" class="form-control valRiviSuhteet" for="perusprosentti" tid="<?php echo $data->id; ?>" value="<?php echo $perusprosentti; ?>">
	</td>
	<td class="col2">
	<input type="text" class="form-control valRiviSuhteet" for="lisaprosentti" tid="<?php echo $data->id; ?>" value="<?php echo $lisaprosentti; ?>">
	</td>
	<td class="col2">
	<input type="text" class="form-control valRiviSuhteet" for="kuukaudessa" tid="<?php echo $data->id; ?>" value="<?php echo $kuukaudessa; ?>">
	</td>
	<td class="col2">
	<input type="text" class="form-control valRiviSuhteet" for="kahdessa_viikossa" tid="<?php echo $data->id; ?>" value="<?php echo $kahdessa_viikossa; ?>">
	</td>
	<td class="col2">
	<input type="text" class="form-control valRiviSuhteet" for="viikossa" tid="<?php echo $data->id; ?>" value="<?php echo $viikossa; ?>">
	</td>
	<td class="col2">
	<input type="text" class="form-control valRiviSuhteet" for="paivassa" tid="<?php echo $data->id; ?>" value="<?php echo $paivassa; ?>">
	</td>
	<td class="col2">
	<input type="text" class="form-control valRiviSuhteet" for="atk_varten" tid="<?php echo $data->id; ?>" value="<?php echo $atk_varten; ?>">
	</td>
	<td class="col2">
	<input type="text" class="form-control valRiviSuhteet" for="yksi_tuloraja" tid="<?php echo $data->id; ?>" value="<?php echo $yksi_tuloraja; ?>">
	</td>
</tr>
