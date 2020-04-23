<?php

$criteria = new CDbCriteria();
$criteria->select = 'id, tyyppi, yrityksen_nimi, yhteyshenkilo, sahkoposti';
$criteria->condition = "(tyyppi = 'yritys' AND (yrityksen_nimi != '' OR sahkoposti != '')) OR (tyyppi = 'henkilo' AND (yhteyshenkilo != '' OR sahkoposti != ''))";
$customers_results = Asiakkaat::model()->findAll($criteria);
foreach ($customers_results as $c)
  $customers[$c->id] = ($c->tyyppi == 'yritys' ? $c->yrityksen_nimi : $c->yhteyshenkilo) ?: $c->sahkoposti;

?>

<style>
  .ticket {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.5);
    transition: 0.3s;
    border-radius: 5px;
  }

  .ticket-label {
    padding-top: 10px;
  }

  .ticket-description {
    min-height: 40px;
    margin-bottom: 6px;
    padding: 0px 6px;
  }

  .ticket:hover {
    cursor: pointer;
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 1);
  }

  .progbar-outer-box {
    position: fixed;
    width: 1200px;
    height: 22px;
    bottom: 80px;
    left: calc(50% - 480px);
    z-index: 999;
  }

  .progbar-inner-box {
    width: 50%;
    height: 22px;
    margin: 0px auto;
    background: none;
    opacity: 90%;
  }

  .progbar {
    background: -webkit-linear-gradient(left, #33156d 0%, #f282bc 100%);
    border: 2px solid #151414;
    border-radius: 25px;
    display: none;
    transition-duration: 10ms;
  }

  #alert-container {
    position: fixed;
    width: 500px;
    top: 70px;
    left: 240px;
    height: 55px;
    z-index: 9999;
    border-right: 1.5pt solid black;
    border-bottom: 1.5pt solid black;
    border-radius: 25px;
    opacity: 1;
    text-align: center;
    transition: 0.2s;
    display: none;
  }

  #alert-container:hover {
    cursor: pointer;
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 1);
    opacity: 1;
  }

  #alert-container button.close {
    color: white;
    opacity: 0.6;
    transition: 0.1s;
  }

  #alert-container button.close:hover {
    color: black;
    opacity: 1;
  }

  #btn-settings-popup {
    width: 32px;
    border: 2px solid black;
    border-radius: 25px;
  }

  .btn-settings {
    padding: 4px;
  }

  #toggle-menu-container {
    position: relative;
  }

  #toggle-menu {
    position: absolute;
    right: 4px;
    width: 280px;
    /* min-height: 520px; */
    z-index: 9998;
    background-color: whitesmoke;
    /* border: 1px solid #ddd; */
    border: 2px solid #99b7bd;
    margin: 12px 0;
    padding: 12px 8px;
    border-radius: 5px;
    text-align: center;
    transition: 0.2s;
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 1);
  }

  #freshdesk-container {
    width: 100%;
  }

  #spopup-container {
    position: relative;
    width: inherit;
  }

  #spopup {
    position: fixed;
    width: inherit;
    height: 80%;
    /* min-height: 520px; */
    z-index: 9998;
    background-color: whitesmoke;
    /* border: 1px solid #ddd; */
    border: 2px solid #99b7bd;
    margin: 16px 0px;
    padding: 4px;
    border-radius: 5px;
    text-align: left;
    transition: 0.1s;
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 1);
    overflow-y: scroll;
    overflow-x: hidden;
    overflow-wrap: break-word;
  }

  #spopup-header {
    margin: 4px 0 10px;
  }
</style>

<div style="display:none">
  <div class="ticket" id="ticket-base">
    <div class="ticket-body caption text-center" onclick="">
      <!-- onclick="location.href='/index.php/tyovuoroot/freshdesk/id" -->
      <h4 class="ticket-label"><a class="ticket-title" href="#" target="_blank">
          <!-- Title --></a></h4>
      <p><i class="glyphicon glyphicon-user light-red lighter bigger-120"></i>&nbsp;<a class="ticket-customer-link" href="#" target="_blank" style="color:inherit;">
          <!-- Customer Name --></a></p>
      <div class="ticket-description smaller">
        <!-- Description -->
      </div>
    </div>
    <div class="ticket-footer caption card-footer text-center">
      <!-- bg-[color] based on status -->
      <ul class="ticket-footer-list list-inline">
        <!-- text-dark if not answered -->
        <li><i class="people lighter"></i>&nbsp;<i class="ticket-status">
            <!-- Answered/Not Answered, Date --></i></li>
        <li></li>
        <li><i class="glyphicon glyphicon-envelope lighter"></i>&nbsp;<a href="#" style="color:inherit">Vastaa</a></li>
      </ul>
    </div>
  </div>
</div>


<!-- Error alert popup (top-right) -->
<div style="position:relative">
  <div id="alert-container" class="alert fade in bg-danger">
    <button class="close pull-left light" data-dismiss="alert">×</button>
    <span id="alert-text">Tukipyyntöjen haussa tapahtui virhe. Paina tästä lisätiedot.</span>
  </div>
</div>

<!-- Progress bar fixed -->
<div class="progbar-outer-box">
  <div class="progbar-inner-box">
    <div class="progbar progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
    </div>
  </div>
</div>

<div id="freshdesk-container">
  <!-- Full screen popup -->
  <div id="spopup-container">
    <div id="spopup" class="collapse">
      <div class="row">
        <div class="col-md-11">
          <h4 id="spopup-header">&nbsp;</h4>
        </div>
        <div class="col-md-1">
          <button type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div id="spopup-body">&nbsp;</div>
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

    <!-- Right space for popup buttons -->
    <div class="col-md-1">

      <!-- Menu popup button -->
      <div class="row">
        <div class="col-md-12">
          <button id="btn-settings-popup" class="btn-primary pull-right" data-toggle="collapse" data-target="#toggle-menu" aria-expanded="false" aria-controls="toggle-menu">
            <!-- <div style="width:75%;float:left;overflow:hidden;font-weight:bold">A</div> -->
            <!-- <div style="width:25%;float:left"><span class="glyphicon glyphicon-cog"></span></div> -->
            <span class="glyphicon glyphicon-cog"></span>
          </button>
        </div>
      </div>

      <!-- Settings menu popup -->
      <div class="row">
        <div class="col-md-12">
          <div id="toggle-menu-container">
            <div id="toggle-menu" class="collapse">
              <div class="row">
                <div class="col-md-12">
                  <button id="btn-export-customers" class="btn-settings btn-warning" type="button">
                    <b>Vie asiakkaat Freshdeskiin&nbsp;<span class="glyphicon glyphicon-user"></span></b>
                  </button>
                </div>
              </div>
              <div class="row options-row">
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    var rowHeights = [0, 0, 0];

    var drawTicket = function(id, customer, customer_id, status, updated_date, title, description) {
      let obj = $('#ticket-base').clone();
      obj.find('.ticket-title').text('Tukipyyntö: ' + title);
      obj.find('.ticket-customer-link').attr('href', `/index.php/asiakkaat/update?id=${customer_id}`).text(customer);
      obj.find('.ticket-description').text(description);
      // obj.find('.ticket-body').attr('onclick', `location.href='/index.php/tyovuoroot/freshdesk/${id}'`);
      // obj.find('.ticket-body').attr('onclick', `alert(${list_tickets[id]})`);
      obj.find('.ticket-body').on('click', function(e) {
        let data = '',
          val = '',
          emptyKeys = [];
        $.each(Object.keys(list_tickets[id]), function(i, key) {
          val = list_tickets[id][key];
          if (val != null && val.length > 0) {
            // console.log(`${key}: ${val}`);
            if (typeof(val) == "object") {
              val = JSON.stringify(val);
            } else {
              val = val
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
            }

            data += `<b>${key}:</b> ${val}<br>`;
          } else {
            emptyKeys.push(key);
          }
        });

        if (emptyKeys.length > 0) {
          emptyKeys = emptyKeys.join(', ');
          // console.log(`Empty keys: ${emptyKeys}`);
          data += `<br>Empty keys: ${emptyKeys}`;
        }

        let subject = ("subject" in list_tickets[id]) ?
          list_tickets[id].subject : '(ei otsikkoa)';

        $('#spopup-header').html(`Tukipyyntö ${id}: ${subject}`);
        $('#spopup-body').html(`<p>${data}</p>`);
        $('#spopup').collapse("show");
      });

      switch (status) {
        case 2: // Open
          obj.find('.ticket-footer').addClass('bg-warning');
          obj.find('.ticket-footer-list').addClass('text-dark');
          obj.find('.ticket-status').text(`Auki ${updated_date}`);
          break;
        case 3: // Pending
          obj.find('.ticket-footer').addClass('bg-primary');
          obj.find('.ticket-status').text(`Vastattu ${updated_date}`);
          break;
        case 4: // Resolved
          obj.find('.ticket-footer').addClass('bg-success');
          obj.find('.ticket-status').text(`Ratkaistu ${updated_date}`);
          break;
        case 5: // Closed
          obj.find('.ticket-footer').addClass('bg-secondary');
          obj.find('.ticket-status').text(`Suljettu ${updated_date}`);
          break;
      }

      let row = 1;
      if (rowHeights[1] < rowHeights[0])
        row = 2;
      if (rowHeights[2] < rowHeights[row - 1])
        row = 3;
      $('#ticket-row-' + row).append(obj);
      rowHeights[row - 1] += obj.height();
    };

    let list_tickets = {},
      list_request_underway = false,
      list_previous_page = 0,
      list_end_reached = false;

    var list = async function(page = 0) {
      if (list_request_underway || list_end_reached) return;
      if (page <= 0)
        page = list_previous_page + 1;
      progbar();

      $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/freshdesk?page=${page}`, {

        // xhrFields: {
        //   onprogress: function(e) {
        //     let response = e.currentTarget.response.substring(list_previous_length);
        //     list_previous_length = e.currentTarget.response.length; // let list_previous_length = 0;
        //     console.log(response);
        //     let parsed = JSON.parse(response);
        //     let customer_id = Math.floor(Math.random() * 10000); // TEMP, internal, for link.
        //     $.each(parsed, function(i, t) {
        //       drawTicket(t.id, t.requester.name, customer_id, t.status, formatUtcString(t.updated_at), t.subject, t.description_text);
        //     });
        //   }
        // },

        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          alert(xhr.responseText);
        },

        success: function(data) {
          console.log(`Received response, length: ${data.length}`);
          let parsed = null;

          try {
            parsed = JSON.parse(data);
          } catch (e) {
            console.log(`Failed to parse response JSON. Error: ${e}\nResponse data: ${data}`);
          }

          if (typeof(parsed) != "object") {
            console.log("Parsed data is unusable (not an object).");

          } else if (parsed.length == 0) {
            list_end_reached = true;
            console.log("Reached end of ticket data");

          } else if ("errors" in parsed) {
            console.log(`Errors in response: ${parsed.errors}\nResponse data: ${data}`);
            if ("eod" in parsed && parsed.eod)
              list_end_reached = true;

          } else if ("eod" in parsed && parsed.eod) {
            console.log(`Received \{eod=true\}, assuming end of data. Response: ${data}`);
            list_end_reached = true;

          } else {
            $.each(JSON.parse(data), function(i, t) {
              if (typeof(t) != "object") {
                console.log("Invalid content inside response data (not an object): " + t);
              } else {
                let customer_id = Math.floor(Math.random() * 10000); // TEMP
                drawTicket(t.id, t.requester.name, customer_id, t.status, formatUtcString(t.updated_at), t.subject, t.description_text);
                list_tickets[t.id] = t;
              }
            });
          }
        },

        complete: function() {
          if (list_end_reached || !listFetchIfScrolled())
            progbarStop();
          list_request_underway = false;
          list_previous_page = page;
        }
      });
    };

    /**
     * Format UTC string to a more eye-friendly date string.
     */
    var formatUtcString = function(utc) {
      let time = Date.parse(utc);
      const timeFormat = new Intl.DateTimeFormat('fi-FI', {
        timeZone: 'Europe/Helsinki',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
      });
      let [{
        value: mo
      }, , {
        value: da
      }, , {
        value: ye
      }, , {
        value: ho
      }, , {
        value: mi
      }] = timeFormat.formatToParts(time);
      return `${da}.${mo}.${ye} ${ho}:${mi}`;
    };

    /**
     * Request next page of tickets if a request is not currently active and
     * window is scrolled to bottom.
     */
    var listFetchIfScrolled = function() {
      if (!list_request_underway && $(window).scrollTop() == $(document).height() - $(window).height()) {
        list();
        return true;
      }
      return false;
    };

    /**
     * Hook scroll to a check of if it's time to request more tickets.
     */
    $(window).scroll(function() {
      listFetchIfScrolled();
    });

    var errorText = '',
      errorData = '';

    var showError = function(header, text, data = '') {
      errorText = text;
      errorData = data;
      $('#alert-text').text((header.length > 0) ? header : 'Pyynnössä tapahtui virhe.');
      $('#alert-container').css('display', 'block');
    };

    $('#alert-container').on('click', function(e) {
      e.preventDefault();
      if (errorText != null && errorText.length > 0)
        console.log(errorText);
      if (errorData != null && errorData.length > 0)
        console.log(errorData);
    });

    var adjustDynamicElements = function() {
      let containerWidth = $('#freshdesk-container').width();
      $('#spopup')
        .css('transition', '0s')
        .width(containerWidth * 0.8 + 'px')
        .css({
          'margin-left': containerWidth * 0.1 + 'px',
          'height': Math.max(document.documentElement.clientHeight, window.innerHeight || 0) * 0.7 + 'px',
          'transition': '0.2s'
        });
    };

    $(window).on('resize', function() {
      adjustDynamicElements();
    });

    $('#spopup button.close').on('click', function(e) {
      $('#spopup').collapse("hide");
    });

    /** Hide collapsibles when clicked elsewhere. */
    $(document).mouseup(function(e) {
      var options_div = $('#toggle-menu');
      if (options_div.attr('aria-expanded') && !options_div.is(e.target) && options_div.has(e.target).length === 0)
        options_div.collapse("hide");
      var spopup_div = $('#spopup');
      if (spopup_div.attr('aria-expanded') && !spopup_div.is(e.target) && spopup_div.has(e.target).length === 0 && $(e.target).parents('.ticket-body').length == 0)
        spopup_div.collapse("hide");
    });

    $('#btn-export-customers').on('click', function(e) {
      e.preventDefault();
      let selection = $('#export-customers-list').val();
      if (selection.length == 0)
        return;
      $(this).attr('disabled', 'disabled');
      $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/freshdesk`, {

        type: 'POST',
        data: {
          export: selection
        },

        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          showError('Virhe asiakkaiden lähettämisessä Freshdeskiin.', '', xhr.responseText);
        },

        success: function(data) {
          console.log(data);
          let parsed = null;

          try {
            parsed = JSON.parse(data);
          } catch (e) {
            showError('Virhe asiakkaiden lähettämisessä Freshdeskiin.', `Failed to parse response JSON.`, e);
          }

          if (typeof(parsed) != "object" || parsed == null) {
            showError('Virhe asiakkaiden lähettämisessä Freshdeskiin.', 'Parsed data is unusable (not an object).', parsed);

          } else if ("errors" in parsed && parsed.errors.length > 0) {
            let error_text = `Asiakkaiden viemisessä Freshdeskiin tapahtui virheitä. Asiakkaita luotu: ${parsed.created_count}, päivitetty: ${parsed.updated_count}\n\nVirheet:\n`;

            $.each(parsed.errors, function(k,v) {
              if ("asiakas_id" in v)
                error_text += `(asiakas ${v.asiakas_id}: ${v.asiakas}): `;
              error_text += `${v.text} (${v.description})\n`;
            });

            error_text += "\n\n\nEdistynyt tieto:\n" + data;
            alert(error_text);

          } else {
            alert(`Asiakkaita luotu: ${parsed.created_count}, päivitetty: ${parsed.updated_count}`);
          }
        },
        complete: function() {
          $('#btn-export-customers').removeAttr('disabled');
        }
      });
    });

    //*--------------------------------------------------------------------------
    //* Progress Bar
    //*--------------------------------------------------------------------------

    /** Sleeps for ms milliseconds. Use with await. */
    var sleep = function(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    };

    /** @type {boolean} Indicates if the progress bar is currently active. */
    var progbarActive = false;

    /** @type {boolean} Can be set to true to tell progress bar to exit early. */
    var progbarShouldStop = false;

    /** @type {number} Elapsed milliseconds during previous animation. */
    var progbarPrevElapsed = 1000;

    /**
     * Activate progress bar and grow it to 100 in approximately 10 seconds.
     * Counts up until it reaches near 100 or is stopped. After finishing, the
     * bar is hidden again.
     */
    var progbar = async function() {
      if (progbarActive) return;
      progbarClean(true);

      // increment: ms / (ms/interval) * (100/ms) / 100  : (bring to 0-1 float value).
      let n = 0,
        startTime = (new Date()).getTime(),
        repeatsTotal = progbarPrevElapsed / 15,
        repeats = 0,
        elapsedTime = 0,
        delayedTime = 0, // time set after hitting 90%
        increment = progbarPrevElapsed / repeatsTotal * (100 / progbarPrevElapsed) / 100,
        adjusted = 0;

      console.log(`previous time: ${progbarPrevElapsed}, increment: ${increment} (interval 15ms)`);

      while (1 - n > 0.01) {
        if (progbarShouldStop)
          break;
        elapsedTime = new Date().getTime - startTime;

        // Slow down increment because time taken per request is not predictable.
        switch (true) {
          case (adjusted == 0 && n > 0.40):
            increment /= 1.20;
            adjusted++;
            break;
          case (adjusted == 1 && n > 0.45):
            increment /= 1.20;
            adjusted++;
            break;
          case (adjusted == 2 && n > 0.50):
            increment /= 1.20;
            adjusted++;
            break;
          case (adjusted == 3 && n > 0.55):
            increment /= 1.20;
            adjusted++;
            break;
          case (adjusted == 4 && n > 0.60):
            increment /= 1.30;
            adjusted++;
            break;
          case (adjusted == 5 && n > 0.65):
            increment /= 1.30;
            adjusted++;
            break;
          case (adjusted == 6 && n > 0.70):
            increment /= 1.30;
            adjusted++;
            break;
          case (adjusted == 7 && n > 0.75):
            increment /= 1.50;
            adjusted++;
            break;
          case (adjusted == 8 && n > 0.80):
            increment /= 2.50;
            adjusted++;
            break;
          case (adjusted == 9 && n > 0.85):
            increment /= 3.50;
            adjusted++;
            break;
          case (adjusted == 0 && n > 0.90):
            increment /= 4.50;
            adjusted++;
            delayedTime = new Date().getTime();
            break;
          case (n > 0.92 && elapsedTime - delayedTime > 6000):
            console.log(`progbar exiting due to delay; 6 seconds elapsed after 90%, total elapsed: ${elapsedTime}`);
            progbarStop();
            break;
        }

        repeats++;
        n += increment - (increment * n / 2);
        let ival = Math.trunc(n * 100);
        $('div.progbar').attr('aria-valuenow', ival).css('width', ival + '%');
        await new Promise(r => setTimeout(r, 15));
      }

      progbarPrevElapsed = Math.max(200, ((new Date()).getTime() - startTime));
      progbarActive = false;
      await progbarClean(false);
    };

    /**
     * Tells the progress bar to stop if it's active.
     */
    var progbarStop = function() {
      progbarShouldStop = (progbarActive == true);
    };

    /**
     * Resets progress bar to base state of active or stopped.
     * @param {boolean} active Whether to set the bar as active or stopped.
     */
    var progbarClean = async function(active = false) {
      if (active) {
        if (!progbarActive) {
          progbarActive = true;
          progbarShouldStop = false;
          $('div.progbar').attr('aria-valuenow', 0).css({
            width: 0,
            display: 'block',
            border: '2px solid #151414'
          });
        }
      } else if (progbarActive) {
        progbarShouldStop = true;
      } else {
        $('div.progbar').css({
          width: 0,
          display: 'none',
          border: 'none'
        });
        progbarActive = false;
        progbarShouldStop = false;
      }
    };

    //*--------------------------------------------------------------------------
    //* Tests
    //*--------------------------------------------------------------------------

    /** Tests progress bar with various timeouts (10 total). */
    var progbarTest = async function() {
      const msarr = [1600, 400, 890, 3100, 890, 990, 100, 750, 2100, 1550];
      let timeout = null;
      for (let i = 0; i < msarr.length; i++) {
        timeout = setTimeout(() => progbarStop(), msarr[i]);
        await progbar();
        clearTimeout(timeout);
      }
    };

    /** Populate the ticket rows with random data to preview. */
    var ticketPreviewPopulate = function(count = 20) {

      // Test ticket data. 8 items each, forming random ticket properties.
      const TICKET_TEST_NAMES = ['Testiasiakas A', 'Ossi Meikäläinen', 'Jouni A.', 'Antero Mertasaari', 'Jokupulju Oy', 'Tuntematon', 'Ninja Warrior', 'Crokodile Dundee'];
      const TICKET_TEST_DATES = ['09.01.2019 11:36', '06.03.2019 15:34', '07.05.2019 16:25', '18.08.2019 09:27', '04.09.2019 17:32', '01.01.2020 18:55', '15.01.2020 17:41', '03.04.2020 08:24'];
      const TICKET_TEST_TITLES = ['Lorem ipsum dolor sit amet', 'Phasellus rhoncus erat sed', 'Ut rutrum, arcu sed', 'Sed pretium nisi dui', 'Quisque bibendum, dui non', 'In lacinia felis et mi.', 'Suspendisse quis quam velit.', 'In vehicula commodo augue', ];
      const TICKET_TEST_TEXTS = [
        'mollis justo. Maecenas maximu id, dapibus eu arcu. parturient montes, nascetur ridiculus mus. Ut viverra molestie mi, accumsan dignissim odio suscipit ac. Nullam at blandit dolor, sit amet commodo nibh.',
        'Praesent lacinia cursus sem quis hendrerit. Duis in mi auctor, tincidunt elit et, ondimentum dui, non hendndrerit at urna sit amet, aliquet finibus diam.',
        'libero arcu finibus ipsum, eu convallis leo metus ut velit. Aliquam pellentesque tempus nisl sed egestas. Proin a purus a elit fermentum laoreet. Nullam non risus sit amet orci pellentesque mattis ac a neque. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam ultricies tortor dolor, a laoreet ex tincidunt quis.',
        'justo convallis vitae. Nulla sed nulla elit. Etiam ultricies sodales mattis. Donec euismod, odio quis dignimi, quis pellentesque tellus ante nec lectus. Donec sem neque, eleifend malesuada nibh eu, efficitur vehicula lectus. Sed accumsan, eros viverra sodales accumsan, ex ex posuere nisi, vitae euismod arcu eros ultrices sapien.',
        'ullamcorper lobortis enim urna at ipsum. Integer vel aliquet ligula, ac accumsan neque. Cras cursus sodales erat quis consectetur. Cras vestibulum ultricies orci congue porta. In posuere velit magna, nec venenatis urna suscipit sed. Vestibulum congue libero mi, in consectetur nulla gravida blandit. Vivamus sollicitudin felis dignissim faucibus aliquam. Nullam eros lectus, ornare quiss lacus a tempus.',
        'Mauris posuere urna posuere, vulputate elit id, gravida nisl. Nullam purus risus, vulputate nec libero ut, faucibus lobortis diam. Curabitur vel sem eu ex efficitur egestas ac ut diam. Donec ut tempor nisi. Vivamus orci dui, elementum ut nunc vitae, imperdiet semper mauris. Vivamus porttitor elementum lorem, nec volutpat justo cursus vel. Praesent nec nulla et lorem varius bibendum. Quisque at semaesent rutrum magna nulla, vitae fermentum turpis ultrices vitae.',
        'rhoncus felis tempor vitae. Nulla euismod nisl quis diam fringilla dignissim. Ut felis magna, fermentum sit amet felis ac, varius fringilla dui. Mauris vitae tortor lacinia, semper mi nec, mollis quam. Etiam luctus ligula ac ligula elementum, ornare faucibus nisi pulvinar. Etiam orci lectus, faucibus sed sollicitudin et, luctus ac massa. In rhoncus vitae dolor quis laoreet. Praesent tincidunt tula tristique. Donec lectus felis, eleifend vitae libero eu, vestibulum consequat enim.',
        'turpis at purus tempus pellentesque id id lorem. Ut a quam ornare, hendrerit felis e dignissim ut. Curabitur ultrices interdum purus, quis fringilla enim condimentum sed. '
      ];


      // Pick random elements from each test array and form tickets.
      for (let i = 0; i < count; i++) {
        let rand = Math.random(),
          customer = TICKET_TEST_NAMES[Math.floor(rand * 8)],
          title = TICKET_TEST_TITLES[Math.floor(rand * 8)],
          desc = TICKET_TEST_TEXTS[Math.floor(rand * 8)],
          date = TICKET_TEST_DATES[Math.floor(rand * 8)];
        drawTicket(i, customer, Math.floor(rand * 5000), Math.floor(rand * 4) + 2, date, title, desc);
      }
    };

    //*--------------------------------------------------------------------------
    //* Initialized
    //*--------------------------------------------------------------------------

    adjustDynamicElements();

    // Fetch first set of tickets.
    list();
    // ticketPreviewPopulate();
    // progbarTest();
  });
</script>