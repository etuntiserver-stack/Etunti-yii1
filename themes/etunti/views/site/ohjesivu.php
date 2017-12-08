<?php
	$o = AsetuksetForAll::model()->findbypk(1);
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Ohjesivu'); ?> 
		</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-sm-12">

		    	<p><?= CHtml::link('<i class="fa fa-file-pdf-o pull-right" aria-hidden="true"></i> Etunti ota käyttöön', 
				Yii::app()->request->baseUrl."/lib/pdf/etunti_ko.pdf", array(
				'target' => '_blank',
				'class' => 'btn btn-primary btn-lg btn-block myBgColors'
			)); ?></p>

		    	<p><?= CHtml::link('<i class="fa fa-file-pdf-o pull-right" aria-hidden="true"></i> Etunti mobiilisovellus', 
				Yii::app()->request->baseUrl."/lib/pdf/Etunti-mobiilisovellus.pdf", array(
				'target' => '_blank',
				'class' => 'btn btn-primary btn-lg btn-block myBgColors'
			)); ?></p>

		    	<p><?= CHtml::link('<i class="fa fa-file-pdf-o pull-right" aria-hidden="true"></i> eDico mobiilisovellus', 
				Yii::app()->request->baseUrl."/lib/pdf/.pdf", array(
				'target' => '_blank',
				'class' => 'btn btn-primary btn-lg btn-block myBgColors'
			)); ?></p>

		    	<p><?= CHtml::link('<i class="fa fa-file-pdf-o pull-right" aria-hidden="true"></i> eDico käyttöehdotmalli', 
				Yii::app()->request->baseUrl."/lib/pdf/.pdf", array(
				'target' => '_blank',
				'class' => 'btn btn-primary btn-lg btn-block myBgColors'
			)); ?></p>

                      </div>

                    </div>

                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>






