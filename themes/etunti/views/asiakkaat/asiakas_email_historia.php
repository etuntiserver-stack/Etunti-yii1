<?php
?>

<div class="tray-center">
    <h2>Sähköposti historia</h2>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <button type="button" class="btn btn-primary myBgColors haeEmailHistoria">Hae</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="emailHistoryResult">

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".haeEmailHistoria").click(function() {
            $("#emailHistoryResult").html("<div class='col-sm-12'>Haetaan historiaa... (haku on hidas ja kestää jopa yli 15 sekuntia!)</div>");
            $.ajax({
                url: location.protocol + "//" + location.host + '/index.php/asiakkaat/email_history?recipient=<?= urlencode($model->sahkoposti)?>',
                success: function(data) {
                    //console.log("data", data);
                    const logs = JSON.parse(data);
                    if(logs.length > 0) {
                        let resultHtml = "";
                        for(let log of logs) {
                            resultHtml += `
                            <div class="col-sm-12">
                                <div class="panel-body bg-light">
                                    <p>Aika: ${log.time}</p>
                                    <p>Vastaanottaja: ${log.email_to}</p>
                                    <p>Otsikko: ${log.email_subject}</p>
                                    <p>Viesti: ${log.email_message}</p>
                                </div>
                            </div>`
                        }
                        $("#emailHistoryResult").html(resultHtml);
                    } else {
                        $("#emailHistoryResult").html("<div class='col-sm-12'>Ei tuloksia.</div>");
                    }
                    
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                    $("#emailHistoryResult").html("<div class='col-sm-12'>Virhe haettaessa historiaa.</div>");
                }
            });
        });
    });
</script>