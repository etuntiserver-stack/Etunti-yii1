<legend><?=Yii::t('main', 'Hinnastot')?></legend>


<?php $model = DigistenHinnasto::model()->findByPk(1); ?>

<div class="row">
 <div class="col-sm-12">
  <div class="table-responsive">
   <table class="table table-bordered table-striped">
   <thead>
    <tr>
     <th><?=Yii::t('main', 'Tunnin määrä')?></th>
     <th><?=Yii::t('main', 'eTyö')?></th>
     <th><?=Yii::t('main', 'eLasku')?></th>
     <th><?=Yii::t('main', 'eOnline')?></th>
     <th><?=Yii::t('main', 'eDico')?></th>
    </tr>
   </thead>
   <tbody>
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
   </tbody>
   <tfoot>
    <tr>
     <th><?=Yii::t('main', 'Modulit')?></th>
     <th><?php echo (in_array(1, $tasot) and in_array(2, $tasot))? '<button class="btn btn-block btn-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Valittu</button>':''; ?></th>
     <th><?php echo (in_array(3, $tasot))? '<button class="btn btn-block btn-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Valittu</button>':'<button class="btn btn-block btn-warning otakayttoon" taso="3" nimike="eLasku"> Ota käyttöön</button>'; ?></th>
     <th><?php echo (in_array(4, $tasot))? '<button class="btn btn-block btn-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Valittu</button>':'<button class="btn btn-block btn-warning otakayttoon" taso="4" nimike="eOnline">Ota käyttöön</button>'; ?></th>
     <th><?php echo (in_array(5, $tasot))? '<button class="btn btn-block btn-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Valittu</button>':'<button class="btn btn-block btn-warning otakayttoon" taso="5" nimike="eDico">Ota käyttöön</button>'; ?></th>
    </tr>
   </tfoot>
   </table>
  </div>
 </div>
</div>

<div id="valmis"></div>

<script type="text/javascript">
$(document).ready(function(){


$(".otakayttoon").click(function(){

    var taso = $(this).attr('taso');
    var thisButton = $(this);

    if(confirm('Haluatko varmaasti ota käyttöön '+$(this).attr('nimike')+' toiminnon?'))
    {
        $.ajax({
           url: 'yrityksentiedot?id=1',
	   type:'POST',
	   data: { taso : taso },
           success: function(data){
		data = JSON.parse(data);
		console.log(data);
		thisButton.replaceWith('<button class="btn btn-block btn-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Valittu</button>');
		$("#valmis").addClass('alert bg-success').html($(this).attr('nimike') +' on käytössä silloin kun kirjaudut seuraavan kerran Etuntiin.');
           }
        });
    }
});



});
</script>

