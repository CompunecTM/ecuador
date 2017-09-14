function validar(thisaux,seltipfiscal){
  $.ajax({
  	url: 'index.php?page=validacioncedrub',
  	type: 'POST',
  	dataType: 'json',
  	data: {cedrud: thisaux, tipfiscal: seltipfiscal, perfisica: $('input[name="personafisica"]').prop('checked')},
  })
  .done(function(data) {

  	$('input[name="cifnif"]').parent('div').removeClass('has-success');
  	$('input[name="cifnif"]').parent('div').removeClass('has-error');

  	if (data['estatus'] == '1') {
  		$('input[name="cifnif"]').parent('div').addClass('has-success');
  		$('button[type="submit"]').attr('disabled',false);
  	}else{
  		$('input[name="cifnif"]').parent('div').addClass('has-error');
  		$('button[type="submit"]').attr('disabled',true);
  	}

  	$('input[name="cifnif"]').attr('data-container', 'body');
  	$('input[name="cifnif"]').attr('data-toggle', 'popover');
  	$('input[name="cifnif"]').attr('data-placement', 'top');
  	
  	$('input[name="cifnif"]').attr('data-content', data['mensaje']);

  	$('input[name="cifnif"]').popover('show');
  })
  .fail(function() {
  	console.log("error");
  })
  .always(function() {
  	setTimeout(function() {
        $('input[name="cifnif"]').popover('hide');
    }, 1000);
  });
  
}

$(document).ready(function() {
	var scriptaux ='';
	var aux = '';
	var aux2 = '';
	var tipfiscal = '';
	var perfisica = '';

	tipfiscal = $('select[name="tipoidfiscal"]').val();


	aux2= "'cifnif'";
	aux = 'input[name='+aux2+']';
	$('form[name="f_nuevo_cliente"]').append('<input type="hidden" name="scriptaux" value="0"/>');

	scriptaux += '<div class="scriptaux">';
	scriptaux += '<script>';
	scriptaux += '$("'+aux+'").keyup(function() {';
	scriptaux += '	validar($(this).val(),"'+tipfiscal+'");';
	scriptaux += '});';
	scriptaux += '</script>';

	$('#b_nuevo_cliente').click(function() {
		if ($('input[name="scriptaux"]').val() == '0') {
			$('form[name="f_nuevo_cliente"]').append(scriptaux);
			$('input[name="scriptaux"]').val('1');
			$('button[type="submit"]').attr('disabled',true);
		}
	});
});