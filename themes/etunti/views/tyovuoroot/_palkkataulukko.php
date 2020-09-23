<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$sum 	= 0;
		$sumI 	= '';
		$korv 	= '';
		$lisatt	= '';
		$ennakko= '';
		$iltatunnit_tyotjamatkat = 0;

		if($iltatunnit != 0)
		$iltatunnit_tyotjamatkat = '<b>Työt</b>:<br>'.number_format($this->num($iltatunnit-$matkaIlta), 2, ',', '');
	        else
		$iltatunnit = '';

		if($sutunnit != 0)
		$sutunnit = $this->num($sutunnit);

		if($matkaIlta != 0)
		$matkaIlta = '<br><b>Matkat</b>:<br>'.number_format($this->num($matkaIlta), 2, ',', '');
	        else
		$matkaIlta = '';



?>

<tr>

	<td class="tulostus_tekija col1"><?php echo $data->$tt_order_1.' '.$data->$tt_order_2; ?></td>
	<td class="col2"><?php if($tp != 0) echo $tp; ?></td>
	<td class="col3"><?php if($this->num($matkatunnit) != 0) echo number_format($this->num($matkatunnit), 2, ',', ''); ?></td>
	<td class="col4"><?php if($tyotunnit != 0) echo number_format($this->num($tyotunnit), 2, ',', ''); ?></td>
	<td class="col5"><?php echo number_format($this->num($matkatunnit+$tyotunnit), 2, ',', ''); ?></td>
	<td class="col6"><?php echo $iltatunnit_tyotjamatkat.$matkaIlta; ?></td>
	<td class="col7"><?php if($iltatunnit != 0) echo number_format($this->num($iltatunnit), 2, ',', ''); ?></td>
	<td class="col8"><?php echo number_format($this->num($loun), 2, ',', ''); ?></td>
	<td class="col8"><?=number_format($this->num($yotunnit), 2, ',', '')?></td>
	<td class="col9"><?php if($sutunnit != 0) echo number_format($sutunnit, 2, ',', ''); ?></td>
	<td class="col10"><?php echo number_format($this->num($pyhat), 2, ',', ''); ?></td>
	<td class="col10"><?php echo number_format($this->num($el), 2, ',', ''); ?></td>
	<td class="col10"><?php if($sl > 0) echo number_format($sl, 2, ',', ''); ?></td>
	<td class="col11"><?php if($spl > 0) echo number_format($spl, 2, ',', ''); ?></td>
	<td class="col12"><?php if($ls > 0) echo number_format($ls, 2, ',', ''); ?></td>
	<td class="col12"><?php if($vl > 0) echo number_format($vl, 2, ',', ''); ?></td>
	<td class="col12"><?php if($vkl > 0) echo number_format($vkl, 2, ',', ''); ?></td>
</tr>

	


