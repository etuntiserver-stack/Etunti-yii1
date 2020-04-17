<style>
  div.output { overflow-y: scroll; }
  #output-primary { height: 180px; }
  #output-full { height: 350px; }
  a.hidden-if-empty:empty { display: none; }
</style>

<div class="page-header">
  <h1>Menneiden Toistuvien Työvuorojen Haku</h1>
</div>

<!-- Current status and controls -->
<a id="btn-start-toggle" href="#" class="btn btn-success btn-lg" tabindex="-1" role="button">Aloita</a>
<a id="status" href="#" class="btn btn-primary btn-lg disabled hidden-if-empty" tabindex="-1" role="button" aria-disabled="true"></a>
<a id="status-created" href="#" class="btn btn-primary btn-lg disabled hidden-if-empty" tabindex="-1" role="button" aria-disabled="true"></a><br><br>

<!-- Changed (important) output -->
<h4>Löydetyt/muutetut työvuorot:</h4>
<div id="output-primary" class="well well-sm output"></div>

<!-- Full debug output -->
<h4>Täysi tuloste:</h4>
<div id="output-full" class="well well-sm output"></div>


<script>
  $(function() {

    String.prototype.f = function() {
      var s = this,
        i = arguments.length;
      if (i == 1 && Array.isArray(arguments[0]))
        return s.f(...arguments[0]);
      while (i)
        s = s.replace(new RegExp('\\{' + i-- + '\\}', 'gm'), arguments[i]);
      return s;
    };

    var output = function(text, type = 0) {

      // Get and format current time.
      let time = new Date();
      const timeFormat = new Intl.DateTimeFormat('fi-FI', {
        timeZone: 'Europe/Helsinki', year: 'numeric', month: '2-digit', day: '2-digit',
        hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false,
      });
      let [{value: mo},,{value: da},,{value: ye},,{value: ho},,{value: mi},,{value: se}] = timeFormat.formatToParts(time);
      let timeStr = '{1}-{2}-{3} {4}:{5}:{6}'.f(ye, mo, da, ho, mi, se);

      // Output text with specific format.
      switch (type) {
        case -1:
          s = '<p class="text-danger"><b>{1} (VIRHE): {2}</b></p>'.f(timeStr, text);
          $("#output-primary").prepend(s);
          $("#output-full").prepend(s);
          return s;
        case 1:
          s = '<p class="text-primary"><b>{1}: {2}</b></p>'.f(timeStr, text);
          $("#output-primary").prepend(s);
          $("#output-full").prepend(s);
          return s;
        default:
          s = '<p class="text-secondary">{1}: {2}</p>'.f(timeStr, text);
          $("#output-full").prepend(s);
          return s;
      }
    };

    let previousLength = 0;
    let running = false;

    $("#btn-start-toggle").on("click", function(e) {
      e.preventDefault();

      if (!running) {
        $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/find_past_chains?run=1`, {
          type: 'POST',
          data: { find_past_chains_begin: true },
          xhrFields: {
            onprogress: function(e) {
              let response = e.currentTarget.response.substring(previousLength);
              previousLength = e.currentTarget.response.length;
              console.log(response);
              let parsed = JSON.parse(response);
              if (parsed.text.length > 0)
                output(parsed.text, parsed.type);
              $("#status").text("Käsitelty: {1}/{2}".f(parsed.current, parsed.total));
              if (parsed.created > 0) {
                $("#status-created").text("Ketjuja luotu: {1} (poistettu {2} työvuoroa)".f(parsed.created, parsed.deleted));
              }
            }
          }
        }).success(function(data) {
          output("Toiminto suoritettu.", 1);
        }).error(function(xhr, status, error) {
          output("Toiminto keskeytetty, virhe: " + xhr.responseText, -1);
        }).complete(function() {
          $("#btn-start-toggle").removeClass("btn-danger").addClass("btn-success").text("Aloita");
        });
        running = true;
        $("#btn-start-toggle").removeClass("btn-success").addClass("btn-danger").text("Pysäytä");
      } else {
        alert("Pysäytys ei vielä toiminnassa");
      }
    });
  });
</script>