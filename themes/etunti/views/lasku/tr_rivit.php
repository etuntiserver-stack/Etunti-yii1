<?php
	$k = Kohteet::model()->findbypk($kohde);
	if(isset($k->asiakas_id))
	{
	$a = Asiakkaat::model()->findbypk($k->asiakas_id);
	} else {
	echo 'asiakas_id puutuu';
	exit;
	}

	// <-- Free text
	$free_text = '';
	if($onkokohde == 'onkohde')
	$free_text = $k->osoite;
	if($onkokohde == 'eikohde'){
	$a = Asiakkaat::model()->findbypk($kohde);
	$free_text = $a->osoite;
	}
	$free_text = $free_text.', '.date('d.m.Y',strtotime($_POST['from'])).'-'.date('d.m.Y',strtotime($_POST['to']));
	//     Free text -->

	$tuotePalvelu = '';
	$tuote = LaskutusTuotteet::model()->findbypk($_POST['tuotePalvelu']);
	if(isset($tuote->id))
	{
		$tuoteID	= $tuote->id;
		$tuotePalvelu 	= $tuote->tuotenimi;
	} else {
		$tuoteID	= '';
		$tuotePalvelu 	= '';
	}


?>

     <TR class="kaikkiTR" id="trRivi_<?php echo $num; ?>">
	<TD><b class="link text-danger poista" for="poista_<?php echo $num; ?>" style="font-size:150%"><i class="fa fa-times"></i></b></TD>
	<TD>
		<input type="hidden" size="1" name="tuoteID[<?php echo $num; ?>]" id="tuoteID_<?php echo $num; ?>" class="form-control" value="<?php echo $tuoteID; ?>">
		<input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control" value="<?php echo $tuotePalvelu; ?>">
	</TD>
	<TD><input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $kpl; ?>"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control">
		<option value="<?php echo $yksikko; ?>"><?php echo $yksikko; ?></option>
		<?php echo $this->yksikkot(null); ?>
		</select>
	</TD>
	<TD><input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $hinta; ?>" step="0.01"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="alv[<?php echo $num; ?>]" id="alv_<?php echo $num; ?>" class="form-control">
		<option value="<?php echo $a->alv; ?>"><?php echo $a->alv; ?></option>
		<?php echo $this->alv(null); ?>
		</select>
	</TD>
	<TD><input class="yhteensa_total_verot form-control" size="10" type="text" name="hinta_alv[<?php echo $num; ?>]" id="hinta_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="10" name="ale[<?php echo $num; ?>]" id="ale_<?php echo $num; ?>" value="0" class="onlyDigits form-control"><span class="errmsg"></span></TD>
	<TD><input class="yhteensa_total_veroton form-control" type="text" size="10" name="veroton[<?php echo $num; ?>]" id="veroton_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input class="yhteensa_total form-control" type="text" size="10" name="yhteensa_alv[<?php echo $num; ?>]" id="yhteensa_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="5" name="free_text[<?php echo $num; ?>]" id="free_text_<?php echo $num; ?>" class="form-control" value="<?php echo $free_text; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $free_text; ?>"></TD>
     </TR>
