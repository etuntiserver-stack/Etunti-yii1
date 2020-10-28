<?php
	$tuotePalvelu = '';
	$tuote = TuotteetPalvelut::model()->findbypk($_POST['tuotePalvelu']);
	if(isset($tuote->id))
	{
		$tuoteID	= $tuote->id;
		$tuotePalvelu 	= $tuote->nimike;
	} else {
		$tuoteID	= '';
		$tuotePalvelu 	= '';
	}

	$hinnasto_rivi_id = '';
	$hinnaston_otsikko = Yii::t('main', 'Hinnastoa ei määritetty');
	$rivi_lisays = !empty($rivi_lisays) ? " $rivi_lisays" : "";

	if( isset($_POST['hinnasto_rivi_id']) and $_POST['hinnasto_rivi_id'] > 0 ){
	   $hr = HinnastotRivi::model()->findByPk($_POST['hinnasto_rivi_id']);
	}
	if(isset($hr->id))
	{
		$hinnasto_rivi_id = $hr->id;
		$hn = Hinnastot::model()->findByPk($hr->hinnastot_id);
		if(isset($hn->id)) {
			$hinnaston_otsikko = '<span class="btn btn-default fa fa-money" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Hinnasto: '). ' ' .$hn->hinnaston_otsikko.'"></span>';
		}
	}
?>

     <TR class="kaikkiTR" id="trRivi_<?php echo $num; ?>">
	<TD><b class="link text-danger poista" for="poista_<?php echo $num; ?>" style="font-size:150%"><i class="fa fa-times"></i></b></TD>
	<TD>
	<input type="hidden" size="1" name="tuoteID[<?php echo $num; ?>]" id="tuoteID_<?php echo $num; ?>" class="form-control" value="<?php echo $tuoteID; ?>">
	<input type="hidden" size="1" name="hinnasto_rivi_id[<?php echo $num; ?>]" id="hinnasto_rivi_id_<?php echo $num; ?>" class="form-control" value="<?php echo $hinnasto_rivi_id; ?>">

	<?php if( isset($_POST['tuotteet_palvelut_muoto']) and $_POST['tuotteet_palvelut_muoto'] == 0 ): ?>
	<input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control" value="<?php echo $tuotePalvelu . $rivi_lisays; ?>">
	<?php endif; ?>

	<?php if( isset($_POST['tuotteet_palvelut_muoto']) and $_POST['tuotteet_palvelut_muoto'] == 1 ): ?>
	<div class="row">
	  <div class="col-lg-4">
		<?php
		$criteria = new CDbCriteria();
       		$criteria->condition = " hinta_alv_0!=0 ";
		echo CHtml::dropdownList('','palvelu', CHtml::listData(TuotteetPalvelut::model()->findAll($criteria), 'id', 'nimike'), 
		array('empty'=>'','class'=>'form-control valitseTuote_tuoteonly','id'=>'lt_'.$num,'num'=>$num));
		?>
	  </div><div class="col-lg-8">
	  <input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control">
	  </div>
	</div>
	<?php endif; ?>

	</TD>
	<TD><input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $kpl; ?>"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control">
		<option value="<?php echo $yksikko; ?>"><?php echo $yksikko; ?></option>
		<?php echo $this->yksikkot(null); ?>
		</select>
	</TD>
	<TD style="width:10%">
	  <div class="input-group">
	   <input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $hinta; ?>" step="any"> 
	   <span class="input-group-btn">
		<?=$hinnaston_otsikko?>
	   </span>
	  </div>
	  <span class="errmsg"></span>
	</TD>
	<TD>
		<select type="text" name="alv[<?php echo $num; ?>]" id="alv_<?php echo $num; ?>" class="form-control">
		<option value="<?php echo $alv; ?>"><?php echo $alv; ?></option>
		<?php echo $this->alv(null); ?>
		</select>
	</TD>
	<TD><input class="yhteensa_total_verot form-control" size="10" type="text" name="hinta_alv[<?php echo $num; ?>]" id="hinta_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="10" name="ale[<?php echo $num; ?>]" id="ale_<?php echo $num; ?>" value="0" class="onlyDigits form-control"><span class="errmsg"></span></TD>
	<TD><input class="yhteensa_total_veroton form-control" type="text" size="10" name="veroton[<?php echo $num; ?>]" id="veroton_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input class="yhteensa_total form-control" type="text" size="10" name="yhteensa_alv[<?php echo $num; ?>]" id="yhteensa_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="5" name="free_text[<?php echo $num; ?>]" id="free_text_<?php echo $num; ?>" class="form-control" value="<?php echo $free_text; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $free_text; ?>"></TD>
     </TR>
