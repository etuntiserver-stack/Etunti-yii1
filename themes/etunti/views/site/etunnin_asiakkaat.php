<?php
//Yii::log('joko', 'info', 'application.site');
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'ASIAKKAAT'); ?> </h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			   <select class="gui-input" name="aktiivinen" id="aktiivinen">
       				<option value=1 <?php echo(isset($_GET['aktiivinen']) and $_GET['aktiivinen'] == 1)? 'selected':''; ?>><?php echo Yii::t('main', 'Aktiiviset'); ?></option>
       				<option value=0 <?php echo(isset($_GET['aktiivinen']) and $_GET['aktiivinen'] == 0)? 'selected':''; ?>><?php echo Yii::t('main', 'Passiviset'); ?></option>
			   </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			   <select class="gui-input" name="maksullinen" id="maksullinen">
       				<option value=1 <?php echo(isset($_GET['maksullinen']) and $_GET['maksullinen'] == 1)? 'selected':''; ?>><?php echo Yii::t('main', 'Maksulliset'); ?></option>
       				<option value=0 <?php echo(isset($_GET['maksullinen']) and $_GET['maksullinen'] == 0)? 'selected':''; ?>><?php echo Yii::t('main', 'Ilmaiset'); ?></option>
			   </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="domain_nimi" value="<?php if(isset($_GET['domain_nimi'])) echo $_GET['domain_nimi']; ?>" placeholder="<?php echo Yii::t('main', 'Domain'); ?>..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>



                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                      <div class="col-md-2 col-md-offset-4">
			<?php echo CHtml::link(Yii::t('main', 'Lähetä kirje'),Yii::app()->request->baseUrl.'/index.php/site/laheta_et_kirje',array('class'=>'btn btn-default')); ?>
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>

<?php
	$start_date = date( "Y-m-d", strtotime('first day of this month') );
	$end_date = date("Y-m-d", strtotime('last day of this month') );
?>

  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Domain'); ?></th>
  <th><?php echo Yii::t('main', 'Yritys'); ?></th>
  <th><?php echo Yii::t('main', 'Puhelin'); ?></th>
  <th><?php echo Yii::t('main', 'Sähköposti'); ?></th>
  <th><?php echo Yii::t('main', 'Modulit'); ?></th>
  <th><?php echo Yii::t('main', 'Kuukauden käyttö (tunnit)'); ?></th>
  <th><?php echo Yii::t('main', 'Hinnat'); ?></th>
  <?php if( isset($_GET['maksullinen']) and $_GET['maksullinen'] == 0 ): ?>
  <th>
	<?php echo Yii::t('main', 'Käyttötunnit'); ?>
	<p><?=date("d.m.Y", strtotime($start_date))?>-<?=date("d.m.Y", strtotime($end_date))?></p>
  </th>
  <?php endif; ?>
  <th><?php echo Yii::t('main', 'Kuukauden laskuri'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_etunnin_asiakkaat',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',
	//'viewData' => array( 'site' => $site ),

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
