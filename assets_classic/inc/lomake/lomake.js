jQuery(document).ready(function(){
    function lomake_validointi (lomake) {
        cont = true;
        lomake.find('.required').each(function(){
            if ($(this).find('input').attr('type') == 'checkbox' || $(this).find('input').attr('type') == 'radio'){
                var nimi = $(this).find('input').attr('name');
                if(!$(this).find('input[name="'+nimi+'"]').is(':checked')){
                    $(this).parent().addClass('error');
                    cont = false;
                }
            }else if($(this).val().length == 0){
                    $(this).parent().addClass('error');
                    cont = false;
            }
        });
        /* Oikea email */
        lomake.find('.email').each(function(){
            if ($(this).val().length != 0){
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if(!emailReg.test($(this).val())) {
                $(this).parent().addClass('error');
                cont = false;
                }
            }
        });
        /* numerokenttä */
        lomake.find('.number').each(function(){
            if ($(this).val().length != 0){
                var numReg = /^([^a-zA-Z])*$/; /* kaikkea muuta paitsi kirjaimia */
                if(!numReg.test($(this).val())) {
                $(this).parent().addClass('error');
                cont = false;
                }
            }
        });
        if (cont == false){ lomake.find('.huom').css('display','block');}
        return cont;
    }

    $('form').each(function( index ) {
        var form = $(this);
        form.submit(function(event){
            cont = lomake_validointi(form);
            if(cont == true && form.hasClass('ajax')){
                event.preventDefault();
                $.ajax({
                  type: form.attr('method'),
                  url: form.attr('action'),
                  //url: 'inc/lomake_tarjouspyynto.php',
                  //url: 'inc/lomake/lomake_class.php',
                  data: form.serialize()
                }).done(function(data) {
		  //console.log(data)
                  // Optionally alert the user of success here...
                  form.closest('div').html(data);


		  $('#lataaMenestyvan').html('Kiitos oppaan lataamisesta.');		
		  $('#taytapyydetytKentaat').html('Ilmainen opas on lähetetty sähköpostisi.');		

                  //console.log(data);
                }).fail(function(data) {
                    console.log("fail");
                  // Optionally alert the user of an error here...
                });

            }else{
                return cont;
            }
        });
    });
    $('input, textarea, select').focus(function() {
      $(this).closest('.error').removeClass('error');
    });
});
