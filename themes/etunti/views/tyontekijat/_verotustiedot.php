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
	<?php if($tulostus): ?>
		<?php echo $tuloraja_ajalle; ?>
	<?php else : ?>
		<input type="text" class="form-control valRiviSuhteet" for="tuloraja_ajalle" tid="<?php echo $data->id; ?>" value="<?php echo $tuloraja_ajalle; ?>">
	<?php endif; ?>
	</td>
	<td class="col2">
	<?php if($tulostus): ?>
		<?php echo $perusprosentti; ?>
	<?php else : ?>
		<input type="text" class="form-control valRiviSuhteet" for="perusprosentti" tid="<?php echo $data->id; ?>" value="<?php echo $perusprosentti; ?>">
	<?php endif; ?>
	</td>
	<td class="col2">
	<?php if($tulostus): ?>
		<?php echo $lisaprosentti; ?>
	<?php else : ?>
		<input type="text" class="form-control valRiviSuhteet" for="lisaprosentti" tid="<?php echo $data->id; ?>" value="<?php echo $lisaprosentti; ?>">
	<?php endif; ?>
	</td>
	<td class="col2">
	<?php if($tulostus): ?>
		<?php echo $kuukaudessa; ?>
	<?php else : ?>
		<input type="text" class="form-control valRiviSuhteet" for="kuukaudessa" tid="<?php echo $data->id; ?>" value="<?php echo $kuukaudessa; ?>">
	<?php endif; ?>
	</td>
	<td class="col2">
	<?php if($tulostus): ?>
		<?php echo $kahdessa_viikossa; ?>
	<?php else : ?>
		<input type="text" class="form-control valRiviSuhteet" for="kahdessa_viikossa" tid="<?php echo $data->id; ?>" value="<?php echo $kahdessa_viikossa; ?>">
	<?php endif; ?>
	</td>
	<td class="col2">
	<?php if($tulostus): ?>
		<?php echo $viikossa; ?>
	<?php else : ?>
		<input type="text" class="form-control valRiviSuhteet" for="viikossa" tid="<?php echo $data->id; ?>" value="<?php echo $viikossa; ?>">
	<?php endif; ?>
	</td>
	<td class="col2">
	<?php if($tulostus): ?>
		<?php echo $paivassa; ?>
	<?php else : ?>
		<input type="text" class="form-control valRiviSuhteet" for="paivassa" tid="<?php echo $data->id; ?>" value="<?php echo $paivassa; ?>">
	<?php endif; ?>
	</td>
	<td class="col2">
	<?php if($tulostus): ?>
		<?php echo $atk_varten; ?>
	<?php else : ?>
		<input type="text" class="form-control valRiviSuhteet" for="atk_varten" tid="<?php echo $data->id; ?>" value="<?php echo $atk_varten; ?>">
	<?php endif; ?>
	</td>
	<td class="col2">
	<?php if($tulostus): ?>
		<?php echo $yksi_tuloraja; ?>
	<?php else : ?>
		<input type="text" class="form-control valRiviSuhteet" for="yksi_tuloraja" tid="<?php echo $data->id; ?>" value="<?php echo $yksi_tuloraja; ?>">
	<?php endif; ?>
	</td>
</tr>
