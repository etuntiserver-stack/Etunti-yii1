<legend><?=Yii::t('main', 'Hinnastot')?></legend>


<?php $model = DigistenHinnasto::model()->findByPk(1); ?>


<div class="row">
 <div class="col-sm-12">
  <div class="table-responsive">
   <table class="table table-bordered table-striped">
    <tr>
     <th><?=Yii::t('main', 'Tunnin määrä')?></th>
     <th><?=Yii::t('main', 'Etyö')?></th>
     <th><?=Yii::t('main', 'Elasku')?></th>
     <th><?=Yii::t('main', 'Eonline')?></th>
     <th><?=Yii::t('main', 'Edico')?></th>
    </tr>
    <tr>
     <td><?=Yii::t('main', 'Alle 500')?></td>
     <td><?=$model->etyo_1000?>&euro;</td>
     <td><?=$model->elasku_1000?>&euro;</td>
     <td><?=$model->eonline_1000?>&euro;</td>
     <td><?=$model->edico_1000?>&euro;</td>
    </tr>
    <tr>
     <td><?=Yii::t('main', '500-1500')?></td>
     <td><?=$model->etyo_1000_2000?>&euro;</td>
     <td><?=$model->elasku_1000_2000?>&euro;</td>
     <td><?=$model->eonline_1000_2000?>&euro;</td>
     <td><?=$model->edico_1000_2000?>&euro;</td>
    </tr>
    <tr>
     <td><?=Yii::t('main', '1500-3000')?></td>
     <td><?=$model->etyo_2000_3000?>&euro;</td>
     <td><?=$model->elasku_2000_3000?>&euro;</td>
     <td><?=$model->eonline_2000_3000?>&euro;</td>
     <td><?=$model->edico_2000_3000?>&euro;</td>
    </tr>
    <tr>
     <td><?=Yii::t('main', '3000-6000')?></td>
     <td><?=$model->etyo_3000_6000?>&euro;</td>
     <td><?=$model->elasku_3000_6000?>&euro;</td>
     <td><?=$model->eonline_3000_6000?>&euro;</td>
     <td><?=$model->edico_3000_6000?>&euro;</td>
    </tr>
    <tr>
     <td><?=Yii::t('main', '9000 plus')?></td>
     <td><?=$model->etyo_9000_plus?>&euro;</td>
     <td><?=$model->elasku_9000_plus?>&euro;</td>
     <td><?=$model->eonline_9000_plus?>&euro;</td>
     <td><?=$model->edico_9000_plus?>&euro;</td>
    </tr>
   </table>
  </div>
 </div>
</div>


