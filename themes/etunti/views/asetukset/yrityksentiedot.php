<?php
if(isset($_GET['first']))
{
echo '
<!-- Modal -->
<style>
.modal-dialog-center {
    margin-top: 15%;
}
</style>
<div id="myModalFirst" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-center">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tervetuloa Etunnin käyttäjäksi.</h4>
      </div>
      <div class="modal-body">
        <p>Ohjeet löydät ylärivin valikosta, kohta Asetukset. Käyttöönotto-ohjeen saat <a href="'.Yii::app()->request->baseUrl.'/lib/pdf/etunti_ko.pdf" target="_blank">tästä</a>.</p>
	<p>Täydennä <span style="color:red">*</span> merkityt kentät ennen tallentamista.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
      </div>
    </div>

  </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
	$("#myModalFirst").modal({ show : true });
	$("#yrityksentiedot").addClass("in");
});
</script>
';

	FirmanTiedot::model()->updateByPk(1, array( 'juuri_tullut_asiakkaaksi' => 0 ));
}
?>



        <!-- begin: .tray-center -->
        <div class="tray-center">

	<h2 class="myBgColors p20"> <i class="fa fa-gear"></i> <?=Yii::t('main','Yrityksen tiedot')?> </h2>

     <div class="admin-form">
      <div class="panel heading-border">
       <div class="panel-body bg-light">

        <div class="row">
	  <div class="col-sm-3">
		  <?php echo $this->renderPartial('//firmanTiedot/_form', array('model'=>$f)); ?>
	  </div>
	  <div class="col-sm-offset-1 col-sm-8">


		<!-- ilmainen_kaytto -->
		<?php if(isset($domainit->id) and $domainit->maksullinen == 0) : ?>
		<?php echo $this->renderPartial('_ilmainen_kaytto'); ?>
		<?php endif; ?>
		<!-- ilmainen_kaytto -->

		<!-- Hinnastot -->
		<hr>
		<?php // if(isset($domainit->id) and $domainit->maksullinen == 1) : ?>
		<?php echo $this->renderPartial('_hinnastot', array('domainit' => $domainit, 'tasot' => $tasot)); ?>
		<?php // endif; ?>
		<!-- Hinnastot -->

		<!-- Laskuri -->
		<?php
			$site = Yii::app()->createController('Site');
			$start_date = date( "Y-m-d", strtotime('first day of this month') );
			$end_date = date("Y-m-d", strtotime('last day of this month') );
			$sum_result = $site[0]->digistenTunnitYhteensa($start_date, $end_date, 'kesto');
		?>
		<p><h2>Arvio työtunneista tässä kuussa: <?=(int)$sum_result; ?></h2></p>
		<!-- laskuri -->
	  </div>
        </div>

       </div>
      </div>
     </div>


     <h2 class="myBgColors p20"> <i class="fa fa-gear"></i> <?=Yii::t('main','Tapahtumat')?> </h2>
     <div class="admin-form">
      <div class="panel heading-border">
       <div class="panel-body bg-light">
        <div class="row">
	  <div class="col-sm-12">
		<?php
		$criteria = new CDbCriteria();
		$criteria->condition = " 
			yritys_id='".$domainit->id."' 
		";
		$dataProvider=new CActiveDataProvider('DigistenYritysLog', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));
		$ylog = DigistenYritysLog::model()->findAll($criteria);
		?>

		 <div class="row table-responsive">
		  <table class="table table-striped" id="mobileTable">
		  <thead class="myBgColors">
		  <tr>
		  <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>
		  <th><?php echo Yii::t('main', 'Tapahtuman sisältö'); ?></th>
		  <th><?php echo Yii::t('main', 'Tasot'); ?></th>
		  </tr>
		  </thead>
		  <?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_yrityksen_lokitus',
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




     </div>


