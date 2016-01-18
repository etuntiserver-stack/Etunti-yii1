<?php

  $criteria = new CDbCriteria();
  $criteria->order = " pvm DESC ";
  $criteria->condition = 
	" 
	tid='".$tid."'
	AND pvm 
	BETWEEN '$from' AND '$to' 
	";

  $m = Lisatyotunnit::model()->findAll($criteria);
  foreach($m as $v)
  {
	echo 

		$v->getAttributeLabel('pvm').': '.$v->pvm.'<br>'.
		$v->getAttributeLabel('syy').': '.$v->syy.'<br>'.
		$v->getAttributeLabel('prosentti').': '.$v->prosentti.'<br>'.
		$v->getAttributeLabel('tunnimaara').': '.$v->tunnimaara.'<br>';

	if(!Yii::app()->request->getPost('tulosta'))
	{
	  echo CHtml::link("Poista", '#', array(
	  	'submit'=>array('lisatyotunnit/delete', "id"=>$v->id), 
	  	'confirm' => 'Oletko varmaa?')
	  );
	}


	if($v->id)
	echo '<hr>';

  }

?>
