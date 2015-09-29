<?php

  $criteria = new CDbCriteria();
  $criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
  $criteria->condition = 
	" 
	tid='".$tid."'
	AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
	BETWEEN '$from' AND '$to' 
	";

  $m = Korvaukset::model()->findAll($criteria);
  foreach($m as $v)
  {
	echo 
	'<div class="well small">'.
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
	echo '</div>';

	if($v->id)
	echo '<hr>';
  }

?>
