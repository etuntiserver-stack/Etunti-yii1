<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<style>
.yiiLog{ display: none; }
</style>

<div class="container">
  <br>
  <br>
  <br>
  <br>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <div class="panel panel-danger">
        <div class="panel-heading">
          <h2 class="text-center">
          <span class="glyphicon glyphicon-exclamation-sign avaavirhe" aria-hidden="true"></span> Voi ei:
          <small>Toiminnossa tapahtui <b>virhe.</b></small>
          </h2>
        </div>
        <div class="panel-body">
          <p><h3>Toimi näin:</h3></p>

            <ul class="list-group">
              <li class="list-group-item">Siirry jatkamaan palvelun käyttö tästä <a href="https://app.etunti.fi">https://app.etunti.fi</a></li>
              <li class="list-group-item">Käyttö voi vaatia uudelleen kirjautumisen.</li>
                <li class="list-group-item">Virheen tekninen kuvaus on lähetetty tutkittavaksemme.</li>
              </ul>
          </div>
        </div>
      </div>
      <div class="col-md-2">

      </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){

 $(".avaavirhe").click(function(){
    $(".yiiLog").show(370);
 });

});
</script>
