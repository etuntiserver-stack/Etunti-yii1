<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$return = $this->toteutu($data->id,"palkkataulukko");

		if($return[0] != 0)
		$return[0] = $this->sprint($return[0]).'<br>('.$this->num($return[0]).')';

		if($return[1] != 0)
		$return[1] = $this->sprint($return[1]).'<br>('.$this->num($return[1]).')';

		if($return[2] != 0)
		$return[2] = $this->sprint($return[2]).'<br>('.$this->num($return[2]).')';

		if($return[3] != 0)
		$return[3] = $this->sprint($return[3]).'<br>('.$this->num($return[3]).')';

		$matkaIlta = 0;
		$matkaIlta = $this->matkaIlta($data->id);
		if($matkaIlta != 0)
		$matkaIlta = '<br><b>Matkat</b>:<br>'.$this->sprint($matkaIlta).'<br>('.$this->num($matkaIlta).')';



  $matka = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>Yii::app()->session['from'],
		'to'=>Yii::app()->session['to'],
		'tid'=>$data->id
		),true);

		if($matka != 0)
		$matka = $this->sprint($matka).'<br>('.$this->num($matka).')';

  $lisatt = $this->renderPartial('//lisatyotunnit/tidfromto',array(
		'from'=>Yii::app()->session['from'],
		'to'=>Yii::app()->session['to'],
		'tid'=>$data->id
		),true);

  $korv = $this->renderPartial('//korvaukset/tidfromto',array(
		'from'=>Yii::app()->session['from'],
		'to'=>Yii::app()->session['to'],
		'tid'=>$data->id
		),true);

  $ennakko = $this->renderPartial('//ennakko/tidfromto',array(
		'from'=>Yii::app()->session['from'],
		'to'=>Yii::app()->session['to'],
		'tid'=>$data->id
		),true);
?>

<tr>

	<td class="tulostus_tekija"><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo $tp; ?></td>
	<td><?php echo $matka; ?></td>
	<td><?php echo $return[0]; ?></td>
	<td><?php echo $return[1].$matkaIlta; ?></td>
	<td><?php echo $return[2]; ?></td>
	<td><?php echo $return[3]; ?></td>
	<td><?php echo $korv; ?></td>
	<td><?php echo $lisatt; ?></td>
	<td><?php echo $ennakko; ?></td>
</tr>

	


