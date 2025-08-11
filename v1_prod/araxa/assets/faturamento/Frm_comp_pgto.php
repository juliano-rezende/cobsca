<?php
require_once"../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">

<?php
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

// ids das parcelas selecionadas
$FRM_ids			= isset( $_GET['ids'])	?	$_GET['ids']: $erro=('Campo ids está faltando ');
$FRM_matricula		= isset( $_GET['mat'])	?	$_GET['mat']: $erro=('Campo mat está faltando ');


// recupera os dados da
$dadosempresa=empresas::find_by_sql("
									SELECT empresas.*,logradouros.descricao as nm_logradouro,
									logradouros.complemento as complemento,
									logradouros.cep,estados.id AS estado_id,
									estados.sigla AS nm_estado,
									cidades.id AS cidade_id,
									cidades.descricao AS nm_cidade,
									bairros.id AS bairro_id,
									bairros.descricao AS nm_bairro
                                    FROM empresas
                                    INNER JOIN logradouros ON logradouros.id = empresas.logradouros_id
                                    INNER JOIN estados ON estados.id = logradouros.estados_id
                                    INNER JOIN cidades ON cidades.id = logradouros.cidades_id
                                    INNER JOIN bairros ON bairros.id = logradouros.bairros_id
                                    WHERE empresas.id = '".$COB_Empresa_Id."'");

// recupera os dados do associado
$dadosassociado= associados::find_by_sql("SELECT
										  associados.nm_associado,
										  associados.cpf
										FROM
										  associados
										WHERE
										  associados.matricula = ".$FRM_matricula."");
?>
</div>

<style>

#menu-float a{ background-color:transparent;}

</style>


<div style="height: 362px; width:100%; overflow-y: scroll;" id="print_comp_pgto">


	<div style="width:48%; float: left; border-left:1px solid #ccc; border-right:1px solid #ccc;padding:5px;" id="col_left">


		<h1 style="width: 100%; padding: 0; margin: 0; text-transform: uppercase; font-size: 8px;" class=" uk-text-small uk-text-left">

		Razão social :<?php echo utf8_encode( $dadosempresa[0]->razao_social ); ?>

		</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; text-transform: uppercase; font-size: 8px;" class=" uk-text-small uk-text-left">

		Nome fantasia: <?php echo utf8_encode( $dadosempresa[0]->nm_fantasia); ?>

		</h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 8px;" class=" uk-text-small uk-text-left">

		<?php echo utf8_encode($dadosempresa[0]->complemento." ".$dadosempresa[0]->nm_logradouro); ?>,
		<?php echo $dadosempresa[0]->num; ?>,
		<?php echo $dadosempresa[0]->compl_end; ?>

		</h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size:8px;" class=" uk-text-small uk-text-left">

		<?php echo utf8_encode($dadosempresa[0]->nm_bairro); ?> -
		<?php echo utf8_encode($dadosempresa[0]->nm_cidade); ?> /
		<?php echo utf8_encode($dadosempresa[0]->nm_estado); ?> -
		CEP: <?php echo tool::MascaraCampos("?????-???",$dadosempresa[0]->cep); ?>

		</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; text-transform: uppercase; font-size: 8px;" class=" uk-text-small uk-text-left">CNPJ: <?php echo tool::MascaraCampos("??.???.???/????-??",$dadosempresa[0]->cnpj); ?></h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 8px;" class=" uk-text-small uk-text-left">IM: <?php echo $dadosempresa[0]->im; ?></h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 8px;" class=" uk-text-small uk-text-left">data: <?php echo date("d/m/Y h:m:s"); ?></h1>

		<h1 style="width: 100%; padding: 0; margin: 0; font-size: 8px; line-height: 15px;" class="uk-text-left">
		************************************************************************************</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; font-size: 14px; line-height: 20px;" class="uk-text-bold uk-text-center">Não é documento fiscal</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; font-size: 8px;line-height: 15px;" class="uk-text-left">
		************************************************************************************</h1>


		<h1 style="width: 100%; padding: 5 0 0 0;  margin: 0; text-transform: uppercase;font-size: 8px; " class=" uk-text-small uk-text-left">Nome: <?php echo $dadosassociado[0]->nm_associado; ?></h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 8px;" class=" uk-text-small uk-text-left">Cpf: <?php echo tool::MascaraCampos("???.???.???/??",$dadosassociado[0]->cpf); ?></h1>


		<h1 style="width: 100%; padding: 0; margin: 0; font-size: 11px;" class=" uk-text-small uk-text-center">Comprovante de pagamento</h1>


		<table  class="uk-table uk-table-striped uk-table-hover" style="width: 100%;">

		<thead style="font-size: 12px; text-transform: capitalize; ">
		<tr class="uk-gradient-cinza" style="border-top: 1px solid #ccc;">
			<th class="uk-text-small">Cod</th>
			<th class="uk-text-small">Referencia</th>
			<th class="uk-text-small">vencimento</th>
			<th class="uk-text-small" >Valor</th>
			<th class="uk-text-small">pago</th>
		</tr>
		</thead>

		<tbody style="font-size: 11px;">
		<?php

		$total=0;
		$parcelas       = explode(',' ,$FRM_ids);

		foreach ($parcelas as $id){                                 //   faz um loop usando foreach e recupera os valores

		// recupera ps dados da parcela
		$Query_parcela=faturamentos::find($id);

		$ref = new ActiveRecord\DateTime($Query_parcela->referencia);
		$dtvenc = new ActiveRecord\DateTime($Query_parcela->dt_vencimento);
		$dtpgto = new ActiveRecord\DateTime($Query_parcela->dt_pagamento);

		$total+=$Query_parcela->valor_pago;

		?>
		<tr>
			<td style="text-align: center;"><?php echo $Query_parcela->id; ?></td>
			<td style="text-align: center;"><?php echo tool::Referencia($ref->format('Ymd'),"/"); ?></td>
			<td style="text-align: center;"><?php echo $dtvenc->format('d/m/Y'); ?></td>
			<td style="text-align: center;"><?php echo number_format($Query_parcela->valor,2,",","."); ?></td>
			<td style="text-align: center;"><?php echo number_format($Query_parcela->valor_pago,2,",","."); ?></td>
		</tr>
		<?php
		}
		?>
		</tbody>

		<tfoot style="font-size: 12px;">
		<tr class="uk-gradient-cinza" style="border-top: 1px solid #ccc;">
			<td >Total</td>
			<td ></td>
			<td ></td>
			<td >R$</td>
			<td style="text-align: center;"><?php echo number_format($total,2,",","."); ?></td>
		</tr>
		</tfoot>

		</table>
	</div> <!-- final coluna left -->

	<div style="width: 48%; float: right; border-left:1px solid #ccc; border-right:1px solid #ccc; padding:5px;">

	<h1 style="width: 100%; padding: 0; margin: 0; text-transform: uppercase; font-size: 8px;" class=" uk-text-small uk-text-left">

	Razão social :<?php echo utf8_encode( $dadosempresa[0]->razao_social ); ?>

	</h1>
	<h1 style="width: 100%; padding: 0; margin: 0; text-transform: uppercase; font-size: 8px;" class=" uk-text-small uk-text-left">

	Nome fantasia: <?php echo utf8_encode( $dadosempresa[0]->nm_fantasia); ?>

	</h1>
	<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 8px;" class=" uk-text-small uk-text-left">

	<?php echo utf8_encode($dadosempresa[0]->complemento." ".$dadosempresa[0]->nm_logradouro); ?>,
	<?php echo $dadosempresa[0]->num; ?>,
	<?php echo $dadosempresa[0]->compl_end; ?>

	</h1>
	<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 8px;" class=" uk-text-small uk-text-left">

	<?php echo utf8_encode($dadosempresa[0]->nm_bairro); ?> -
	<?php echo utf8_encode($dadosempresa[0]->nm_cidade); ?> /
	<?php echo utf8_encode($dadosempresa[0]->nm_estado); ?> -
	CEP: <?php echo tool::MascaraCampos("?????-???",$dadosempresa[0]->cep); ?>

	</h1>
	<h1 style="width: 100%; padding: 0; margin: 0; text-transform: uppercase; font-size: 8px;" class=" uk-text-small uk-text-left">CNPJ: <?php echo tool::MascaraCampos("??.???.???/????-??",$dadosempresa[0]->cnpj); ?></h1>
	<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 8px;" class=" uk-text-small uk-text-left">IM: <?php echo $dadosempresa[0]->im; ?></h1>
	<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 8px;" class=" uk-text-small uk-text-left">data: <?php echo date("d/m/Y h:m:s"); ?></h1>

	<h1 style="width: 100%; padding: 0; margin: 0; font-size: 8px; line-height: 15px;" class="uk-text-left">
	************************************************************************************</h1>
	<h1 style="width: 100%; padding: 0; margin: 0; font-size: 14px; line-height: 20px;" class="uk-text-bold uk-text-center">Não é documento fiscal</h1>
	<h1 style="width: 100%; padding: 0; margin: 0; font-size: 8px;line-height: 15px;" class="uk-text-left">
	************************************************************************************</h1>


	<h1 style="width: 100%; padding: 5 0 0 0;  margin: 0; text-transform: uppercase;font-size: 8px; " class=" uk-text-small uk-text-left">Nome: <?php echo $dadosassociado[0]->nm_associado; ?></h1>
	<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 8px;" class=" uk-text-small uk-text-left">Cpf: <?php echo tool::MascaraCampos("???.???.???/??",$dadosassociado[0]->cpf); ?></h1>


	<h1 style="width: 100%; padding: 0; margin: 0; font-size: 11px;" class=" uk-text-small uk-text-center">Recibo Sacado</h1>
	<br>


	<table  class="uk-table uk-table-striped uk-table-hover" style="width: 100%;">

		<thead style="font-size: 12px; text-transform: capitalize; ">
		<tr class="uk-gradient-cinza" style="border-top: 1px solid #ccc;">
			<th class="uk-text-small">Cod</th>
			<th class="uk-text-small">Referencia</th>
			<th class="uk-text-small">vencimento</th>
			<th class="uk-text-small" >Valor</th>
			<th class="uk-text-small">pago</th>
		</tr>
		</thead>

		<tbody style="font-size: 11px;">
		<?php

		$total=0;
		$parcelas       = explode(',' ,$FRM_ids);

		foreach ($parcelas as $id){                                 //   faz um loop usando foreach e recupera os valores

		// recupera ps dados da parcela
		$Query_parcela=faturamentos::find($id);

		$ref = new ActiveRecord\DateTime($Query_parcela->referencia);
		$dtvenc = new ActiveRecord\DateTime($Query_parcela->dt_vencimento);
		$dtpgto = new ActiveRecord\DateTime($Query_parcela->dt_pagamento);

		$total+=$Query_parcela->valor_pago;

		?>
		<tr>
			<td style="text-align: center;"><?php echo $Query_parcela->id; ?></td>
			<td style="text-align: center;"><?php echo tool::Referencia($ref->format('Ymd'),"/"); ?></td>
			<td style="text-align: center;"><?php echo $dtvenc->format('d/m/Y'); ?></td>
			<td style="text-align: center;"><?php echo number_format($Query_parcela->valor,2,",","."); ?></td>
			<td style="text-align: center;"><?php echo number_format($Query_parcela->valor_pago,2,",","."); ?></td>
		</tr>
		<?php
		}
		?>
		</tbody>

		<tfoot style="font-size: 12px;">
		<tr class="uk-gradient-cinza" style="border-top: 1px solid #ccc;">
			<td >Total</td>
			<td ></td>
			<td ></td>
			<td >R$</td>
			<td style="text-align: center;"><?php echo number_format($total,2,",","."); ?></td>
		</tr>
		</tfoot>

	</table>
</div> <!-- final coluna right -->
</div>
<div id="Col_pdf_email" class="uk-modal">
    <div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header ">
        <h2><i class="uk-icon-envelope-o" ></i> Recibo por e-mail</h2>
        </div>
		<form method="post" id="FrmRecebimento" class="uk-form" style="padding-top:0;margin:0;">

		<fieldset style="width:500px; background-color:transparent;">

		            <label>
		                <span>E-mail</span>
		                  <input  type="text" class="input_text w_300 center" name="email" id="email" autocomplete="off"/>
		            </label>
		</fieldset>
		</form>
        <div class="uk-modal-footer uk-text-right">
            <a id="Btn_email_confirm" class="uk-button uk-button-primary uk-button-small" ><i class="uk-icon-search" ></i> Confirmar</a>
            <a id="btn_email_cancel" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
        </div>
    </div>
</div>
<nav class="uk-navbar" style="padding:8px 18px 8px 0px; text-align: right; border-top: 1px solid #ccc;">
	<button class="uk-button uk-button-small uk-button-success" type="button" onclick="Print();"><i class="uk-icon-print"></i> Imprimir</button>
	<button class="uk-button uk-button-small uk-button-success" type="button" id="Btn_dow"><i class="uk-icon-floppy-o"></i> Download</button>
	<button class="uk-button uk-button-small uk-button-success" type="button" id="Btn_email"><i class="uk-icon-envelope-o"></i> E-mail</button>
</nav>
<script type="text/javascript" >


function Print(){

$( "#print_comp_pgto" ).print();

}

// envia os dados para gerar os boleto ou receber
jQuery(function(){

    jQuery("#Btn_dow").click(function(event) {


        // mensagen de carregamento
        jQuery("#msg_loading").html("Aguarde...");

        //abre a tela de preload
        modal.show();

        //desabilita o envento padrao do formulario
        event.preventDefault();

        jQuery.ajax({
                   async: true,
                    url: "assets/faturamento/recibo/Controller_recibo_pdf.php",
                    type: "post",
                    data: "ids=<?php echo $FRM_ids; ?>&mat=<?php echo $FRM_matricula; ?>",
                    success: function(resultado) {

                    	var text = '{"'+resultado+'"}';
                        var obj = JSON.parse(text);
                        modal.hide();



                        // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
                        if(obj.callback == 1){

                          New_window('exclamation-triangle','500','250','Atenção','<div style="padding: 5px; width:490px; height:240px; overflow-x:auto;">'+obj.msg +'</div>',true,true,'Aguarde...');


                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                        }else{

								window.open('assets/faturamento/recibo/arquivos/'+obj.arquivo+'');
                        }

                    },
                    error:function (){
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                        modal.hide();
                    }

        });
    });
});


// envia os dados para gerar os boleto ou receber
jQuery(function(){


    jQuery("#Btn_email").click(function(event) {


	var form_email = UIkit.modal("#Col_pdf_email");
	form_email.show();



	jQuery("#Btn_email_confirm").click(function(event) {

		// mensagen de carregamento
        jQuery("#msg_loading").html("Aguarde...");

        //abre a tela de preload
        modal.show();

		//desabilita o envento padrao do formulario
        event.preventDefault();


		jQuery.ajax({
			        async: true,
			        url: "assets/faturamento/recibo/Controller_recibo_pdf.php",
			        type: "post",
			        data: "ids=<?php echo $FRM_ids; ?>&mat=<?php echo $FRM_matricula; ?>",
			        success: function(resultado) {

			            var text = '{"'+resultado+'"}';
			            var obj = JSON.parse(text);

			             // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
			             if(obj.callback == 1){

			             	New_window('exclamation-triangle','500','250','Atenção','<div style="padding: 5px; width:490px; height:240px; overflow-x:auto;">'+obj.msg +'</div>',true,true,'Aguarde...');
			             	modal.hide();

			            // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
			            }else{

								jQuery("#msg_loading").html("Enviando email...");

								$.post("assets/faturamento/recibo/Controller_recibo_email.php", {email:jQuery("#email").val(),arq:obj.arquivo,ids:obj.ids},

										function(callback){

												modal.hide();
												UIkit.notify(''+callback+'', 'success');

										});

			            }

			        },
			        error:function (){
			            UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
			            modal.hide();
			        }
			 });
        });
    });
});


jQuery(document).ready(function(){
	jQuery("#menu-float").css("background-color",""+jQuery("#"+jQuery("#menu-float").closest('.Window').attr('id')+"").css('border-left-color')+"");// define a cor do menu lateral
});
</script>



