function validar(thisaux,campocedruc, camposeltipofiscal){
  $.ajax({
    url: 'index.php?page=validacioncedruc',
    type: 'POST',
    dataType: 'json',
    data: {cedruc: thisaux, tipfiscal: $('select[name="'+camposeltipofiscal+'"]').val(), perfisica: $('input[name="personafisica"]').prop('checked') },
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

    console.log(data['tipoval']);

    if (data['tipoval'] == 'CPE') {
    	$('select[name="'+camposeltipofiscal+'"]').val('Cedula');
    	$('input[name="personafisica"]').attr('checked',true);
    }
    if (data['tipoval'] == 'RPE') {
    	$('select[name="'+camposeltipofiscal+'"]').val('R.U.C');
    	$('input[name="personafisica"]').attr('checked',true);
    }
    if (data['tipoval'] == 'RPI') {
    	$('select[name="'+camposeltipofiscal+'"]').val('R.U.C');
    	$('input[name="personafisica"]').attr('checked',false);
    }
    if (data['tipoval'] == 'RPU') {
    	$('select[name="'+camposeltipofiscal+'"]').val('R.U.C');
    	$('input[name="personafisica"]').attr('checked',false);
    }



  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    setTimeout(function() {
        $('input[name="'+campocedruc+'"]').popover('hide');
    }, 1500);
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
	
}

function funvalidar_nuevaventa(){

	$("input[name='nuevo_cifnif']").keyup(function() {
		validar($(this).val(),'nuevo_cifnif','nuevo_tipoidfiscal'); 
	}); 

	$("select[name='tipoidfiscal']").change(function() {
		$("input[name='cifnif']").val("");
	   	$("button[type='submit']").attr("disabled",true);
	}); 
	
}

$(document).ready(function() {

	funvalidar();

	funvalidar_nuevaventa();


});