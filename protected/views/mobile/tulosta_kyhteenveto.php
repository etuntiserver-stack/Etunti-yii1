<style>
table{
	width: 290px;
}
td,th{
	padding:3px 7px;
	border:1px #333 solid;
}
</style>

<?php if(Yii::app()->session['from'] and Yii::app()->session['to']) : ?>
  <table>
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunniteltu tunnit'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luetut tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></th>
  </tr>
  </thead>

  <tbody>
  <?php 
  foreach($model as $data)
  {
	$this->renderPartial('_kyhteenveto',array('data'=>$data));
  }
  ?>
  </tbody>

  </table>
<?php endif; ?>
