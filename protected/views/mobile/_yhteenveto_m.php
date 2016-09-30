<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$total_l 	= 0;
		$total_t 	= 0;
		$totalTp	= 0;
		$totalIlta 	= 0;
		$totalYo 	= 0;
		$totalSu	= 0;
		$tp		= 0;
		$al		= '';
		$lop		= '';
		$total_sunniteltu = 0;

       		$criteria = new CDbCriteria();
		$this->luMatka($criteria,$data->tid,$from,$to);

		$lu = Mobile::model()->find($criteria);

		$this->totMatka($criteria,$data->tid,$from,$to);
		$tot = Toteutuneet::model()->find($criteria);
	
		$totatl_t = $lu->l_tunnit+$tot->l_tunnit;
?>

<tr>

	<td class="col1">
		<?php 
			$tnimi = Tyontekijat::model()->findbypk($data->tid); 
			echo $tnimi->tekijan_nimi;
		?>
	</td>
	<td class="col2"><?php echo $this->sprint($data->l_tunnit); ?></td>
	<td class="col3"><?php echo $this->sprint($totatl_t); ?></td>

</tr>

	


