<?php

$criteria = new CDbCriteria();
$criteria->select = 'id, tyyppi, yrityksen_nimi, yhteyshenkilo, sahkoposti';
$criteria->condition = "(tyyppi = 'yritys' AND (yrityksen_nimi != '' OR sahkoposti != '')) OR (tyyppi = 'henkilo' AND (yhteyshenkilo != '' OR sahkoposti != ''))";
$customers_results = Asiakkaat::model()->findAll($criteria);
foreach ($customers_results as $c)
  $customers[$c->id] = ($c->tyyppi == 'yritys' ? $c->yrityksen_nimi : $c->yhteyshenkilo) ?: $c->sahkoposti;

$freshdesk_domain = 'santelo'; // TEMP

?>

<!-- Base structure for a ticket, which will be cloned into actual tickets. -->
<div style="display:none">
  <div class="ticket" id="ticket-base">
    <div class="ticket-hidden-id" style="display:none"></div>
    <div class="ticket-hidden-data" style="display:none"></div>
    <div class="ticket-body caption text-center" onclick="">
      <!-- onclick="location.href='/index.php/asiakkaat/freshdesk/id" -->
      <h4 class="ticket-label"><a class="ticket-subject" href="#" target="_blank"></a></h4>
      <p><i class="glyphicon glyphicon-user light-red lighter bigger-120"></i>&nbsp;<a class="ticket-customer-link" href="#" target="_blank" style="color:inherit;">
          <!-- Customer Name --></a></p>
      <div class="ticket-summary smaller">
        <!-- Description -->
      </div>
    </div>
    <div class="ticket-footer caption card-footer text-center">
      <!-- bg-[color] based on status -->
      <ul class="ticket-footer-list list-inline">
        <!-- text-dark if not answered -->
        <li><i class="people lighter"></i>&nbsp;<i class="ticket-status"></i></li>
        <li></li>
        <li><i class="glyphicon glyphicon-envelope lighter"></i>&nbsp;<a class="ticket-respond-link" href="#" target="_blank" style="color:inherit">Vastaa</a></li>
      </ul>
    </div>
  </div>
</div>

<!-- Top-left popup error -->
<div style="position:relative">
  <div id="alert-container" class="alert fade in bg-danger">
    <button class="close pull-left light" data-dismiss="alert">×</button>
    <span id="alert-text">Tukipyyntöjen haussa tapahtui virhe. Paina tästä lisätiedot.</span>
  </div>
</div>

<!-- Bottom-center progress bar (fixed) -->
<div class="progress-bar-outer">
  <div class="progress-bar-inner">
    <div class="progress-bar progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
    </div>
  </div>
</div>

<!-- Primary container for controls and tickets. -->
<div id="main-container">

  <!-- Fullscreen popup -->
  <div id="fullscreen-popup-container">
    <div id="fullscreen-popup" class="collapse">
      <div class="row">
        <div class="col-md-11">
          <h4 id="fullscreen-popup-header">&nbsp;</h4>
        </div>
        <div class="col-md-1">
          <button type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div id="fullscreen-popup-body">&nbsp;</div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">

      <!-- Top controls bar  -->
      <div id="top-bar">
        <div id="domain-label" class="pull-right"><?= $freshdesk_domain ?></div>
      </div>

      <!-- Menu popup button -->
      <button id="menu-open-button" class="btn-primary pull-right" data-toggle="collapse" data-target="#menu" aria-expanded="false" aria-controls="menu">
        <!-- <div style="width:75%;float:left;overflow:hidden;font-weight:bold">A</div> -->
        <!-- <div style="width:25%;float:left"><span class="glyphicon glyphicon-cog"></span></div> -->
        <span class="glyphicon glyphicon-cog"></span>
      </button>

      <!-- Settings menu popup -->
      <div id="menu-container">
        <div id="menu" class="collapse">
          <div class="row">
            <div class="col-md-12">
              <button id="btn-refresh" class="menu-button btn-primary" type="button">
                <b>Päivitä tukipyynnöt&nbsp;<span class="glyphicon glyphicon-refresh"></span></b>
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <button id="btn-export-customers" class="menu-button btn-warning" type="button">
                <b>Vie asiakkaat Freshdeskiin&nbsp;<span class="glyphicon glyphicon-user"></span></b>
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label class="field select">
                <select id="export-customers-list" class="gui-input">
                  <option value="all" selected>(Kaikki)</option>
                  <?php foreach ($customers as $id => $name) : ?>
                    <option value="<?= $id ?>"><?= $name ?></option>
                  <?php endforeach; ?>
                </select>
                <i class="arrow double"></i>
              </label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="well well-sm" id="export-customers-progress"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">

    <!-- Container for ticket rows -->
    <div class="col-md-11">
      <div class="row">
        <div class="col-md-4">
          <div id="ticket-row-1">
          </div>
        </div>
        <div class="col-md-4">
          <div id="ticket-row-2">
          </div>
        </div>
        <div class="col-md-4">
          <div id="ticket-row-3">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
