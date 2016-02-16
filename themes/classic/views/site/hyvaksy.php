        <!-- Header-->
        <header>
            <!-- Container-->
            <div class="container">
                <!-- Row-->
                <div class="row">
                    <!-- Logo-->
                    <div class="col-md-3">
                        <div class="logo">
  			<?php $asetukset=Asetukset::model()->find("id=1"); ?>
  			<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
                        </div>
                    </div>
                    <!-- End Logo-->

                    <!-- Nav-->
                    <div class="col-md-9 slogan">
                        <!--Voita siivousalan haasteet-->
                    </div>
                    <!-- End Nav-->
                </div>
                <!-- End Row-->
            </div>
            <!-- End Container-->
        </header>
        <!-- End Header-->

<?php
  if(isset($model->id))
  {
	$ids = explode(",",$model->ids);
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


echo '
        <section class="esittely">
            <div class="paddings">
                <div class="container">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->
                        <h1 class="title-subtitle text-center">Olet hyväksynyt tunteja.
                            <span>
                              Kiitos.
                            </span>
                        </h1>
                        <hr>
                    <!-- End Titles Heading -->

                </div>
                <!-- End Container-->
            </div>
        </section>        
';
  
  AsiakasHyvaksynta::model()->updatebypk($model->id, array('code'=>'','status'=>3));

  } else {

echo '
        <section class="esittely">
            <div class="paddings">
                <div class="container">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->
                        <h1 class="title-subtitle text-center">Tämä linkki on vanhentunut.
                            <span>
                              Kiitos.
                            </span>
                        </h1>
                        <hr>
                    <!-- End Titles Heading -->

                </div>
                <!-- End Container-->
            </div>
        </section>        
';

  }
?>

