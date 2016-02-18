<?php
	$k = Kohteet::model()->findbypk($kohde);
	if(isset($k->asiakas_id))
	{
	$a = Asiakkaat::model()->findbypk($k->asiakas_id);
	} else {
	echo 'asiakas_id puutuu';
	exit;
	}

	$osoite = '';
	if($onkokohde == 'onkohde')
	$osoite = $k->osoite;
	if($onkokohde == 'eikohde'){
	$a = Asiakkaat::model()->findbypk($kohde);
	$osoite = $a->osoite;
	}

	$tuotePalvelu = '';
	if(isset($_POST['tuotePalvelu']) and !empty($_POST['tuotePalvelu']))
	$tuotePalvelu = $_POST['tuotePalvelu'].', ';

	$osoite = $tuotePalvelu.$osoite;
?>

     <TR class="kaikkiTR" id="trRivi_<?php echo $num; ?>">
	<TD><b class="link text-danger poista" for="poista_<?php echo $num; ?>" style="font-size:150%"><i class="fa fa-times"></i></b></TD>
	<TD><input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control" value="<?php echo $osoite.' '.date('d.m',strtotime($_POST['from'])).'-'.date('d.m',strtotime($_POST['to'])); ?>"></TD>
	<TD><input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $kpl; ?>"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control">
		<option value="<?php echo $yksikko; ?>"><?php echo $yksikko; ?></option>
		<?php echo $this->yksikkot(null); ?>
		</select>
	</TD>
	<TD><input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $hinta; ?>"><span class="errmsg"></span></TD>
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
     </TR>
