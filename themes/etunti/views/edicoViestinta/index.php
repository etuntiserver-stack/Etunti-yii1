<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

Domainit::sendGCMeDico(9, Yii::t('main', 'Uusi viesti'), 'testi teksti', null);
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'eDico Viestintä'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/edicoViestinta/create',array('class'=>'btn btn-default fa fa-plus', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title' => Yii::t('main', 'Lisää kohde') )); ?>

	 <div class="pull-right montakoRiviaSivulle">
	   <?php
	   ($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';
	   ($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
	   ($perSivu == 100) ? $defcl100 = 'btn-success' : $defcl100 = 'btn-default';

	   echo '<button class="btn '.$defcl10.' kpl" kpl="10" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10 '.Yii::t('main', 'kohdetta sivulla').'">10</button>';
	   echo '<button class="btn '.$defcl50.' kpl" kpl="50" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 50 '.Yii::t('main', 'kohdetta sivulla').'">50</button>';
	   echo '<button class="btn '.$defcl100.' kpl" kpl="100" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 100 '.Yii::t('main', 'kohdetta sivulla').'">100</button>';

	   ?>
	 </div>
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

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
				$placeholder = 'Asiakas';
				if(isset($_POST[$sarake])) 			$postvalue = $_POST[$sarake]; 
				else if(isset(Yii::app()->session[$sarake])) 	$postvalue = Yii::app()->session[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod, array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>




                      <div class="col-md-2 col-sm-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th>#</th>
  <th><?php echo Yii::t('main', 'Keskustelun luonti'); ?></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Otsikko'); ?></th>
  <th><?php echo Yii::t('main', 'Viimeinen viesti'); ?></th>
  <th></th>
  <th><?php echo Yii::t('main', 'Vastaus'); ?></th>
  <th></th>
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
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){


$(".haemob").click(function(){
	$("#mobForm").submit();
});


 $(".kpl").click(function(){
	var kohteetPerSivu = $(this).attr('kpl');
        $.ajax({
           url: 'index',
           type: "POST",
           data: { "kohteetPerSivu" : kohteetPerSivu },
           success: function(data){
		var d = JSON.parse(data);
		window.location.reload();

           }
        });
 });

});
</script>
