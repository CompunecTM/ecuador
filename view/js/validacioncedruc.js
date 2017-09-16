function validar(thisaux,campocedruc, camposeltipofiscal){
  $.ajax({
    url: 'index.php?page=validacioncedruc',
    type: 'POST',
    dataType: 'json',
    data: {cedruc: thisaux, tipfiscal: $('select[name="'+camposeltipofiscal+'"]').val(), perfisica: $('input[name="personafisica"]').prop('checked'),pribpubli: $('input[name="privpubli"]').prop('checked') },
  })
  .done(function(data) {

    $('input[name="'+campocedruc+'"]').parent('div').removeClass('has-success');
    $('input[name="'+campocedruc+'"]').parent('div').removeClass('has-error');

    if (data['estatus'] == '1') {
      $('input[name="'+campocedruc+'"]').parent('div').addClass('has-success');
      $('button[type="submit"]').attr('disabled',false);
    }else{
      $('input[name="'+campocedruc+'"]').parent('div').addClass('has-error');
      $('button[type="submit"]').attr('disabled',true);
    }

    $('input[name="'+campocedruc+'"]').attr('data-container', 'body');
    $('input[name="'+campocedruc+'"]').attr('data-toggle', 'popover');
    $('input[name="'+campocedruc+'"]').attr('data-placement', 'top');
    
    $('input[name="'+campocedruc+'"]').attr('data-content', data['mensaje']);

    $('input[name="'+campocedruc+'"]').popover('show');


  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    setTimeout(function() {
        $('input[name="'+campocedruc+'"]').popover('hide');
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

	validar_chkpersonafisica('cifnif', 'tipoidfiscal');
	
}

function funvalidar_nuevaventa(){

	$("input[name='nuevo_cifnif']").keyup(function() {
		validar($(this).val(),'nuevo_cifnif','nuevo_tipoidfiscal'); 
	}); 

	$("select[name='nuevo_tipoidfiscal']").change(function() {
		validar_chkpersonafisica('nuevo_cifnif', 'nuevo_tipoidfiscal');
	}); 

	auxscript='';

	auxscript +='<label class="checkbox-inline" style="margin-left: 0;">'; 
	auxscript +='<input type="checkbox" name="privpubli" value="TRUE" checked="" disabled="disabled">';
	auxscript +='empresa privada (no publica)';
	auxscript +='</label>';

	$('input[name="nuevo_cifnif"]').parent().append(auxscript);

	validar_chkpersonafisica('nuevo_cifnif', 'nuevo_tipoidfiscal');
	
}

function validar_chkpersonafisica(campocedruc, camposeltipofiscal){
	if(!$("input[name='personafisica']").prop("checked") ) {

	  	$("button[type='submit']").attr("disabled",true);
	  	$("input[name='privpubli']").attr("disabled",false);
	  	$("select[name='"+camposeltipofiscal+"']").val("R.U.C");
	  	$("select[name='"+camposeltipofiscal+"']").parent().append('<input type="hidden" name="aux" value="R.U.C">');
	  	$("select[name='"+camposeltipofiscal+"']").attr("name",camposeltipofiscal+'_AUX');
	  	$("input[name='aux']").attr("name",camposeltipofiscal);


	  	$("select[name='"+camposeltipofiscal+"_AUX']").attr("disabled",true);


		$("input[name='privpubli']").change(function() {
			$("input[name='"+campocedruc+"']").val('');
		  	$("button[type='submit']").attr("disabled",true);
		}); 

	}else{

	  	$("button[type='submit']").attr("disabled",true);
	  	$("input[name='privpubli']").attr("disabled",true);
	  	$("input[name='"+camposeltipofiscal+"']").remove();
	  	$("select[name='"+camposeltipofiscal+"_AUX']").attr('disabled',false);
	  	$("select[name='"+camposeltipofiscal+"_AUX']").attr('name',camposeltipofiscal);
	  	$("select[name='"+camposeltipofiscal+"']").val('Cedula');

		$("input[name='privpubli']").change(function() {
			$("input[name='"+campocedruc+"']").val('');
		  	$("button[type='submit']").attr("disabled",true);
		}); 
	}
}



$(document).ready(function() {
	var scriptaux ='';
	var aux = '';
	var aux2 = '';

	funvalidar();

	funvalidar_nuevaventa();

	scriptaux ='';

	aux2="'personafisica'";
	scriptaux +='<script>';

	scriptaux +='$("input[name='+aux2+']").change(function() {';
	scriptaux +='	validar_chkpersonafisica("nuevo_cifnif", "nuevo_tipoidfiscal");';
	scriptaux +='});';

	scriptaux +='</script>';

	$("#modal_proveedor").on('show.bs.modal', function () {
		
		$('#modal_proveedor').append(scriptaux);
		
    });

    scriptaux ='';
	scriptaux +='<script>';

	scriptaux +='$("input[name='+aux2+']").change(function() {';
	scriptaux +='	validar_chkpersonafisica("nuevo_cifnif", "nuevo_tipoidfiscal");';
	scriptaux +='});';

	scriptaux +='</script>';

    $("#modal_cliente").on('show.bs.modal', function () {
		
		$('#modal_cliente').append(scriptaux);
		
    });

    aux2= "'cifnif'";
	aux = 'input[name='+aux2+']';
	$('form[name="f_nuevo_cliente"]').append('<input type="hidden" name="scriptaux" value="0"/>');

	scriptaux += '<script>';
	scriptaux += '$("'+aux+'").keyup(function() {';
	scriptaux += '	validar($(this).val()); ';
	scriptaux += '}); ';

	aux2= "'personafisica'";
	scriptaux += '$("input[name='+aux2+']").change(function() {';
	scriptaux +='	validar_chkpersonafisica("cifnif", "tipoidfiscal");';
	scriptaux += '}); ';

	scriptaux +='</script>';

    $("#modal_nuevo_cliente").on('show.bs.modal', function () {

    	if ($('input[name="scriptaux"]').val() == '0') {
			$('form[name="f_nuevo_cliente"]').append(scriptaux);
			$('input[name="scriptaux"]').val('1');
			$('button[type="submit"]').attr('disabled',true);
		}
		
		$('#modal_nuevo_cliente').append(scriptaux);
		
    });

});