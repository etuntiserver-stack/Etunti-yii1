<?php

	$k=Kohteet::model()->findbypk($data->kohde);
	if(isset($k->id)) $osoite = $k->osoite; else $osoite = '';

	$tt=Tyontekijat::model()->findbypk($this_tid);
	if(isset($tt->id)) $tekijan_nimi = $this->etuSukunimi($tt->id); else $tekijan_nimi = '';


  	$path = Yii::app()->basePath.'/../img/tekijat/'.Yii::app()->user->domain.'/'. $this_tid.'.jpg';
  	if (file_exists($path) and isset($_POST['tulosta'])){
		$img =  '<img src="img/tekijat/'.Yii::app()->user->domain.'/'. $this_tid.'.jpg" height="30">';
  	} elseif (file_exists($path) and !isset($_POST['tulosta'])){
		$img =  '<img src="'.Yii::app()->request->baseUrl.'/img/tekijat/'.Yii::app()->user->domain.'/'. $this_tid.'.jpg" height="60">
			<p>'.$tekijan_nimi.'</p>';
	} else {
		$img =  $tekijan_nimi;
	}

  $kesto = strtotime($data->loppu)-strtotime($data->alku);
  $kesto_formated = $this->sprint($kesto);

  $link = CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></input>',
    [
      sprintf('/tyovuoroot/beta?mode=vko&year=%s&week=%s&tv_id=%s&tv_filter=1', date("Y", strtotime($this_pvm)), date("W", strtotime($this_pvm)), $this_id)
    ],
    [
      'class' => 'btn btn-primary myBgColors',
      'style' => 'color:white',
      'data-toggle' => 'tooltip',
      'data-placement' => 'top',
      'title' => Yii::t('main', 'Muokkaa'),
      'target' => '_blank'
    ]
  );

  switch ($data->peruutettu) {
    case 1: $peruutettu_text = 'Peruutettu'; break;
    case 2: $peruutettu_text = 'Peruutettu Laskutettava'; break;
    case 3: $peruutettu_text = "Peruutettu, laskutetaan välineet 9,90€"; break;
    case 4: $peruutettu_text = "Peruutettu, laskutetaan välineet 19,90€"; break;
    default: $peruutettu_text = ''; break;
  }

  echo <<<EOC
<tr>
  <td>$link</td>
  <td class="col1">$this_pvm</td>
  <td class="col2">$data->alku-$data->loppu</td>
  <td class="col1">$kesto_formated</td>
  <td class="col3">$osoite</td>
  <td class="col4">$img</td>
  <td class="col1">$data->tietoja</td>
  <td class="peruutettu"><span class="text-danger"><b>$peruutettu_text</b></span></td>
</tr>
EOC;
