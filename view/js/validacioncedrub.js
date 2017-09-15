function validar(thisaux,campocedrud, camposeltipofiscal){
  $.ajax({
    url: 'index.php?page=validacioncedrub',
    type: 'POST',
    dataType: 'json',
    data: {cedrud: thisaux, tipfiscal: $('select[name="'+camposeltipofiscal+'"]').val(), perfisica: $('input[name="personafisica"]').prop('checked'),pribpubli: $('input[name="privpubli"]').prop('checked') },
  })
  .done(function(data) {

    $('input[name="'+campocedrud+'"]').parent('div').removeClass('has-success');
    $('input[name="'+campocedrud+'"]').parent('div').removeClass('has-error');

    if (data['estatus'] == '1') {
      $('input[name="'+campocedrud+'"]').parent('div').addClass('has-success');
      $('button[type="submit"]').attr('disabled',false);
    }else{
      $('input[name="'+campocedrud+'"]').parent('div').addClass('has-error');
      $('button[type="submit"]').attr('disabled',true);
    }

    $('input[name="'+campocedrud+'"]').attr('data-container', 'body');
    $('input[name="'+campocedrud+'"]').attr('data-toggle', 'popover');
    $('input[name="'+campocedrud+'"]').attr('data-placement', 'top');
    
    $('input[name="'+campocedrud+'"]').attr('data-content', data['mensaje']);

    $('input[name="'+campocedrud+'"]').popover('show');


  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    setTimeout(function() {
        $('input[name="'+campocedrud+'"]').popover('hide');
    }, 1000);
  });
  
}

function funvalidar(){

	$("input[name='cifnif']").keyup(function() {
		validar($(this).val(),'cifnif','tipoidfiscal'); 
	}); 

	$("select[name='tipoidfiscal']").change(function() {
		$("input[name='cifnif']").val("");
	   	$("button[type='submit']").attr("disabled",true);
	}); 

	auxscript='';

	auxscript +='<label class="checkbox-inline" style="margin-left: 0;">'; 
	auxscript +='<input type="checkbox" name="privpubli" value="TRUE" checked="" disabled="disabled">';
	auxscript +='empresa privada (no publica)';
	auxscript +='</label>';

	$('input[name="cifnif"]').parent().append(auxscript);

	$("input[name='personafisica']").change(function() {
		if(!$("input[name='personafisica']").prop("checked") ) {

			$("input[name='cifnif']").val("");

		  	$("button[type='submit']").attr("disabled",true);
		  	$("input[name='privpubli']").attr("disabled",false);
		  	$("select[name='tipoidfiscal']").val("R.U.C");
		  	$("select[name='tipoidfiscal']").attr("disabled",true);


			$("input[name='privpubli']").change(function() {

				$("input[name='cifnif']").val("");

			  	$("button[type='submit']").attr("disabled",true);
			}); 

		}else{
			$("input[name='cifnif']").val("");
		  	$("button[type='submit']").attr("disabled",true);
		  	$("input[name='privpubli']").attr("disabled",true);
		  	$("select[name='tipoidfiscal']").val("Cedula");
		  	$("select[name='tipoidfiscal']").attr("disabled",false);
		}
	});
	
}

function funvalidar_nuevaventa(){

	$("input[name='nuevo_cifnif']").keyup(function() {
		validar($(this).val(),'nuevo_cifnif','nuevo_tipoidfiscal'); 
	}); 

	$("select[name='nuevo_tipoidfiscal']").change(function() {
		$("input[name='nuevo_cifnif']").val("");
	   	$("button[type='submit']").attr("disabled",true);
	}); 

	auxscript='';

	auxscript +='<label class="checkbox-inline" style="margin-left: 0;">'; 
	auxscript +='<input type="checkbox" name="privpubli" value="TRUE" checked="" disabled="disabled">';
	auxscript +='empresa privada (no publica)';
	auxscript +='</label>';

	$('input[name="nuevo_cifnif"]').parent().append(auxscript);

	$("input[name='personafisica']").change(function() {
		if(!$("input[name='personafisica']").prop("checked") ) {

			$("input[name='nuevo_cifnif']").val("");

		  	$("button[type='submit']").attr("disabled",true);
		  	$("input[name='privpubli']").attr("disabled",false);
		  	$("select[name='nuevo_tipoidfiscal']").val("R.U.C");
		  	$("select[name='nuevo_tipoidfiscal']").attr("disabled",true);


			$("input[name='privpubli']").change(function() {

				$("input[name='nuevo_cifnif']").val("");

			  	$("button[type='submit']").attr("disabled",true);
			}); 

		}else{
			$("input[name='nuevo_cifnif']").val("");
		  	$("button[type='submit']").attr("disabled",true);
		  	$("input[name='privpubli']").attr("disabled",true);
		  	$("select[name='nuevo_tipoidfiscal']").val("Cedula");
		  	$("select[name='nuevo_tipoidfiscal']").attr("disabled",false);
		}
	});
	
}

function validar_chkpersonafisica(campocedrud, camposeltipofiscal){
	if(!$("input[name='personafisica']").prop("checked") ) {

		$("input[name='"+campocedrud+"']").val("");

	  	$("button[type='submit']").attr("disabled",true);
	  	$("input[name='privpubli']").attr("disabled",false);
	  	$("select[name='"+camposeltipofiscal+"']").val("R.U.C");
	  	$("select[name='"+camposeltipofiscal+"']").parent().append('<input type="hidden" name="aux" value="R.U.C">');
	  	$("select[name='"+camposeltipofiscal+"']").attr("name",camposeltipofiscal+'_AUX');
	  	$("input[name='aux']").attr("name",camposeltipofiscal);


	  	$("select[name='"+camposeltipofiscal+"_AUX']").attr("disabled",true);


		$("input[name='privpubli']").change(function() {


		  	$("button[type='submit']").attr("disabled",true);
		}); 

	}else{
	  	$("button[type='submit']").attr("disabled",true);
	  	$("input[name='privpubli']").attr("disabled",true);
	  	$("select[name='"+camposeltipofiscal+"']").val("Cedula");
	  	$("select[name='"+camposeltipofiscal+"']").attr("disabled",false);
	}
}



$(document).ready(function() {
	var scriptaux ='';
	var aux = '';
	var aux2 = '';

	funvalidar();

	funvalidar_nuevaventa();

	validar_chkpersonafisica('nuevo_cifnif', 'nuevo_tipoidfiscal');

	validar_chkpersonafisica('cifnif', 'tipoidfiscal');

	aux2= "'cifnif'";
	aux = 'input[name='+aux2+']';
	$('form[name="f_nuevo_cliente"]').append('<input type="hidden" name="scriptaux" value="0"/>');

	scriptaux += '<div class="scriptaux">';
	scriptaux += '<script>';
	scriptaux += '$("'+aux+'").keyup(function() {';
	scriptaux += '	validar($(this).val()); ';
	scriptaux += '}); ';

	aux2= "'tipoidfiscal'";
	scriptaux += '$("select[name='+aux2+']").change(function() {';
	aux2= "'cifnif'";
	scriptaux += '	$("input[name='+aux2+']").val("");';
	aux2= "'submit'";
	scriptaux += '  $("button[type='+aux2+']").attr("disabled",true);';
	scriptaux += '}); ';

	aux2= "'personafisica'";
	scriptaux += '$("input[name='+aux2+']").change(function() {';
	scriptaux += 'if(!$("input[name='+aux2+']").prop("checked") ) {';
	aux2= "'cifnif'";
	scriptaux += '	$("input[name='+aux2+']").val("");';
	aux2= "'submit'";
	scriptaux += '  $("button[type='+aux2+']").attr("disabled",true);';
	aux2= "'privpubli'";
	scriptaux += '  $("input[name='+aux2+']").attr("disabled",false);';
	aux2= "'tipoidfiscal'";
	scriptaux += '  $("select[name='+aux2+']").val("R.U.C");';
	scriptaux += '  $("select[name='+aux2+']").attr("disabled",true);';

	aux2= "'privpubli'";
	scriptaux += '$("input[name='+aux2+']").change(function() {';
	aux2= "'cifnif'";
	scriptaux += '	$("input[name='+aux2+']").val("");';
	aux2= "'submit'";
	scriptaux += '  $("button[type='+aux2+']").attr("disabled",true);';
	scriptaux += '}); ';

	scriptaux += '}else{';
	aux2= "'cifnif'";
	scriptaux += '	$("input[name='+aux2+']").val("");';
	aux2= "'submit'";
	scriptaux += '  $("button[type='+aux2+']").attr("disabled",true);';
	aux2= "'privpubli'";
	scriptaux += '  $("input[name='+aux2+']").attr("disabled",true);';
	aux2= "'tipoidfiscal'";
	scriptaux += '  $("select[name='+aux2+']").val("Cedula");';
	scriptaux += '  $("select[name='+aux2+']").attr("disabled",false);';
	scriptaux += '}';
	scriptaux += '}); ';

	scriptaux += '</script>';

	$('#b_nuevo_cliente').click(function() {
		if ($('input[name="scriptaux"]').val() == '0') {
			$('form[name="f_nuevo_cliente"]').append(scriptaux);
			$('input[name="scriptaux"]').val('1');
			$('button[type="submit"]').attr('disabled',true);
		}
	});
});