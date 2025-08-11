<?php 
require_once("../../../sessao.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Importação de arquivo</title>
<link rel="stylesheet" href="css/forms.css?<?php echo microtime(); ?>" />
<link href="jquery/plugins/alertify-0.3.11/themes/alertify.core.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css">
<link href="jquery/plugins/alertify-0.3.11/themes/alertify.bootstrap.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css">
<style>
.ui-tabs-nav{ width:100%;}
#msg{ width:500px; margin:0 -70px; height:175px; margin-top:210px; position:absolute; float:left;  font-size:9px;  text-transform:uppercase; background-color:#fff; font-weight:bold; overflow:auto;}
</style> 
</head>
<body>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

?>
</div>
<form action="" method="post" id="form_upload" enctype="multipart/form-data" style="width:98%; text-align: right; margin-top:5px;  background-color: transparent; background-image:none;" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbform">
    <tr>
    <td colspan="2">Empresa:</td>
  </tr>
  <tr>
    <td colspan="2">
    <select name="cdempresa" id="cdempresa" class="camposdigitaveis border_radios3 select ">
      <option selected="selected" value="0">Selecionar</option>
      <?php
 $descricaoempresa=empresa::find('all');
                                    $descricao= new ArrayIterator($descricaoempresa);
                                    while($descricao->valid()):
                                        echo'<option value="'.$descricao->current()->cdempresa.'" >'.$descricao->current()->nomefantasia.'</option>';
                                    $descricao->next();
                                    endwhile;		?>
    </select>
    </td>
  </tr>
  <tr>
    <td colspan="2">Mês a Assegurar:</td>
  </tr>
  <tr>
    <td><select name="mesassegurar" id="mesassegurar" class="camposdigitaveis border_radios3 select ">
      <option value="jan">Janeiro</option>
      <option value="fev">Fevereiro</option>
      <option value="mar">Marco</option>
      <option value="abr">Abril</option>
      <option value="mai">Maio</option>
      <option value="jun">Junho</option>
      <option value="jul">Julho</option>
      <option value="ago">Agosto</option>
      <option value="sete">Setembro</option>
      <option value="out">Outubro</option>
      <option value="nov">Novembro</option>
      <option value="dez">Dezembro</option>
    </select></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td width="50%">&nbsp;</td>
    <td width="50%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Aquivo: </td>
    </tr>
  <tr>
    <td colspan="2"><input type="file" style="border:0;"  name="arquivo" class="camposdigitaveis border_radios3 text_left" /></td>
  </tr>
  <tr>
    <td colspan="2">
      </td>
  </tr>
</table>
</form>
<fieldset style="width:98%; text-align: right;  background-color: transparent; background-image:none;" >
  <input name="Btn_Upload" type="button" class="btn_confirmar"  id="Btn_Upload" title="ENVIAR ARQUIVO" alt="ENVIAR ARQUIVO" value=" " />
</fieldset>
       

</body>
</html>
<script src="jquery/plugins/form/jquery.form.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("#Btn_Upload").click( function(){
		
			 // faz a requisição e envia os dados para a pagina de tratamento dos dados
	var w=905; // largura
	var h=530;// altura
	var d=80; // desconto no topo
	var winleft = ((screen.width-w)/2);
	var winttop = ((screen.height-h)/2);
	var NmWindow='Emissão de Carne';
	var html='';
	// loader
	$("#window_carregamento").fadeIn(50);

		event.preventDefault();
		
        $("#form_upload").ajaxForm({

            url: 'relatorios/seguro/app_importacao/upload.php',
            uploadProgress: function(event, position, total, percentComplete) {
                //$("#msg").html('Enviando...');
            },
            success: function(data) {
									// titulo da janela
									$("#titulo_janela_impressao").html(''+NmWindow+'');	
									// define o tamanho da janela e o conteudo
									$("#window_print_content").css('height',''+h+'px').css('width',''+w+'px').html(data);
									$("#window_print").css('left',''+winleft+'px').css('top',''+winttop-d+'px').show();
									$("#window_carregamento").fadeOut(500);
			 },
            error: function(){
				$("#msg").html("ERRO AO ENVIAR ARQUIVO");
            }

        }).submit()

    });

})
</script>
