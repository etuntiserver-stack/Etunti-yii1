<?php

  if(isset($model['id']))
  {
	$ids = explode(",",$model['ids']);
	foreach($ids as $val)
	{
	    $explVal = explode("_", $val);
	    if(isset($explVal[1]))
	    {

		if($explVal[0] == 'mobile') 
		{
		   Mobile::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'1//'.date("d.m.Y"),'status'=>3));
		   //echo $explVal[1].'<br>';
		}

		if($explVal[0] == 'toteutu')
		{
		   Toteutuneet::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'1//'.date("d.m.Y"),'status'=>3));
		   //echo $explVal[1].'<br>';
		}

	    }

	}

  AsiakasHyvaksynta::model()->updatebypk($model['id'], array('code'=>''));
  echo '<h2>Kiitos</h2>';
  }
?>
