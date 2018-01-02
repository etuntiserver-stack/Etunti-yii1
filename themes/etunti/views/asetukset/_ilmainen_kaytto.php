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
<p><b>Laajennettu</b> Etunti-ohjelma mahdollistaa yli <b>500</b> työtunnin suunnittelun ja toteuman. Lisäksi saat kattavamman käyttäjätuen käyttöösi. Sinulla on myös mahdollisuus muokata palvelupakettiasi haluamaasi kokoonpanoon. Tutustu lisäosiin <?php echo CHtml::link('tästä',"https://etunti.fi", array('target' => '_blank')); ?>.</p>
 
<p><b>Etunti-ohjelman</b> maksullisen version hinta perustuu suunniteltuihin tai toteutuneisiin työtunteihin, riippuen siitä, kumpi luku on suurempi. Työtunti tarkoittaa joko suunniteltua tai leimattua työtuntia, riippuen siitä kumpien yhteenlaskettu summa on suurempi. Työtunnit eivät sisällä matkoja eivätkä lounaita. Maksat siis vain työtuntien mukaan. Katso tarkempi hinnasto.</p>
 
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
