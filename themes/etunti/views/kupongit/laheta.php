<?php

?>



        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="fa fa-paper-plane-o"></i> <?php echo Yii::t('main', 'Lähetys'); ?> (Alennuskoodi: <?=$model->kupongin_id?>)
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">
			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Yritys';
				if(isset($_POST[$sarake])) 			$postvalue = $_POST[$sarake]; 
				else if(isset(Yii::app()->session[$sarake])) 	$postvalue = Yii::app()->session[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>			
                        </div>
		      </div>


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">
			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yhteyshenkilo';
				$placeholder = 'Yhteyshenkilö';
				if(isset($_POST[$sarake])) 			$postvalue = $_POST[$sarake]; 
				else if(isset(Yii::app()->session[$sarake])) 	$postvalue = Yii::app()->session[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>			
                        </div>
		      </div>

                      <div class="col-md-3 col-md-offset-1">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Lähetä'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>

