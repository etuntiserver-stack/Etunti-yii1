<?php
	if(isset($_POST['num'])){
		$num = $_POST['num'];
	}

	$tv_id 			= 0;
	$tuoteID 		= '';
	$tuote 			= '';
	$hinta 			= 0;
	$maara 			= 0;
	$yksikkot 		= $this->yksikkot(null);
	$alv 			= $this->alv(24);
	$free_text 		= '';
	$pikkuviesti 	= '';

	if(isset($_POST['digisten_tunnit_id'])){
		$digisten_tunnit_id = $_POST['digisten_tunnit_id'];
		$model = DigistenTunnitKk::model()->findByPk($digisten_tunnit_id);
		if(isset($model->id))
		{
			$DigistenTunnitKk = Yii::app()->createController('DigistenTunnitKk');
			$hinta = $DigistenTunnitKk[0]->tuntihinta_laskin($model->id);
			$maara = $model->tunnit;
			$yksikkot = '<option value="h">h</option>';
			$tuote = 'Etunti (tunnit) '.$model->month.'.'.$model->year;
		}

	}

	if(isset($la_asiakkaat_kk))
	{
		$tuote			= $la_asiakkaat_kk['tuote'];
		$maara			= 1;
		$hinta 			= $la_asiakkaat_kk['hinta'];
		$free_text 		= '';
		$yksikkot 		= $this->yksikkot('kk');
		$alv 			= $this->alv($la_asiakkaat_kk['alv']);
		$free_text 		= $la_asiakkaat_kk['free_text'];
	}
	
	if(isset($la_asiakkaat_mobiili))
	{
		if(isset($la_asiakkaat_mobiili['pikkuviesti']))
			$pikkuviesti 	= $la_asiakkaat_mobiili['pikkuviesti'];
		
		$tuote			= $la_asiakkaat_mobiili['tuote'];
		$tuoteID		= $la_asiakkaat_mobiili['tuoteID'];
		$tv_id 			= $la_asiakkaat_mobiili['tv_id'];
		$maara 			= $la_asiakkaat_mobiili['maara'];
		$hinta 			= $la_asiakkaat_mobiili['hinta'];
		$yksikkot 		= $this->yksikkot('h');
		$alv 			= $this->alv($la_asiakkaat_mobiili['alv']);
		$free_text 		= $la_asiakkaat_mobiili['free_text'];
	}
?>
<?php if(!empty($pikkuviesti)): ?>
<tr>
	<td colspan="7"></td>
	<td colspan="5"><b class="text-danger">Viesti mobiilista: </b> <?=$pikkuviesti?></td>
</tr>
<?php endif; ?>
<TR class="kaikkiTR" id="trRivi_<?php echo $num; ?>">
	<TD><span class="link text-danger poista" for="poista_<?php echo $num; ?>" style="font-size:150%"><i class="fa fa-times"></i></span></TD>
	<TD>

	<input type="hidden" size="1" name="tuoteID[<?php echo $num; ?>]" id="tuoteID_<?php echo $num; ?>" class="form-control" value="<?php echo $tuoteID; ?>">
	<input type="hidden" size="1" name="tv_id[<?php echo $num; ?>]" id="tv_id_<?php echo $num; ?>" class="form-control" value="<?php echo $tv_id; ?>">
	<input type="hidden" size="1" name="hinnasto_rivi_id[<?php echo $num; ?>]" id="hinnasto_rivi_id_<?php echo $num; ?>" class="form-control">
	<div class="row">
	  <div class="col-lg-4">
		<?php
		$criteria = new CDbCriteria();
		$criteria->order = " nimike ";
		$criteria->condition = " 
			hinta_alv_0!=0 AND nayta_vain_onlinevarauksessa=0
		";
		echo CHtml::dropdownList('','palvelu', CHtml::listData(TuotteetPalvelut::model()->findAll($criteria), 'id', 'nimike'), 
			['empty'=>'','class'=>'form-control valitseTuote', 'id'=>'lt_'.$num,'num'=>$num, 'options' => [$tuoteID => ['selected'=>true]] ]
		);
		?>
	  </div><div class="col-lg-8">
	      <input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control form-group" value="<?=$tuote?>">
	  </div>
	</div>

	</TD>

	<TD><input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control" value="<?=$maara?>"><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control">
		<?php echo $yksikkot; ?>
		</select>
	</TD>
	<TD style="width:10%">
	  <div class="input-group">
	   <input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control" value="<?php echo $hinta; ?>" step="any"> 
	   <span class="input-group-btn">
		<span class="btn btn-default fa fa-money hinnaston_otsikko" data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('main', 'Hinnastoa ei määritetty')?>"></span>
	   </span>
	  </div>
	  <span class="errmsg"></span>
	</TD>
	<TD>
		<select type="text" name="alv[<?php echo $num; ?>]" id="alv_<?php echo $num; ?>" class="form-control">
		<?php echo $alv; ?>
		</select>
	</TD>
	<TD><input class="yhteensa_total_verot form-control" size="10" type="text" name="hinta_alv[<?php echo $num; ?>]" id="hinta_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="10" name="ale[<?php echo $num; ?>]" id="ale_<?php echo $num; ?>" value="0" class="onlyDigits form-control"><span class="errmsg"></span></TD>
	<TD><input class="yhteensa_total_veroton form-control" type="text" size="10" name="veroton[<?php echo $num; ?>]" id="veroton_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input class="yhteensa_total form-control" type="text" size="10" name="yhteensa_alv[<?php echo $num; ?>]" id="yhteensa_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="5" name="free_text[<?php echo $num; ?>]" id="free_text_<?php echo $num; ?>" class="form-control" value="<?=$free_text?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $free_text; ?>"></TD>
</TR>
