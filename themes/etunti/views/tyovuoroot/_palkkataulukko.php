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
		$iltatunnit_tyotjamatkat = '<b>Työt</b>:<br>'.$this->num($iltatunnit-$matkaIlta);
	        else
		$iltatunnit = '';

		if($sutunnit != 0)
		$sutunnit = $this->num($sutunnit);

		if($matkaIlta != 0)
		$matkaIlta = '<br><b>Matkat</b>:<br>'.$this->num($matkaIlta);
	        else
		$matkaIlta = '';



?>

<tr>

	<td class="tulostus_tekija col1"><?php echo $this->etuSukunimi($data->id); ?></td>
	<td class="col2"><?php if($tp != 0) echo $tp; ?></td>
	<td class="col3"><?php if($this->num($matka) != 0) echo $this->num($matka); ?></td>
	<td class="col4"><?php if($toteutu != 0) echo $this->num($toteutu); ?></td>
	<td class="col5"><?php echo $this->num($matka+$toteutu); ?></td>
	<td class="col6"><?php echo $iltatunnit_tyotjamatkat.$matkaIlta; ?></td>
	<td class="col7"><?php if($iltatunnit != 0) echo $this->num($iltatunnit); ?></td>
	<td class="col8"><?php echo $this->num($loun); ?></td>
	<td class="col8"><?=$this->num($yotunnit)?></td>
	<td class="col9"><?php if($sutunnit != 0) echo $sutunnit; ?></td>
	<td class="col10"><?php echo $this->num($pyhat); ?></td>
	<td class="col10"><?php echo $this->num($el); ?></td>
	<td class="col10"><?php if($sl > 0) echo $sl; ?></td>
	<td class="col11"><?php if($spl > 0) echo $spl; ?></td>
	<td class="col12"><?php if($ls > 0) echo $ls; ?></td>
	<td class="col12"><?php if($vl > 0) echo $vl; ?></td>
	<td class="col12"><?php if($vkl > 0) echo $vkl; ?></td>
</tr>

	


