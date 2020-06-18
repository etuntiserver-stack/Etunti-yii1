<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$korv 	= '';
		$lisatt	= '';
		$ennakko= '';
		$iltatunnit_tyotjamatkat = '';
		$sum 	= 0;

		if($sutunnit != 0){
			$sutunnit = $this->num($sutunnit);
		}

		if($iltatunnit != 0){
			$iltatunnit_tyotjamatkat = '<b>Työt</b>:<br>'.$this->num($iltatunnit);
	        } else {
			$iltatunnit = '';
	  	}

		if($matkaIlta != 0){
			$matkaIlta = '<br><b>Matkat</b>:<br>'.$this->num($matkaIlta);
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

	<td class="tulostus_tekija col1" style="mso-number-format:\@;"><?=$data->$tt_order_1.' '.$data->$tt_order_2?></td>
	<td class="col2" style="mso-number-format:\@;"><?php if($tp != 0) echo $tp; ?></td>
	<td class="col3" style="mso-number-format:\@;"><?php if($this->num($matkatunnit) != 0) echo $this->num($matkatunnit); ?></td>
	<td class="col4" style="mso-number-format:\@;"><?php if($tyotunnit != 0) echo $this->num($tyotunnit); ?></td>
	<td class="col5" style="mso-number-format:\@;"><?php echo $sum; ?></td>
	<td class="col6" style="mso-number-format:\@;"><?php echo $iltatunnit_tyotjamatkat.$matkaIlta; ?></td>
	<td class="col7" style="mso-number-format:\@;"><?php echo $this->num($iltatunnit_ja_iltamatka); ?></td>
	<td class="col8" style="mso-number-format:\@;"><?php echo $this->num($loun); ?></td>
	<td class="col8" style="mso-number-format:\@;"><?=$this->num($yotunnit)?></td>
	<td class="col9" style="mso-number-format:\@;"><?php if($sutunnit != 0) echo $sutunnit; ?></td>
	<td class="col10" style="mso-number-format:\@;"><?php echo $this->num($pyhat); ?></td>
	<td class="col10" style="mso-number-format:\@;"><?php echo $this->num($el); ?></td>
	<td class="col10" style="mso-number-format:\@;"><?php if($sl > 0) echo $sl; ?></td>
	<td class="col11" style="mso-number-format:\@;"><?php if($spl > 0) echo $spl; ?></td>
	<td class="col12" style="mso-number-format:\@;"><?php if($ls > 0) echo $ls; ?></td>
	<td class="col12" style="mso-number-format:\@;"><?php if($vl > 0) echo $vl; ?></td>
	<td class="col12" style="mso-number-format:\@;"><?php if($vkl > 0) echo $vkl; ?></td>
	<td class="col13" style="mso-number-format:\@;"><?php echo $korv; ?></td>
	<td class="col14" style="mso-number-format:\@;"><?php echo $lisatt; ?></td>
	<td class="col15" style="mso-number-format:\@;"><?php echo $ennakko; ?></td>
</tr>

	


