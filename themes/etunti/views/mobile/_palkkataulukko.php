<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$korv 	= '';
		$lisatt	= '';
		$ennakko= '';
		$iltatunnit_tyotjamatkat = '';
		$sum 	= 0;

		if($sutunnit != 0)
			$sutunnit = $this->num($sutunnit);

		if($su_matkat != 0)
			$su_matkat = $this->num($su_matkat);
			
		if($iltatunnit != 0){
			$iltatunnit_tyotjamatkat = '<b>Työt</b>:<br>'.number_format($this->num($iltatunnit), 2, ',', '');
	        } else {
			$iltatunnit = '';
	  	}

		if($matkaIlta != 0){
			$matkaIlta = '<br><b>Matkat</b>:<br>'.number_format($this->num($matkaIlta), 2, ',', '');
	        } else {
			$matkaIlta = '';
		}


  $lisatt = $this->renderPartial('//lisatyotunnit/tidfromto',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$data->id
		),true);

  $korv = $this->renderPartial('//korvaukset/tidfromto',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$data->id
		),true);

  $ennakko = $this->renderPartial('//ennakko/tidfromto',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$data->id
		),true);


		$sum = $this->num($matkatunnit+$tyotunnit);

?>

<tr>

	<td class="tulostus_tekija col1"><?=$data->$tt_order_1.' '.$data->$tt_order_2?> <?= $data->using_framework_agreement ? "(PUITESOPIMUS)" : ""?></td>
	<td class="col2"><?php if($tp != 0) echo $tp; ?></td>
	<td class="col3"><?php if($this->num($matkatunnit) != 0) echo number_format($this->num($matkatunnit), 2, ',', ''); ?></td>
	<td class="col4"><?php if($tyotunnit != 0) echo number_format($this->num($tyotunnit), 2, ',', ''); ?></td>
	<td class="col5"><?php echo number_format($sum, 2, ',', ''); ?></td>
	<td class="col6"><?php echo $iltatunnit_tyotjamatkat.$matkaIlta; ?></td>
	<td class="col7"><?php echo number_format($this->num($iltatunnit_ja_iltamatka), 2, ',', ''); ?></td>
	<td class="col8"><?php echo number_format($this->num($loun), 2, ',', ''); ?></td>
	<td class="col8"><?=number_format($this->num($yotunnit), 2, ',', '')?></td>
	<td class="col9"><?php if($sutunnit != 0) echo number_format($sutunnit, 2, ',', ''); ?></td>
	<td class="col9"><?php if($su_matkat != 0) echo number_format($su_matkat, 2, ',', ''); ?></td>
	<td class="col10"><?php echo number_format($this->num($pyhat), 2, ',', ''); ?></td>
	<td class="col10"><?php echo number_format($this->num($el), 2, ',', ''); ?></td>
	<td class="col10"><?php if($sl > 0) echo number_format($sl, 2, ',', ''); ?></td>
	<td class="col11"><?php if($spl > 0) echo number_format($spl, 2, ',', ''); ?></td>
	<td class="col12"><?php if($ls > 0) echo number_format($ls, 2, ',', ''); ?></td>
	<td class="col12"><?php if($vl > 0) echo number_format($vl, 2, ',', ''); ?></td>
	<td class="col12"><?php if($vkl > 0) echo number_format($vkl, 2, ',', ''); ?></td>
	<td class="col13"><?php if($pv > 0) echo  number_format($pv, 2, ",", ""); ?>
	<td class="col14"><?php if($lsk > 0) echo number_format($lsk, 2, ",", ""); ?>
	<td class="col15"><?php if($pp > 0) echo number_format($pp, 2, ",", ""); ?>
	<td class="col16"><?php echo $korv; ?></td>
	<td class="col17"><?php echo $lisatt; ?></td>
	<td class="col18"><?php echo $ennakko; ?></td>
</tr>

	


