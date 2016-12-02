<?php

  $criteria = new CDbCriteria();
  $criteria->order = " pvm DESC ";
  $criteria->condition = 
	" 
	tid='".$tid."'
	AND pvm
	BETWEEN '$from' AND '$to' 
	";

  $m = Korvaukset::model()->findAll($criteria);
  foreach($m as $v)
  {
	echo 
		$v->getAttributeLabel('pvm').': '.$v->pvm.'<br>'.
		$v->getAttributeLabel('syy').': '.$v->syy.'<br>'.
		$v->getAttributeLabel('korvaus').': '.$v->korvaus.'<br>';

	if(!Yii::app()->request->getPost('tulosta'))
	{
	  echo CHtml::link("Poista", '#', array(
	  	'submit'=>array('korvaukset/delete', "id"=>$v->id), 
	  	'confirm' => 'Oletko varmaa?')
	  );
	}

	if($v->id)
	echo '<hr>';
  }

?>
