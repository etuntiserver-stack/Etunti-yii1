<?php
$tunnit_id 	= '';
$hinnaston_otsikko = '';
$hr = HinnastotRivi::model()->findByPk($rivi['hinnasto_rivi_id']);
if(isset($hr->id))
{
	$hn = Hinnastot::model()->findByPk($hr->hinnastot_id);
	if(isset($hn->id)) {
		$hinnaston_otsikko = '<span class="btn btn-default fa fa-money" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Hinnasto: '). ' ' .$hn->hinnaston_otsikko.'"></span>';
	}
}

$this_hinnoittelu = '';
foreach($kids as $kohde_id)
	if(isset($kohden_hinnoittelut[$kohde_id]['hinnoittelu']) and !empty($kohden_hinnoittelut[$kohde_id]['hinnoittelu']))
		$this_hinnoittelu .= 'Hinnoittelu: '. $kohden_hinnoittelut[$kohde_id]['osoite'].', '.$kohden_hinnoittelut[$kohde_id]['hinnoittelu'];

?>

<TR class="text-center kaikkiTR" id="trRivi_<?php echo $num; ?>">
<td>
	<?php
	if(!empty($rivi['kk_hyv_lista']))
	{
		echo '<span class="link fa fa-list text-primary" data-toggle="collapse" data-target="#collapse_id_'.$num.'" title="Hyväksytyt tunnit"></span>';
		echo '
		<div style="position:relative">
			<div style="position:absolute;left:0;z-index:999999;" class="collapse well" id="collapse_id_'.$num.'">
				<h3>Hyväksytyt tunnit</h3>
				<table class="table table-bordered" >';
				$yht = 0;
				foreach(json_decode($rivi['kk_hyv_lista'], true) as $item)
				{
					$yht += $item['kesto'];
					echo '<tr>
						<td style="white-space:nowrap">'.$item['kohde_kannasta'].'</td>
						<td>'.$item['pvm'].'</td>
						<td style="white-space:nowrap">'.$item['tekijan_nimi'].'</td>
						<td>'.$item['aloitan'].'</td>
						<td>'.$item['loppui'].'</td>
						<td>'.$item['kesto'].'</td>
					</tr>';
				}
				echo '<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>'.$yht.'</td>
				</tr>';
				echo '</table></div></div>';
	}
	if($rivi['mobile_id'] == 0)
		echo '<b class="link text-danger poista"><i class="fa fa-times"></i></b> ';
	if(!empty($this_hinnoittelu))
		echo '<span class="fa fa-info-circle text-primary" data-toggle="tooltip" title="'.$this_hinnoittelu.'"></span>';
	?>
</td>
<TD>
	<input type="hidden" size="1" name="tuoteID[<?php echo $num; ?>]" id="tuoteID_<?php echo $num; ?>" value="<?php echo $rivi['tuoteID']; ?>">
	<input type="hidden" size="1" name="hinnasto_rivi_id[<?php echo $num; ?>]" id="hinnasto_rivi_id_<?php echo $num; ?>" value="<?php echo $rivi['hinnasto_rivi_id']; ?>">
	<textarea name="tiedot[<?php echo $num; ?>]" id="tiedot_<?php echo $num; ?>" style="display:none"><?php echo $rivi['tiedot']; ?></textarea>
	<textarea name="rivi_tunniste[<?php echo $num; ?>]" id="rivi_tunniste_<?php echo $num; ?>" style="display:none"><?php echo $rivi['rivi_tunniste']; ?></textarea>
	<textarea name="kohde_ids[<?php echo $num; ?>]" id="kohde_ids_<?php echo $num; ?>" style="display:none"><?php echo $rivi['kohde_ids']; ?></textarea>
	<input type="hidden" size="1" name="tunnit_id[<?php echo $num; ?>]" id="tunnit_id_<?php echo $num; ?>" class="form-control" value="<?php echo $rivi['mobile_id']; ?>">
	<input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control" value="<?php echo $rivi['tkoodi']; ?>">
</TD>

<TD>
	<input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $rivi['kpl']; ?>"><span class="errmsg"></span>
</TD>
<TD>
	<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control">
	<option value="<?php echo $rivi['yksikko']; ?>"><?php echo $rivi['yksikko']; ?></option>
	<?php echo $this->yksikkot(null); ?>
	</select>
</TD>
<TD  style="width:10%">
	<div class="input-group">
	<input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $rivi['hinta']; ?>" step="any"> 
	<span class="input-group-btn">
	<?=$hinnaston_otsikko?>
	</span>
	</div>
	<span class="errmsg"></span>
</TD>
<TD>
	<select type="text" name="alv[<?php echo $num; ?>]" id="alv_<?php echo $num; ?>" class="form-control">
	<option value="<?php echo $rivi['alv']; ?>"><?php echo $rivi['alv']; ?></option>
	<?php echo $this->alv(null); ?>
	</select>
</TD>
<TD><input class="yhteensa_total_verot form-control" size="10" type="text" name="hinta_alv[<?php echo $num; ?>]" id="hinta_alv_<?php echo $num; ?>" value="<?php echo $rivi['hinta_alv']; ?>" readonly></TD>
<TD><input type="text" size="10" name="ale[<?php echo $num; ?>]" id="ale_<?php echo $num; ?>" value="<?php echo $rivi['ale']; ?>" class="onlyDigits form-control"><span class="errmsg"></span></TD>
<TD><input class="yhteensa_total_veroton form-control" type="text" size="10" name="veroton[<?php echo $num; ?>]" id="veroton_<?php echo $num; ?>" value="<?php echo $rivi['veroton']; ?>" readonly></TD>
<TD><input class="yhteensa_total form-control" type="text" size="10" name="yhteensa_alv[<?php echo $num; ?>]" id="yhteensa_alv_<?php echo $num; ?>" value="<?php echo $rivi['yhteensa_alv']; ?>" readonly></TD>
<TD><input type="text" size="5" name="free_text[<?php echo $num; ?>]" id="free_text_<?php echo $num; ?>" class="form-control" value="<?php echo $rivi['free_text']; ?>"></TD>
</TR>
