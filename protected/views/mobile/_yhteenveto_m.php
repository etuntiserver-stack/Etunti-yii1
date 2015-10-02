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
		$this->luMatka($criteria,$data->tid,Yii::app()->session['from'],Yii::app()->session['to']);

		$lu = Mobile::model()->find($criteria);

		$this->totMatka($criteria,$data->tid,Yii::app()->session['from'],Yii::app()->session['to']);
		$tot = Toteutuneet::model()->find($criteria);
	
		$totatl_t = $lu->l_tunnit+$tot->l_tunnit;
?>

<tr>

	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo $this->sprint($data->l_tunnit); ?></td>
	<td><?php echo $this->sprint($totatl_t); ?></td>
<?php
/*
	<td><?php echo $totalTp; ?></td>
	<td><?php echo $this->sprint($totalIlta); ?></td>
	<td><?php echo $this->sprint($totalYo); ?></td>
	<td><?php echo $this->sprint($totalSu); ?></td>
*/
?>
</tr>

	


