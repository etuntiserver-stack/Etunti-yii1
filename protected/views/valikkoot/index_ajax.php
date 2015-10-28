<?php


	// muokka
	if(isset($_POST['muokkaSelects']) and isset($_POST['id']))
	{
		Valikkoot::model()->updatebypk($_POST['id'], array('value'=>$_POST['value']));
	}

	// deleteFromSelect
	if(isset($_POST['deleteFromSelect']) and $_POST['deleteFromSelect'] == "true"){

       		$criteria = new CDbCriteria();
		$criteria->condition = " select_type = '".$_POST['select_type']."' ";
		$v = Valikkoot::model()->findAll($criteria);
	
		if( count($v) > 1 )
		   Valikkoot::model()->deletebypk($_POST['id']);
		else
		    echo '<script>alert("Viimeinen rivi ei voidaan poistaa");</script>';
	
	}
	// uusi
	if(isset($_POST['uusiRiviSelects']) and  $_POST['uusiRiviSelects'])
	{

		$v = new Valikkoot;
		$v->value=$_POST['value'];
		$v->select_type=$_POST['select_type'];
		$v->save();		

	}

	if(isset($_POST['method']) and $_POST['method'] == 'newSelectType')
	{

		$v = new Valikkoot;
		$v->value='TEST';
		$v->select_type=$_POST['select_type'];
		$v->save();	

	echo "<body onload=\"location.href='index'\">";
	exit;
	}



echo '	<div class="row">';



	echo '<BR>';

	echo '<form class="form-inline" action="index_ajax" method="POST">';
	echo '<div class="form-group"><input type="hidden" class="form-control" name="method" value="newSelectType">';
	echo '<input type="text" class="form-control input-sm" name="select_type">';
	echo '<input type="submit" class="btn btn-sm btn-success" value="'.Yii::t('main','Lisä uusi').'"></div>';
	echo '</form>
	<br>';


       	$criteria = new CDbCriteria();
	$criteria->order = "id DESC";
	$criteria->group = "select_type";
	$criteria->condition = " 
		select_type!='tyoajanlaatu'
		AND select_type!='AddTvuoro'
		AND select_type!='laskun_tilanne'
		AND select_type!='palkkaan_hinnat'
		AND select_type!='Ruokatauko'
	";
	$v = Valikkoot::model()->findAll($criteria);

	echo "<div class='row'>";

	foreach($v as $r)
	{

	if($r->select_type == 'vuosilomat') $selType = 'Vuosilomat';
	//elseif($r->select_type == 'laskun_tilanne') $selType = 'laskun tilanne';
	//elseif($r->select_type == 'AddTvuoro') $selType = 'Ajan välit';
	//elseif($r->select_type == 'tyoajanlaatu') $selType = 'Työajanlaatu';
	//elseif($r->select_type == 'palkkaan_hinnat') $selType = 'Palkkaan hinnat';
	//elseif($r->select_type == 'Ruokatauko') $selType = 'Ruokatauko';
	elseif($r->select_type == 'tyoehtosopimus') $selType = 'Työehtosopimus';
	elseif($r->select_type == 'kortit') $selType = 'Kortit';
	elseif($r->select_type == 'online_varauksen_valmina') $selType = 'online varaus';
	elseif($r->select_type == 'Palkkausmuoto') $selType = 'Palkkausmuoto';
	elseif($r->select_type == 'tilanne') $selType = 'Tilanne';
	elseif($r->select_type == 'aktiivinen') $selType = 'Työssä Aktiivinen';
	elseif($r->select_type == 'tyoryhma') $selType = 'Työryhma';
	elseif($r->select_type == 'tyoajanmerkinta') $selType = 'Työajanmerkinta';
	elseif($r->select_type == 'admin status') $selType = 'Oikeukset';
	elseif($r->select_type == 'siivous') $selType = 'Siivous tyyppi';
	elseif($r->select_type == 'asiakas_ryhma') $selType = 'Asiakasryhmä';
	else $selType = $r->select_type;



	echo "<div class='col-sm-3'><fieldset class='well text-center'>";
		echo '<label>'.$selType.'</label>';


	       	$criteria = new CDbCriteria();
		$criteria->select = " value,id,select_type ";
		$criteria->condition = " select_type = '".$r->select_type."' ";
		$v2 = Valikkoot::model()->findAll($criteria);

		foreach($v2 as $u)
		{

		if(isset($_POST['id']) and $_POST['id'] == $u['id'])
		  $success = 'btn-success';
		else
		  $success = '';
	
		echo '
		<div class="row" id="rivi_'.$u->id.'">
		  <div class="form-inline">
			<input type="text" class="input-sm form-control form-group '.$success.'" value="'.$u->value.'" id="m_'.$u->id.'">
			<input type="button" class="btn btn-sm btn-warning muokka" for="m_'.$u->id.'" id="'.$u->id.'" value="M"></button>
			<input type="button" class="btn btn-sm btn-danger deleteFromSelect" id="poista_'.$u->id.'" select_type="'.$u->select_type.'" value="X"></button>
		  </div>
		</div>
		';
		}

		echo '<BR>
		<div class="row">
		  <div class="form-inline">
			<input type="text" class="form-control input-sm form-group" id="u_'.$r->id.'">
			<button class="btn btn-success btn-sm form-group uusi" tyyppi="'.$r->select_type.'" for="u_'.$r->id.'">uusi</button>
		  </div>
		</div>';

	echo "</fieldset></div>";
	}
echo '</div>';


echo '</div>';



?>





<script type="text/javascript">
$(document).ready(function(){



  $(".muokka").click(function(){
	var forID = $(this).attr("for");
	var thisID = $(this).attr("id");
	var thisVal = $("#"+forID).val();
	$("#"+forID).val("Hetkinen..");
        $.ajax({
           url: 'index_ajax',
           type: "POST",
           data: {"muokkaSelects" : "true", "id" : thisID, "value" : thisVal},
           success: function(html){
		$('#result').html(html);
           }
        });
  });

  $(".deleteFromSelect").click(function(){
	var thisID = $(this).attr("id").split("poista_");
	var select_type = $(this).attr("select_type");
        var r=confirm("Oletko varmaa?")
        if (r)
	{
        $.ajax({
           url: 'index_ajax',
           type: "POST",
           data: { "deleteFromSelect" : "true", "id" : thisID[1], "select_type" : select_type },
           success: function(html){
		$('#result').html(html);
           }
        });
	}
  });


  $(".uusi").click(function(){
	var forID = $(this).attr("for");
	var select_type = $(this).attr("tyyppi");
	var thisVal = $("#"+forID).val();
        $.ajax({
           url: 'index_ajax',
           type: "POST",
           data: {"uusiRiviSelects" : "true", "value" : thisVal, "select_type" : select_type},
           success: function(data){
		//console.log(html);
		$('#result').html(data);
           }
        });
  });






});
</script>

