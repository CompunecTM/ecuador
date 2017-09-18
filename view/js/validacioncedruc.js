function validar(thisaux,campocedruc, camposeltipofiscal,validarprocli){
  $.ajax({
    url: 'index.php?page=validacioncedruc',
    type: 'POST',
    dataType: 'json',
    data: {cedruc: thisaux, tipfiscal: $('select[name="'+camposeltipofiscal+'"]').val(), perfisica: $('input[name="personafisica"]').prop('checked'), validarprocli: validarprocli },
  })
  .done(function(data) {

    if (data['selclipro'] == '') {
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
    }else{

      if ($('#ac_cliente').length || $('#ac_proveedor').length) {
        $('input[name="'+campocedruc+'"]').val('');
        $('input[name="ac_cliente"').val(data['selclipro']);
        $('input[name="ac_cliente"').focus();
        $('input[name="ac_proveedor"').val(data['selclipro']);
        $('input[name="ac_proveedor"').focus();
      }else{

        $('input[name="'+campocedruc+'"]').attr('data-placement', 'top');

        $('input[name="'+campocedruc+'"]').attr('data-content','El cliente '+data['selclipro']+' ya existe registrado con la cedula o RUC indicado');

        $('input[name="'+campocedruc+'"]').popover('show');

        $('button[type="submit"]').attr('disabled',true);
      }
      
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

function validarprocli(){
  if ($('input[name="cliente"]').length) {
    return 'clientes';
  }

  if ($('form[name="f_nuevo_cliente"]').length) {
    return 'clientes';
  }

  if ($('input[name="proveedor"]').length) {
    return 'proveedores';  
  }

  if ($('form[name="f_nuevo_proveedor"]').length) {
    return 'proveedores'; 
  }
}

function funvalidar(){

  $("input[name='cifnif']").keyup(function() {
		validar($(this).val(),'cifnif','tipoidfiscal',validarprocli()); 
	}); 

	$("select[name='tipoidfiscal']").change(function() {
		$("input[name='cifnif']").val("");
	   	$("button[type='submit']").attr("disabled",true);
	}); 

	$("input[name='personafisica']").change(function() {
		$("input[name='cifnif']").val('');
	});
	
}

function funvalidar_nuevaventa(){

	$("input[name='nuevo_cifnif']").keyup(function() {
		validar($(this).val(),'nuevo_cifnif','nuevo_tipoidfiscal',validarprocli()); 
	}); 

	$("select[name='tipoidfiscal']").change(function() {
		$("input[name='cifnif']").val("");
	   	$("button[type='submit']").attr("disabled",true);
	}); 

	$("input[name='personafisica']").change(function() {
		$("input[name='nuevo_cifnif']").val('');
	});
	
}

$(document).ready(function() {

	funvalidar();

	funvalidar_nuevaventa();


});