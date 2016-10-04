<?php
/* @var $this MobileController */
/* @var $data Mobile */

?>

<tr>
	<td class="col1"><?php echo $tekijan_nimi; ?></td>
	<td class="col2"><?php if($luetut == 0) echo '00:00'; else echo $this->sprint($luetut); ?></td>
	<td class="col3"><?php if($toteutuneet == 0) echo '00:00'; else echo $this->sprint($toteutuneet); ?></td>
</tr>

	


