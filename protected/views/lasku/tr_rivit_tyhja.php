<?php
if(isset($_POST['num'])){
	$num = $_POST['num'];
}

?>

     <TR class="kaikkiTR" id="trRivi_<?php echo $num; ?>">
	<TD><span class="btn btn-sm btn-danger poista" for="poista_<?php echo $num; ?>">X</span></TD>
	<TD>
		<?php
		echo CHtml::dropdownList('','palvelu', CHtml::listData(LaskutusTuotteet::model()->findAll(), 'id', 'tuotenimi'), array('empty'=>'Valitse tuote/palvelu','class'=>'form-control input-sm','id'=>'lt'));
		?>
<input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control input-sm" value="">
	</TD>

	<TD><input type="text" name="nimike[<?php echo $num; ?>]" id="nimike_<?php echo $num; ?>" class="form-control input-sm" value=""></TD>
	<TD><input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control input-sm" value=""><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control input-sm">
		<?php echo $this->yksikkot(null); ?>
		</select>
	</TD>
	<TD><input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control input-sm" value=""><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="alv[<?php echo $num; ?>]" id="alv_<?php echo $num; ?>" class="form-control input-sm">
		<?php echo $this->alv(null); ?>
		</select>
	</TD>
	<TD><input class="yhteensa_total_verot form-control input-sm" size="10" type="text" name="hinta_alv[<?php echo $num; ?>]" id="hinta_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="10" name="ale[<?php echo $num; ?>]" id="ale_<?php echo $num; ?>" value="0" class="onlyDigits form-control input-sm"><span class="errmsg"></span></TD>
	<TD><input class="yhteensa_total_veroton form-control input-sm" type="text" size="10" name="veroton[<?php echo $num; ?>]" id="veroton_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input class="yhteensa_total form-control input-sm" type="text" size="10" name="yhteensa_alv[<?php echo $num; ?>]" id="yhteensa_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
     </TR>
