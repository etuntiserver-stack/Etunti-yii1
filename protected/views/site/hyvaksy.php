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
		   Mobile::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'1_'.date("d.m.Y")));
		   //echo $explVal[1].'<br>';
		}

		if($explVal[0] == 'toteutu')
		{
		   Toteutuneet::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'1_'.date("d.m.Y")));
		   //echo $explVal[1].'<br>';
		}

	    }

	}


  //AsiakasHyvaksynta::model()->updatebypk($model['id'], array('code'=>'','status'=>3));
  ?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="fa fa-gear"></i> <?php echo Yii::t('main', 'ASETUKSET'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
		<h2 class="p15">Test</h2>
                <div class="panel-body bg-light">
                 <div class="row">
		  <h1>Kiitos</h1>
                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>


  <?php

  }
?>
