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


<?php
		$domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' AND maksullinen=0 ");
?>
	  <?php if(isset($domainit->id)) : ?>
	  <div class="col-sm-offset-1 col-sm-8">
	  <legend><?=Yii::t('main', 'Aloita laajennettu käyttö')?></legend>


<!-- Modal -->
<style>
.modal_checkbox {
    -webkit-appearance:none;
    width:20px;
    height:20px;
    background:white;
    border-radius:5px;
    border:2px solid #555;
}
.modal_checkbox:checked {
    background: #abd;
}
.modaltxt{
  margin-left: 10px;
  font-size:120%;
}
</style>

<div id="body-aloita">
<p><b>Laajennettu</b> Etunti-ohjelma mahdollistaa yli <b>500</b> työtunnin suunnittelun ja toteuman. Lisäksi saat kattavamman käyttäjätuen käyttöösi. Sinulla on myös mahdollisuus muokata palvelupakettiasi haluamaasi kokoonpanoon. Tutustu lisäosiin <?php echo CHtml::link('tästä',"/index.php/site/mika-on-etunti"); ?>.</p>
 
<p><b>Etunti-ohjelman</b> maksullisen version hinta perustuu suunniteltuihin tai toteutuneisiin työtunteihin, riippuen siitä, kumpi luku on suurempi. Työtunti tarkoittaa joko suunniteltua tai leimattua työtuntia, riippuen siitä kumpien yhteenlaskettu summa on suurempi. Työtunnit eivät sisällä matkoja eivätkä lounaita. Maksat siis vain työtuntien mukaan. Katso tarkempi hinnasto <?php echo CHtml::link('täältä',"/index.php/site/mika-on-etunti"); ?>.</p>
 
<p><b>Huom!</b> Kun olet ottanut käyttöön maksullisen version, ei sitä voi enää palauttaa ilmaisversioksi.</p>
 
 
<p>Valitse Laajennetun palvelun kokonaisuus tästä:</p>

<div class="row">
 <div class="col-sm-offset-1 col-sm-6">
	<p><input type="checkbox" name="eTyo" value="eTyo" class="modal_checkbox" checked disabled> 
		<span class="modaltxt">eTyö (Sisältyy)</span>
	</p>
	<p><input type="checkbox" name="eLasku" class="modal_checkbox val" value="3"> 
		<span class="modaltxt">eLasku</span>
	</p>
	<p><input type="checkbox" name="eOnline" class="modal_checkbox val" value="4"> 
		<span class="modaltxt">eOnline</span>
	</p>
	<p><input type="checkbox" name="eDico" class="modal_checkbox val" value="5"> 
		<span class="modaltxt">eDico</span>
	</p>
 </div>
</div>

</div>

        <button type="button" class="btn btn-primary aloitan_maksullinen"><?=Yii::t('main', 'Aloita Laajennettu käyttö')?></button>
	<?php echo CHtml::link('Kirjaudu ulos',"/index.php/user/logout",array(
		"class"=>"btn btn-primary hidden",
		"id" => "ulospainike"
	)); ?>


<script>
$( document ).ready(function() {
  $(".aloitan_maksullinen").click(function(){

    $(".aloitan_maksullinen").text('Odota..');
    var paketti = [];
    $( ".modal_checkbox.val" ).each(function(index) {
	paketti[$( this ).val()] = $( this ).prop('checked');
    });
    //console.log(paketti);
	if(confirm('Olet ottamassa käyttöön Etunnin laajennetun palvelun. Painamalla OK vahvistat tutustuneesi palvelun hinnastoon.'))
	{
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/maksullinen",
	   type:'POST',
	   data: {dat : paketti},
           success: function(data){
		console.log(data);

		$(".aloitan_maksullinen").remove();
		$("#ulospainike").removeClass('hidden');
	
		$("#body-aloita").html('<p>Onneksi olkoon!</p>' +
			'Sinulla on nyt laajennettu Etunti-ohjelma liiketoimintasi tukena.' +
			'<p><b>Kirjaudu ulos ja palaa takaisin.</b></p>'
		);

    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
	}
	return false;
  });
});
</script>

        </div>
	<?php endif; ?>


       </div>
      </div>
     </div>
