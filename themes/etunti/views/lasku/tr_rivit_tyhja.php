<?php
if(isset($_POST['num'])){
	$num = $_POST['num'];
}

?>

     <TR class="kaikkiTR" id="trRivi_<?php echo $num; ?>">
	<TD><span class="link text-danger poista" for="poista_<?php echo $num; ?>" style="font-size:150%"><i class="fa fa-times"></i></span></TD>
	<TD>

	<input type="hidden" size="1" name="tuoteID[<?php echo $num; ?>]" id="tuoteID_<?php echo $num; ?>" class="form-control">

	<input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control" value="" data-toggle="collapse"  data-target="#lt_<?php echo $num; ?>">
	<?php
		$criteria = new CDbCriteria();
       		$criteria->condition = " is_active=1 ";
		echo CHtml::dropdownList('','palvelu', CHtml::listData(LaskutusTuotteet::model()->findAll($criteria), 'id', 'tuotenimi'), 
		array('empty'=>'Valitse tuote/palvelu','class'=>'form-control collapse valitseTuote input-sm','id'=>'lt_'.$num,'num'=>$num));
	?>
	</TD>

	<TD><input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control" value=""><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control">
		<?php echo $this->yksikkot(null); ?>
		</select>
	</TD>
	<TD><input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control" value="" step="0.01"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="alv[<?php echo $num; ?>]" id="alv_<?php echo $num; ?>" class="form-control">
		<?php echo $this->alv(null); ?>
		</select>
	</TD>
	<TD><input class="yhteensa_total_verot form-control" size="10" type="text" name="hinta_alv[<?php echo $num; ?>]" id="hinta_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="10" name="ale[<?php echo $num; ?>]" id="ale_<?php echo $num; ?>" value="0" class="onlyDigits form-control"><span class="errmsg"></span></TD>
	<TD><input class="yhteensa_total_veroton form-control" type="text" size="10" name="veroton[<?php echo $num; ?>]" id="veroton_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input class="yhteensa_total form-control" type="text" size="10" name="yhteensa_alv[<?php echo $num; ?>]" id="yhteensa_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="5" name="free_text[<?php echo $num; ?>]" id="free_text_<?php echo $num; ?>" class="form-control"></TD>
     </TR>
