<?php
/* @var $this TarjouslaskentaController */
/* @var $dataProvider CActiveDataProvider */
?>



        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Työnkuvaukset'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/tyonkuvaus/create',array('class'=>'btn btn-default fa fa-plus')); ?>
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form" id="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">


                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Yritys';
				if(isset($_POST[$sarake])) $postvalue = $_POST[$sarake]; else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->


                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
		      </div>

                    </div>




                </div>
              </div>
            </div>
	    </form>



        <!-- loppu: .tray-center -->
        </div>


  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Kohde'); ?></th>
  <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>
  <th><?php echo Yii::t('main', 'Tila'); ?></th>
  <th><i class="fa fa-file-pdf-o" aria-hidden="true"></i>
</th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


	'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 

  )); ?>
  </table>
</div>

   </div>
  </div>


<script type="text/javascript">
$(document).ready(function(){

  $(".haemob").click(function(){
	$("#mobForm").submit();
  });


});
</script>
