  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'T'); ?></th>
  <th><?php echo Yii::t('main', 'Versio / GPS'); ?></th>
  <th><?php echo Yii::t('main', 'Pvm'); ?></th>
  <th class="text-center" data-toggle="tooltip" title="Tästä näkyy aloitus ja lopetus etäisyydet kilometri tarkkuudella merkitystä työkohteesta."><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Er.'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th class="text-center" data-toggle="tooltip" title="<?php echo Yii::t('main', 'Työvuorot'); ?>"><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
  <?php endif; ?>


  <th class="text-center" data-toggle="tooltip" title="<?php echo Yii::t('main', 'NFC TAG'); ?>"><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
  <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><?php echo Yii::t('main', 'Laskutettu'); ?></th>
  <th class="text-center" data-toggle="tooltip" title="<?php echo Yii::t('main', 'Hyväksytty'); ?>"><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
  <th class="text-center" data-toggle="tooltip" title="<?php echo Yii::t('main', 'Poista'); ?>"><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'viewData' => array("sivu" => "index", "tv_arr" => $tv_arr),
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
