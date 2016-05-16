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
	echo $bd;

  if(isset($status))
  {
	$ct = CrmTarjoukset::model()->updatebypk($id, array('status'=>2));
  }

?>

