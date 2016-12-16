<?php

?>

     <TR class="kaikkiTR" id="trRivi_<?php echo $num; ?>">
	<TD><b class="link text-danger poista" for="poista_<?php echo $num; ?>" style="font-size:150%"><i class="fa fa-times"></i></b></TD>
	<TD><input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control" value="<?php echo $rivi['tkoodi']; ?>"></TD>

	<TD><input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $rivi['kpl']; ?>"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control">
		<option value="<?php echo $rivi['yksikko']; ?>"><?php echo $rivi['yksikko']; ?></option>
		<?php echo $this->yksikkot(null); ?>
		</select>
	</TD>
	<TD><input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $rivi['hinta']; ?>" step="0.01"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="alv[<?php echo $num; ?>]" id="alv_<?php echo $num; ?>" class="form-control">
		<option value="<?php echo $rivi['alv']; ?>"><?php echo $rivi['alv']; ?></option>
		<?php echo $this->alv(null); ?>
		</select>
	</TD>
	<TD><input class="yhteensa_total_verot form-control" size="10" type="text" name="hinta_alv[<?php echo $num; ?>]" id="hinta_alv_<?php echo $num; ?>" value="<?php echo $rivi['hinta_alv']; ?>" readonly></TD>
	<TD><input type="text" size="10" name="ale[<?php echo $num; ?>]" id="ale_<?php echo $num; ?>" value="0" class="onlyDigits form-control"><span class="errmsg"></span></TD>
	<TD><input class="yhteensa_total_veroton form-control" type="text" size="10" name="veroton[<?php echo $num; ?>]" id="veroton_<?php echo $num; ?>" value="<?php echo $rivi['veroton']; ?>" readonly></TD>
	<TD><input class="yhteensa_total form-control" type="text" size="10" name="yhteensa_alv[<?php echo $num; ?>]" id="yhteensa_alv_<?php echo $num; ?>" value="<?php echo $rivi['yhteensa_alv']; ?>" readonly></TD>
	<TD><input type="text" size="5" name="free_text[<?php echo $num; ?>]" id="free_text_<?php echo $num; ?>" class="form-control" value="<?php echo $rivi['free_text']; ?>"></TD>
     </TR>
