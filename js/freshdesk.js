
$(function () {

  //*------------------------------------------------------------------------------------------------
  //* Variables
  //*------------------------------------------------------------------------------------------------
  //#region Variable Update Helpers

  /**
   * Get selected statuses in the top-bar filter by status -selection as JSON.
   *
   * This might not always reflect the currently active filter, as it returns
   * current selection whether or not it has been applied.
   *
   * @return {String}
   * Selections encoded with JSON, or empty string on no selections/failure.
   */
  var getTicketFilterStatusSelection = function () {
    const statuses = $('#filter-status-multiselect').val();
    return (statuses != null && statuses.length > 0 ? JSON.stringify(statuses) : '');
  };

  /**
   * Get selected order by value in the top-bar.
   *
   * This might not always reflect the currently active selection, as it returns
   * current selection whether or not it has been applied.
   *
   * @return {String}
   * Current selection in the order by -selection in the top-bar.
   */
  var getTicketOrderBySelection = function () {
    return $('#order-by-select').val();
  };

  /**
   * Get selected order type value in the top-bar.
   *
   * This might not always reflect the currently active selection, as it returns
   * current selection whether or not it has been applied.
   *
   * @return {String}
   * Current selection in the order type -selection in the top-bar.
   */
  var getTicketOrderTypeSelection = function () {
    return $('#order-type-select').val();
  };

  //#endregion
  //#region General Variables

  /**
   * Base URL for the admin environment in Freshdesk, without a trailing slash.
   * The domain should be provided for the view from PHP.
   * @type {String}
   */
  const freshdeskUrl = 'https://' + $('#domain-label').val() + '.freshdesk.com/a';

  //#endregion
  //#region Error popup variables

  /**
   * Variable to hold the text of previous error, for when the alert is clicked.
   * @type {String}
   */
  let errorText = '';

  /**
   * Variable to hold the data of previous error, for when the alert is clicked.
   * @type {String}
   */
  let errorData = '';

  //#endregion
  //#region listTickets() variables

  /**
   * Array of heights for each row, for inserting tickets to correct position.
   * @type {Object}
   */
  let rowHeights = [0, 0, 0];

  /**
   * JSON array representing the statuses to request. When changed, tickets
   * should be reset.
   * @type {String}
   */
  let listTicketsFilterStatuses = getTicketFilterStatusSelection();

  /**
   * JSON array representing the statuses to request. When changed, tickets
   * should be reset.
   * @type {String}
   */
  let listTicketsOrderBy = getTicketOrderBySelection();

  /**
   * Tickets order type (desc/asc). When changed, tickets should be reset.
   * @type {String}
   */
  let listTicketsOrderType = getTicketOrderTypeSelection();

  /**
   * Last page number requested by listTickets().
   * @type {Boolean}
   */
  let listTicketsLastPage = 0;

  /**
   * Flag for listTickets() requests to not overlap.
   * @type {Boolean}
   */
  let listTicketsRequesting = false;

  /**
   * Whether listTickets() has reached end of ticket listing.
   * @type {Boolean}
   */
  let listTicketsEndReached = false;

  /**
   * Flag that tells listTickets() to throw away possible upcoming data.
   * @type {Boolean}
   */
  let listTicketsShouldDump = false;

  //#endregion

  //*------------------------------------------------------------------------------------------------
  //* Tickets
  //*------------------------------------------------------------------------------------------------
  //#region Tickets

  /**
   * Get a list of tickets over the API.
   *
   * @param {Number} page
   * Page number to request.
   *
   * @param {Boolean} refresh
   * Whether to force refresh data over API.
   */
  var listTickets = async function (page = 0, refresh = false) {

    // Return if already requesting or if end has been reached.
    if (listTicketsRequesting || listTicketsEndReached)
      return;

    // Get next page by default.
    if (page <= 0)
      page = listTicketsLastPage + 1;

    // Use negative page in request if refresh, to tell action to force refresh.
    const queryPage = (refresh) ? -page : page;

    // Start progress bar.
    progressBar();

    // Request tickets from the Freshdesk action.
    $.ajax(`${location.protocol}//${location.host}/index.php/asiakkaat/freshdesk`, {

      type: 'POST',
      data: {
        page: queryPage,
        filter_statuses: listTicketsFilterStatuses,
        order_by: listTicketsOrderBy,
        order_type: listTicketsOrderType
      },

      error: function (xhr, status, error) {

        // Print error only if listTicketsShouldDump !== true.
        if (true !== listTicketsShouldDump) {
          alert(xhr.responseText);
          console.log(xhr.responseText);
        }
      },

      success: function (data) {

        // Check if this data should be dumped.
        if (listTicketsShouldDump) {
          return;
        }

        // console.log(data);
        console.log(`Received response, length: ${data.length}`);

        // Try parse response JSON.
        let parsed = null;
        try {
          parsed = JSON.parse(data);
        } catch (e) {
          console.log(`Failed to parse response JSON. Error: ${e}\nResponse data: ${data}`);
        }

        if (typeof (parsed) != "object") {

          // Parsing failed. Notify log and let it go.
          console.log("Parsed data is unusable (not an object).");

        } else if (parsed.length == 0) {

          // Data is empty, and since parsing didn't fail, end has been reached.
          listTicketsEndReached = true;
          console.log("Reached end of ticket data");

        } else if ("errors" in parsed) {

          // Errors happened during request.
          console.log(`Errors in response: ${parsed.errors}\nResponse data: ${data}`);

          // Check if end of data reached or invalid page requested.
          if ("eod" in parsed && parsed.eod) {
            listTicketsEndReached = true;
          }
        } else if ("eod" in parsed && parsed.eod) {

          // Response contains end-of-data, but no errors. This is (maybe) odd.
          console.log(`Received \{eod=true\}, assuming end of data. Response: ${data}`);
          listTicketsEndReached = true;
        } else {

          // Everything is normal; draw tickets based on response data.
          $.each(parsed, function (i, t) {
            if (typeof (t) != "object") {
              console.log("Invalid content inside response data (not an object): " + t);
            } else {
              drawTicket(t.id, t, t.unique_external_id || 0);
            }
          });
        }
      },

      complete: function () {

        // Check if last data was dumped, in which case, list should stop.
        if (listTicketsShouldDump) {

          // Latest data is marked to be dumped; reset flags and stop.
          listTicketsRequesting = false;
          listTicketsShouldDump = false;
          requestStopProgressBar();
        } else {

          // Set default values for next requests.
          listTicketsRequesting = false;
          listTicketsLastPage = page;

          // Request more tickets if not end of data and page is scrolled.
          if (listTicketsEndReached || !listTicketsIfScrolled()) {
            requestStopProgressBar();
          }
        }
      }
    });
  };

  /** Empty the tickets container. */
  var listTicketsReset = async function () {
    if (listTicketsRequesting) {
      listTicketsShouldDump = true;
    }

    rowHeights = [0, 0, 0];
    listTicketsLastPage = 0;
    listTicketsRequesting = false;
    listTicketsEndReached = false;

    $('#ticket-row-1').empty();
    $('#ticket-row-2').empty();
    $('#ticket-row-3').empty();
  }

  /**
   * Request next page of tickets if a request is not currently active and
   * window is scrolled to bottom.
   *
   * @return {Boolean}
   * True if more tickets were requested; otherwise, false.
   */
  var listTicketsIfScrolled = function () {

    // Check current scroll when tickets are not currently being requested.
    const shouldRequestTickets =
      !listTicketsRequesting &&
      $(window).scrollTop() == $(document).height() - $(window).height();

    // Get more tickets if conditions require.
    if (shouldRequestTickets) {
      listTickets();
    }

    return shouldRequestTickets;
  };

  /**
   * Draw a ticket.
   *
   * The ticket will be added to the shortest of the rows. The various divs with
   * specific classes inside the ticket skeleton are replaced with actual data.
   *
   * @param {Number} id
   * Ticket ID in Freshdesk.
   *
   * @param {Object} ticket
   * Ticket data, including at least:
   *   requester: Object >> { name: String, ... }
   *       Requester name: Text for the link to customer page under the title.
   *   status: Number
   *       Status (2-7); affects the colors on the ticket body. Options:
   *         2: Open, 3: Pending, 4: Resolved, 5: Closed,
   *         6: Waiting on customer, 7: Waiting for third party
   *   updated_at: String
   *       Date string for when the ticket was last updated in UTC format for formatUtcString().
   *   subject: String
   *       Ticket subject line.
   *   description_text: String
   *       Ticket description text.
   *
   * @param {Number} customer_id
   * Local customer ID. If >0, links to /index.php/asiakkaat/update?id=<id>
   * (local customer update form); otherwise, the link is disabled.
   */
  var drawTicket = function (id, ticket, customer_id) {

    // Link to the ticket in Freshdesk.
    const ticketLink = `${freshdeskUrl}/tickets/${id}`;

    // Get a blank ticket div from the base.
    const obj = $('#ticket-base').clone();

    // Formatted date string for when the ticket was last updated.
    const updatedDate = formatUtcString(ticket.updated_at)

    // Set ID for this ticket div.
    obj.attr('id', 'ticket-' + id);

    // Set subject and description text, and respond link.
    obj.find('.ticket-subject').text('Tukipyyntö: ' + ticket.subject).attr('href', ticketLink);
    obj.find('.ticket-summary').text(ticket.description_text);
    obj.find('.ticket-respond-link').attr('href', ticketLink);

    // Set customer text/link.
    if (customer_id > 0) {
      // customer_id provided; update customer link.
      obj.find('.ticket-customer-link').attr('href', `/index.php/asiakkaat/update?id=${customer_id}`).text(ticket.requester.name);
    } else {
      // customer_id not provided; remove link.
      obj.find('.ticket-customer-link').attr('href', '#').removeAttr('target').text(ticket.requester.name);
    }

    // Set hidden id/data for the ticket, to display full information (debug).
    obj.find('.ticket-hidden-id').text(id);
    obj.find('.ticket-hidden-data').text(JSON.stringify(ticket));

    // Adjust display based on status.
    switch (ticket.status) {
      case 2: // Open
        obj.find('.ticket-footer').addClass('bg-warning');
        obj.find('.ticket-footer-list').addClass('text-dark');
        obj.find('.ticket-status').text(`Vastaamatta ${updatedDate}`);
        break;
      case 3: // Pending
        obj.find('.ticket-footer').addClass('bg-primary');
        obj.find('.ticket-status').text(`Vastattu ${updatedDate}`);
        break;
      case 4: // Resolved
        obj.find('.ticket-footer').addClass('bg-success');
        obj.find('.ticket-status').text(`Ratkaistu ${updatedDate}`);
        break;
      case 5: // Closed
        obj.find('.ticket-footer').addClass('bg-secondary');
        obj.find('.ticket-status').text(`Suljettu ${updatedDate}`);
        break;
      case 6: // Waiting on customer
        obj.find('.ticket-footer').addClass('bg-primary');
        obj.find('.ticket-status').text(`Odottaa Asiakasta ${updatedDate}`);
        break;
      case 7: // Waiting for third party
        obj.find('.ticket-footer').addClass('bg-primary');
        obj.find('.ticket-status').text(`Odottaa Tietoa ${updatedDate}`);
        break;
    }

    // Select the shortest row for this ticket.
    let row = 1;
    if (rowHeights[1] < rowHeights[0])
      row = 2;
    if (rowHeights[2] < rowHeights[row - 1])
      row = 3;

    // Append to shortest row, and update row height with this ticket's height.
    $('#ticket-row-' + row).append(obj);
    rowHeights[row - 1] += obj.height();
  };

  //#endregion

  //*------------------------------------------------------------------------------------------------
  //* UI
  //*------------------------------------------------------------------------------------------------
  //#region UI

  /** Adjust dynamic elements when the window is resized. */
  $(window).on('resize', function () {
    adjustDynamicElements();
  });

  /** Check if more tickets should be requested when the page is scrolled. */
  $(window).scroll(function () {
    listTicketsIfScrolled();
  });

  /** Hide collapsibles when clicked elsewhere. */
  $(document).mouseup(function (e) {
    const optionsPopup = $('#menu');
    const optionsPopupShouldHide =
      optionsPopup.attr('aria-expanded') &&
      !optionsPopup.is(e.target) &&
      optionsPopup.has(e.target).length === 0;

    if (optionsPopupShouldHide) {
      optionsPopup.collapse("hide");
    }

    const fullscreenPopup = $('#fullscreen-popup');
    const fullscreenPopupShouldHide =
      fullscreenPopup.attr('aria-expanded') &&
      !fullscreenPopup.is(e.target) &&
      fullscreenPopup.has(e.target).length === 0 &&
      $(e.target).parents('.ticket-body').length == 0;

    if (fullscreenPopupShouldHide) {
      fullscreenPopup.collapse("hide");
    }
  });

  /** Click handler for the alert popup; log extra data on click. */
  $('#alert-container').on('click', function (e) {
    e.preventDefault();
    if (errorText != null && errorText.length > 0)
      console.log(errorText);
    if (errorData != null && errorData.length > 0)
      console.log(errorData);
  });

  /** Click handler for the close button on the fullscreen popup. */
  $('#fullscreen-popup button.close').on('click', function (e) {
    $('#fullscreen-popup').collapse("hide");
  });

  /** Initialize the status filter multiselect. */
  $('#filter-status-multiselect').multiselect({
    includeSelectAllOption: true,
    buttonClass: 'btn btn-default top-bar-select',
    selectAllText: 'Valitse kaikki',
    buttonText: function (options, select) {
      switch (true) {
        case (options.length === 0):
          return '(valitse)';
        case (options.length === 6):
          return '(kaikki tilat)';
        case (options.length > 3):
          return `(${options.length} tilaa valittu)`;
        default:
          let labels = [];
          options.each(function () {
            if ($(this).attr('label') !== undefined) {
              labels.push($(this).attr('label'));
            } else {
              labels.push($(this).html());
            }
          });
          return labels.join(', ') + '';
      }
    }
  });
  $('#filter-status-multiselect').multiselect('selectAll', false);
  $('#filter-status-multiselect').multiselect('updateButtonText');

  /** Click handler for the top-bar update -button to apply filters/order. */
  $('#controls-update-button').on('click', function (e) {
    e.preventDefault();
    listTicketsReset();
    listTicketsFilterStatuses = getTicketFilterStatusSelection();
    listTicketsOrderBy = getTicketOrderBySelection();
    listTicketsOrderType = getTicketOrderTypeSelection();
    // console.log(listTicketsFilterStatuses);
    // console.log(listTicketsOrderBy);
    // console.log(listTicketsOrderType);
    listTickets(1);
  });

  /** Click handler for the Refresh Tickets -button. */
  $('#btn-refresh').on('click', function (e) {
    e.preventDefault();
    listTicketsReset();
    listTickets(0, true);
  });

  /** Click handler for the Export Customers -button. */
  $('#btn-export-customers').on('click', function (e) {
    e.preventDefault();

    // Get the selection. This will be the customer ID, or 'all'.
    const selection = $('#export-customers-list').val();

    if (selection.length == 0)
      return;

    // Disable the export button for until finished.
    $(this).attr('disabled', 'disabled');

    // Show the progress text div.
    $('#export-customers-progress').css('display', 'block');

    // Length of previous response, for parsing incoming data on flush.
    let previous_length = 0;

    // Request the Freshdesk action with export function.
    $.ajax(`${location.protocol}//${location.host}/index.php/asiakkaat/freshdesk`, {

      type: 'POST',
      data: { export: selection },

      xhrFields: {

        // Catch data when PHP flushes output on progress.
        onprogress: function (e) {

          // Update progress div only when exporting all.
          if (selection != 'all')
            return;

          // Get latest response, save previous length and log.
          const response = e.currentTarget.response.substring(previous_length);
          previous_length = e.currentTarget.response.length;
          console.log(response);

          if (response.length == 0)
            return;

          // Try and parse this data. This might fail from time to time when the
          // period between flushes is too short, which causes the output to
          // have the output of multiple flushes, resulting in invalid JSON.
          let parsed = null;
          try {
            parsed = JSON.parse(response);
          } catch (e) {
            console.log("Unable to parse output: " + response);
            return;
          }

          // Check if created_count in parsed data, meaning that we are done.
          if ('created_count' in parsed) {
            exportCustomersFinish(parsed);
          } else if ('current' in parsed) {

            // Update progress.
            $('#export-customers-progress').text(`Viety: ${parsed['current']} / ${parsed['total']}`);
          }
        }
      },

      error: function (xhr, status, error) {
        console.log(xhr.responseText);
        showError('Virhe asiakkaiden lähettämisessä Freshdeskiin.', '', xhr.responseText);
      },

      success: function (data) {
        console.log(data);
        $('#export-customers-progress').text = `Valmis`;

        // Get latest response, save previous length and log.
        let response = data.substring(previous_length);
        previous_length = data.length;
        console.log(response);

        if (response.length == 0)
          return;

        // Try and parse this data. This might fail from time to time when the
        // period between flushes is too short, which causes the output to
        // have the output of multiple flushes, resulting in invalid JSON. Also,
        // onProgress might have handled this output already, which is okay.
        let parsed = null;
        try {
          parsed = JSON.parse(response);
        } catch (e) {
          console.log("Unable to parse output: " + data);
          return;
        }

        // Finalize operation if parsing was successful.
        if (parsed != null && typeof (parsed) == "object") {
          exportCustomersFinish(parsed);
        }
      }
    });
  });

  /**
   * Open ticket in Freshdesk when ticket body is clicked. When in testing
   * environment, debug information should be opened instead (TODO).
   */
  $('div').delegate('.ticket', 'click', function () {

    // Return on clicked, otherwise set clicked=true, to prevent multiple calls.
    if ($(this).data('clicked')) {
      return;
    } else {

      // Loop each .ticket to set clicked = false.
      $('.ticket').each(function (index) {
        $(this).data('clicked', false);
      });

      // Set current element clicked = true.
      $(this).data('clicked', true);
    }

    // Open ticket link in new tab.
    return openNewTab(ticketLink);


    //* Code for opening debug information; leaving code here for future reference.

    // Get JSON ticket data from the hidden div.
    const ticketDataJson = $(this).find('.ticket-hidden-data').text();
    let ticketData = null;

    // Parse JSON from raw data.
    if (ticketDataJson.length == 0) {
      console.log(`Ticket body was clicked, but the ticket is missing JSON ticket data.`);
      return false;
    } else {
      try {
        ticketData = JSON.parse(ticketDataJson);
      } catch {
        console.log(`Ticket body was clicked, but ticket data was unable to be parsed from JSON:\n${ticketDataJson}`);
        return false;
      }
    }

    // Get raw ID data from the hidden div and try parse int.
    const rawId = $(this).find('.ticket-hidden-id').text();
    let id = parseInt(rawId);

    // Check for invalid data.
    if (isNaN(id) || typeof (id) !== 'number') {

      // Parsing failed; set ID to -1.
      id = -1;

      // Use subject for identifier since ID was not parsed.
      console.log(`
        Generating debug information for ticket with subject: ${ticketData.subject}.
        (Missing or invalid ID on ticket; using -1. Raw data: ${rawId})
      `.trimMultiline());
    } else {

      // ID was parsed; log start message.
      console.log(`Generating debug information for ticket ID ${id}.`);
    }

    // HTML character escape map for escaping strings in following loop.
    const escmap = {
      '&': "&amp;",
      '<': "&lt;",
      '>': "&gt;",
      '"': "&quot;",
      "'": "&#039;"
    };

    // Builder string for data for the popup screen.
    let popupData = '';

    // List of empty keys (values are not individually listed).
    let emptyKeys = [];

    // Loop values on parsed ticket data object and process.
    $.each(Object.keys(ticketData), function (i, key) {

      // Current value in the loop.
      let val = ticketData[key];

      // Check whether this value is empty or not.
      const valueIsEmpty = val == null ||
        (typeof (val) == "string" && val.length > 0) ||
        (typeof (val) == "object" && Object.keys(val).length > 0);

      // Prepare non-empty values.
      if (false !== valueIsEmpty) {
        if (typeof (val) == "object") {

          // JSON-encode non-empty objects.
          val = JSON.stringify(val);

        } else if (typeof (val) == "string") {

          // Replace HTML entities with escaped characters.
          for (const [emkey, emchar] of Object.entries(escmap)) {
            val = val.replace(new RegExp(emkey, 'g'), emchar);
          }
        }

        // Add this value to popup data.
        popupData += `<b>${key}:</b> ${val}<br>`;
      } else {

        // Value is empty; add to the list of empty keys.
        emptyKeys.push(key);
      }
    });

    // Add empty keys item to popup data.
    if (emptyKeys.length > 0) {
      emptyKeys = emptyKeys.join(', ');
      popupData += `<br>Empty keys: ${emptyKeys}`;
    }

    $('#fullscreen-popup-header').html(`Tukipyyntö ${id}: ${ticketData.subject || '(ei otsikkoa)'}`);
    $('#fullscreen-popup-body').html(`<p>${popupData}</p>`);
    $('#fullscreen-popup').collapse("show");
  });

  //#endregion

  //*------------------------------------------------------------------------------------------------
  //* Events
  //*------------------------------------------------------------------------------------------------
  //#region Events

  /**
   * Show an error with the top-left error popup.
   *
   * @param {String} header
   * String displayed on the popup.
   *
   * @param {String} text
   * Error text, logged/displayed when clicked.
   *
   * @param {String} data
   * Error data, logged/displayed when clicked.
   */
  var showError = function (header, text, data = '') {
    errorText = text;
    errorData = data;
    $('#alert-text').text((header.length > 0) ? header : 'Pyynnössä tapahtui virhe.');
    $('#alert-container').css('display', 'block');
  };

  /**
   * Adjust dynamic elements, e.g. when the window is resized.
   */
  var adjustDynamicElements = function () {

    // Calculate new values for the fullscreen popup.
    const containerWidth = $('#main-container').width();
    const newPopupWidth = containerWidth * 0.8;
    const newPopupHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0) * 0.7;
    const newPopupLeftMargin = containerWidth * 0.1;

    // Adjust the fullscreen popup.
    $('#fullscreen-popup')
      .css('transition', '0s')
      .width(`${newPopupWidth}px`)
      .css({
        'margin-left': `${newPopupLeftMargin}px`,
        'height': `${newPopupHeight}px`,
        'transition': '0.2s'
      });
  };

  /**
   * Finish the Export Customers -action by displaying results and adjusting the
   * styles of the progress text and the button itself.
   *
   * @param {Object} results
   * Results dictionary.
   */
  var exportCustomersFinish = function (results) {
    $('#export-customers-progress').css('display', 'none');
    $('#btn-export-customers').removeAttr('disabled');

    if ("errors" in results && results.errors.length > 0) {

      // Set start of error text.
      let error_text = `
        Asiakkaiden viemisessä Freshdeskiin tapahtui virheitä.
        Asiakkaita luotu: ${results.created_count},
        päivitetty: ${results.updated_count}\n\nVirheet:\n
      `.trimMultiline();

      // Add each error to the final error text.
      $.each(results.errors, function (k, v) {
        if ("asiakas_id" in v)
          error_text += `(asiakas ${v.asiakas_id}: ${v.asiakas}): `;
        error_text += `${v.text} (${v.description})\n`;
      });

      // Alert errors.
      alert(error_text + "\n\n\nEdistynyt tieto:\n" + data);

    } else {

      // Notify that the action succeeded.
      alert(`Asiakkaita luotu: ${results.created_count}, päivitetty: ${results.updated_count}`);
    }
  }

  //#endregion

  //*------------------------------------------------------------------------------------------------
  //* Progress Bar
  //*------------------------------------------------------------------------------------------------
  //#region Progress Bar

  /**
   * Indicates if the progress bar is currently active.
   * @type {Boolean}
   */
  var progressBarActive = false;

  /**
   * Can be set to true to tell progress bar to exit early.
   * @type {Boolean}
   */
  var progressBarShouldStop = false;

  /**
   * Elapsed milliseconds during previous animation.
   * @type {Number}
   */
  var progressBarPrevElapsed = 1000;

  /**
   * Activate progress bar and grow it to 100 in approximately 10 seconds.
   * Counts up until it reaches near 100 or is stopped. After finishing, the
   * bar is hidden again.
   */
  var progressBar = async function () {

    // Check if progress bar is already active, to avoid weird bugs. Otherwise,
    // reset the progress bar to an active state.
    if (progressBarActive) {
      console.log('Unable to start progress bar: already active.');
      return false;
    } else {
      await resetProgressBar(true);
    }

    const
      //? Millisecond timestamp representing the start time.
      startTime = (new Date()).getTime(),
      //? Amount of milliseconds between each cycle (sleep time).
      interval = 15;

    let
      //? Amount of times the increment has been adjusted (see below switch)
      adjustCount = 0,
      //? Current progress (non-float; 0-100).
      currentProgress = 0,
      //? Current elapsed time as millisecond timestamp
      elapsedTime = 0,
      //? Delayed time, set after reaching 90%.
      delayedTime = 0,
      //? How much progress should be incremented per cycle:
      //? = 0.01 * {cycle_increment}                  >> cycle_increment  =   {increment_per_ms} * {interval}
      //? = 0.01 * {increment_per_ms}  * {interval}   >> increment_per_ms =   100 / {previous_ms}
      //?                                             >> interval         =   sleep time per cycle
      //? = 0.01 * 100 / {previous_ms} * {interval}   >> previous_ms      =   elapsed time of previous execution
      increment = 0.01 * 100 / progressBarPrevElapsed * interval; // (*0.01: percentage to float)

    // Log start debug info.
    console.log(`
      Starting progress bar at ${startTime} with increment ${increment} @${interval}ms interval.
      Increment calculated from previous final elapsed time ${progressBarPrevElapsed}ms.
      Formula: 0.01 * 100 / {previous_ms:${progressBarPrevElapsed}} * {interval:${interval}}
    `.trimMultiline());

    while (!progressBarShouldStop && (1 - currentProgress > 0.01)) {

      // Update elapsed time.
      elapsedTime = new Date().getTime - startTime;

      // Slow down increment because time taken per request is not predictable.
      // This is a dirty workaround, but altogether not that important.
      switch (true) {
        case (adjustCount == 0 && currentProgress > 0.40): increment /= 1.20; adjustCount++; break;
        case (adjustCount == 1 && currentProgress > 0.45): increment /= 1.20; adjustCount++; break;
        case (adjustCount == 2 && currentProgress > 0.50): increment /= 1.20; adjustCount++; break;
        case (adjustCount == 3 && currentProgress > 0.55): increment /= 1.20; adjustCount++; break;
        case (adjustCount == 4 && currentProgress > 0.60): increment /= 1.30; adjustCount++; break;
        case (adjustCount == 5 && currentProgress > 0.65): increment /= 1.30; adjustCount++; break;
        case (adjustCount == 6 && currentProgress > 0.70): increment /= 1.30; adjustCount++; break;
        case (adjustCount == 7 && currentProgress > 0.75): increment /= 1.50; adjustCount++; break;
        case (adjustCount == 8 && currentProgress > 0.80): increment /= 2.50; adjustCount++; break;
        case (adjustCount == 9 && currentProgress > 0.85): increment /= 3.50; adjustCount++; break;
        case (adjustCount == 0 && currentProgress > 0.90): increment /= 4.50; adjustCount++; delayedTime = new Date().getTime(); break;
        case (currentProgress > 0.92 && elapsedTime - delayedTime > 6000):
          console.log(`progressBar exiting due to delay; 6 seconds elapsed after 90%, total elapsed: ${elapsedTime}`);
          requestStopProgressBar();
          break;
      }

      currentProgress += increment - (increment * currentProgress / 2);
      let ival = Math.trunc(currentProgress * 100);
      $('div.progress-bar').attr('aria-valuenow', ival).css('width', ival + '%');
      await new Promise(r => setTimeout(r, interval));
    }

    progressBarPrevElapsed = Math.max(200, ((new Date()).getTime() - startTime));
    progressBarActive = false;
    await resetProgressBar(false);
    return true;
  };

  /**
   * Tells the progress bar to stop if it's active.
   *
   * This function sets progressBarShouldStop = true if the progress bar is
   * active. If not, the function does nothing.
   *
   * The progress bar constantly checks the value of progressBarShouldStop, and
   * if it's true, the progress bar will exit if possible.
   */
  var requestStopProgressBar = function () {
    progressBarShouldStop = (progressBarActive == true);
  };

  /**
   * Resets the progress bar to a base state of active or stopped.
   *
   * @param {Boolean} active
   * Whether or not the progress bar should be reset to an active (block) or
   * inactive (hidden) state. In any case, the progress is set to 0.
   *
   * If true and progress bar is active, does nothing.
   * If true and progress bar is inactive, it is activated with progress 0.
   * If false and progress bar is active, progressBarShouldStop is set to true.
   * If false and progress bar is inactive, progress is set to 0.
   */
  var resetProgressBar = async function (active = false) {
    if (active) {
      if (!progressBarActive) {
        progressBarActive = true;
        progressBarShouldStop = false;
        $('div.progress-bar').attr('aria-valuenow', 0).css({
          width: 0,
          display: 'block',
          border: '2px solid #151414'
        });
      }
    } else if (progressBarActive) {
      progressBarShouldStop = true;
    } else {
      $('div.progress-bar').css({
        width: 0,
        display: 'none',
        border: 'none'
      });
      progressBarActive = false;
      progressBarShouldStop = false;
    }
  };

  //#endregion

  //*------------------------------------------------------------------------------------------------
  //* Helper Functions
  //*------------------------------------------------------------------------------------------------
  //#region Helper Functions

  /**
   * Open a link in a new tab and focus window to it.
   *
   * @param {String} url
   * Target URL.
   *
   * @returns {Boolean}
   * Whether or not the window was opened and focused.
   */
  var openNewTab = function (url) {
    const tab = window.open(url, '_blank');
    if (tab === null)
      return false;
    tab.focus();
    return true;
  };

  /**
   * Format UTC string to a more eye-friendly date string.
   *
   * @param {String} utc
   * Date string that is parseable with Date.parse().
   *
   * @return {String}
   * Formated date string with format: d.m.Y H:i, or false if formatting failed.
   */
  var formatUtcString = function (utc) {

    // Parse time from provided time string.
    let time = Date.parse(utc);

    // Avoid errors on invalid input data.
    if (time === NaN || typeof (time) !== 'number') {
      console.log(`formatUtcString(): Failed to parse input string using Date.parse(). Input: ${utc}`);
      return false;
    }

    // Specify time format to use for formatting.
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

    // Format date to individual parts.
    let [{ value: mo }, , { value: da }, , { value: ye }, , { value: ho }, , { value: mi }]
      = timeFormat.formatToParts(time);

    // Combine parts into final date string.
    return `${da}.${mo}.${ye} ${ho}:${mi}`;
  };

  /**
   * Cleans a multi-line string by trimming each line and joining elements.
   *
   * This function splits the input string by line break, trims each element
   * while removing empty elements, and joins the resulting array separated by
   * whitespace. This means that line breaks are removed.
   *
   * Example input and output:
   *   `
   *       First line.
   *     Second line with less indentation.
   *           Third line.`.trimMultiline();
   *
   *      --> "First line. Second line with less indentation. Third line."
   *
   * As displayed above, the first line is removed because it is empty. Each
   * element is checked for null/empty/whitespace, and removed if matches.
   *
   * @return {String} Resulting string without line breaks.
   */
  String.prototype.trimMultiline = function () {
    return this
      .split('\n')
      .filter((el) => { return el !== null && el.match(/^ *$/) === null })
      .map((val) => { return val.trim(); })
      .join(' ')
  };

  //#endregion

  //*------------------------------------------------------------------------------------------------
  //* Test Functions
  //*------------------------------------------------------------------------------------------------
  //#region Test Functions

  /**
   * Test the progress bar with random times between 500 and 2500 milliseconds.
   *
   * @param {Number} count
   * Amount of times to loop.
   */
  var testProgressBar = async function (count = 10) {
    for (let i = 0; i < count; i++) {
      const
        ms = Math.floor(Math.random() * 2500) + 500,
        timeout = setTimeout(() => requestStopProgressBar(), ms);
      await progressBar();
      clearTimeout(timeout);
    }
  };

  /**
   * Populate the ticket rows with random data to preview.
   *
   * @param {Number} count
   * Amount of tickets to generate.
   */
  var testTickets = function (count = 20) {

    /**
     * Sample names for generating random test tickets (8 items).
     * @type {Object}
     */
    const TICKET_TEST_NAMES = [
      'Testiasiakas A',
      'Ossi Meikäläinen',
      'Jouni A.',
      'Antero Mertasaari',
      'Jokupulju Oy',
      'Tuntematon',
      'Ninja Warrior',
      'Crokodile Dundee'
    ];

    /**
     * Sample dates for generating random test tickets (8 items).
     * @type {Object}
     */
    const TICKET_TEST_DATES = [
      // '09.01.2019 11:36', '06.03.2019 15:34', '07.05.2019 16:25', '18.08.2019 09:27',
      // '04.09.2019 17:32', '01.01.2020 18:55', '15.01.2020 17:41', '03.04.2020 08:24',
      '2019-01-09 11:36', '2019-03-06 15:34', '2019-05-07 16:25', '2019-08-18 09:27',
      '2019-09-04 17:32', '2020-01-01 18:55', '2020-01-15 17:41', '2020-04-03 08:24'
    ];

    /**
     * Sample subjects for generating random test tickets (8 items).
     * @type {Object}
     */
    const TICKET_TEST_SUBJECTS = [
      'Lorem ipsum dolor sit amet',
      'Phasellus rhoncus erat sed',
      'Ut rutrum, arcu sed',
      'Sed pretium nisi dui',
      'Quisque bibendum, dui non',
      'In lacinia felis et mi.',
      'Suspendisse quis quam velit.',
      'In vehicula commodo augue'
    ];

    /**
     * Sample descriptions for generating random test tickets (8 items).
     * @type {Object}
     */
    const TICKET_TEST_DESCRIPTIONS = [
      'mollis justo. Maecenas maximu id, dapibus eu arcu. parturient montes, nascetur ridiculus mus. Ut viverra molestie mi, accumsan dignissim odio suscipit ac. Nullam at blandit dolor, sit amet commodo nibh.',
      'Praesent lacinia cursus sem quis hendrerit. Duis in mi auctor, tincidunt elit et, ondimentum dui, non hendndrerit at urna sit amet, aliquet finibus diam.',
      'libero arcu finibus ipsum, eu convallis leo metus ut velit. Aliquam pellentesque tempus nisl sed egestas. Proin a purus a elit fermentum laoreet. Nullam non risus sit amet orci pellentesque mattis ac a neque. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam ultricies tortor dolor, a laoreet ex tincidunt quis.',
      'justo convallis vitae. Nulla sed nulla elit. Etiam ultricies sodales mattis. Donec euismod, odio quis dignimi, quis pellentesque tellus ante nec lectus. Donec sem neque, eleifend malesuada nibh eu, efficitur vehicula lectus. Sed accumsan, eros viverra sodales accumsan, ex ex posuere nisi, vitae euismod arcu eros ultrices sapien.',
      'ullamcorper lobortis enim urna at ipsum. Integer vel aliquet ligula, ac accumsan neque. Cras cursus sodales erat quis consectetur. Cras vestibulum ultricies orci congue porta. In posuere velit magna, nec venenatis urna suscipit sed. Vestibulum congue libero mi, in consectetur nulla gravida blandit. Vivamus sollicitudin felis dignissim faucibus aliquam. Nullam eros lectus, ornare quiss lacus a tempus.',
      'Mauris posuere urna posuere, vulputate elit id, gravida nisl. Nullam purus risus, vulputate nec libero ut, faucibus lobortis diam. Curabitur vel sem eu ex efficitur egestas ac ut diam. Donec ut tempor nisi. Vivamus orci dui, elementum ut nunc vitae, imperdiet semper mauris. Vivamus porttitor elementum lorem, nec volutpat justo cursus vel. Praesent nec nulla et lorem varius bibendum. Quisque at semaesent rutrum magna nulla, vitae fermentum turpis ultrices vitae.',
      'rhoncus felis tempor vitae. Nulla euismod nisl quis diam fringilla dignissim. Ut felis magna, fermentum sit amet felis ac, varius fringilla dui. Mauris vitae tortor lacinia, semper mi nec, mollis quam. Etiam luctus ligula ac ligula elementum, ornare faucibus nisi pulvinar. Etiam orci lectus, faucibus sed sollicitudin et, luctus ac massa. In rhoncus vitae dolor quis laoreet. Praesent tincidunt tula tristique. Donec lectus felis, eleifend vitae libero eu, vestibulum consequat enim.',
      'turpis at purus tempus pellentesque id id lorem. Ut a quam ornare, hendrerit felis e dignissim ut. Curabitur ultrices interdum purus, quis fringilla enim condimentum sed.'
    ];

    // Pick random elements from each test array and form tickets.
    for (let i = 0; i < count; i++) {
      const
        rand = Math.random(),
        customer = TICKET_TEST_NAMES[Math.floor(rand * 8)],
        subject = TICKET_TEST_SUBJECTS[Math.floor(rand * 8)],
        description = TICKET_TEST_DESCRIPTIONS[Math.floor(rand * 8)],
        date = TICKET_TEST_DATES[Math.floor(rand * 8)],
        obj = {
          requester: { name: customer },
          status: Math.floor(rand * 5) + 2,
          updated_at: date,
          subject: subject,
          description_text: description
        };
      drawTicket(i, obj, Math.floor(rand * 5000));
    }
  };

  //#endregion

  //*------------------------------------------------------------------------------------------------
  //* Initialized
  //*------------------------------------------------------------------------------------------------
  //#region Initialized

  adjustDynamicElements();

  // Fetch first set of tickets.
  listTickets();
  // testTickets();
  // testProgressBar();

  //#endregion
});
