<?php

include("../../../../sessao.php");

//echo'<div class="tabs-spacer" style="display:none;">';


include("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');

$FRM_matricula    = isset( $_GET['matricula'])  ? $_GET['matricula']/* variavel com os ids das parcelas*/
                        : tool::msg_erros("O Campo matricula Obrigatorio faltando.");


$dadosassociado= associados::find_by_sql("SELECT
										  SQL_CACHE associados.*,
										  logradouros.descricao as nm_logradouro,
										  logradouros.cep,
										  estados.sigla AS nm_estado,
										  cidades.descricao AS nm_cidade,
										  bairros.descricao AS nm_bairro,
										  convenios.nm_fantasia as nm_convenio,
										  convenios.tx_adesao ,
										  usuarios.login as nm_usuario,
										  empresas.logomarca,
										  (
										  SELECT cidades.descricao
										  FROM
										  empresas
										  LEFT JOIN logradouros ON empresas.logradouros_id = logradouros.id
										  LEFT JOIN cidades ON logradouros.cidades_id = cidades.id
										  WHERE logradouros.id = empresas.logradouros_id) as cidade_emp,
										  empresas.razao_social,
										  empresas.fone_fixo as fone_fixo_emp,
										  empresas.fone_cel as fone_cel_emp,
										  empresas.cnpj,
										  vendedores.nm_vendedor as nm_vendedor,
										  configs.nm_seguradora,configs.num_apolice,configs.cnpj_seg,configs.vlr_apol_seg,configs.vlr_aux_fun,
										  dados_cobranca.dt_venc_p,dados_cobranca.valor,dados_cobranca.forma_cobranca_id,dados_cobranca.formascobranca_sys_id
										FROM
										  associados
										  LEFT JOIN logradouros ON logradouros.id = associados.logradouros_id
										  LEFT JOIN estados ON estados.id = logradouros.estados_id
										  LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
										  LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
										  LEFT JOIN usuarios ON usuarios.id = associados.usuarios_id
										  LEFT JOIN convenios ON convenios.id = associados.convenios_id
										  LEFT JOIN vendedores ON vendedores.id = associados.vendedores_id
										  LEFT JOIN empresas ON associados.empresas_id = empresas.id
										  LEFT JOIN dados_cobranca ON associados.matricula = dados_cobranca.matricula
										  LEFT JOIN configs ON configs.empresas_id =  empresas.id
										WHERE
										  associados.matricula = ".$FRM_matricula."");

$dadosdependentes=dependentes::find_by_sql("SELECT
									   SQL_CACHE dependentes.*,
									   parentescos.descricao
									   FROM
									   	dependentes
									   LEFT JOIN parentescos ON dependentes.parentescos_id = parentescos.id
									   WHERE dependentes.matricula='".$FRM_matricula."' and dependentes.status='1'");

$dcad = new ActiveRecord\DateTime($dadosassociado[0]->dt_cadastro);

echo'</div>';
?>

<nav class="uk-navbar" style=" padding:8px 8px 8px 0px; text-align: right;">
  <a  onclick="Print();">
  <i class="uk-icon-print uk-icon-medium "></i>
  </a>
</nav>

<style>
p{ margin:5px 3px;}
em{ font-size:11pt; font-weight:bold;}
.tbform1 {border:1px solid #ccc;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;behavior:url(border-radius.htc);}
.tbform1 tr{border:1px solid #ccc; line-height:20px; }
.tbform1 td{font-size:8pt; border-right:1px solid #ccc; padding-left:2px;border-top:1px solid #ccc;}
.titulo{ background-color:#f5f5f5; text-transform: capitalize; font-weight:bold;}
</style>

<div id="print_c" style=" height: 503px; width:100%; overflow:auto; padding:0; margin:0 auto;background:#fff; ">

<p style="font-family:arial; width:98%; text-align:right; font-size:7pt; position:relative;" >Pagina 1</p>

<div id="big_col_contrato" style="border:0; width: 98%; margin: 0 auto;">
		<div id="col_left_header" style="float: left; height:80px; width: 33%; ">
			<div id="col_logo" style="width: 200px; float: left; margin: 10px 10px;">
				<img src="imagens/empresas/<?php echo $dadosassociado[0]->logomarca; ?>.png" alt="">
			</div>
		</div>
		<div id="col_center_header" style="float: left; height:80px; width: 64%; text-align: center; padding-top: 5px;">
			<div class="uk-article">
				<p style="text-align: justify; margin-bottom: 5px; font-size: 16pt; font-weight: bold;">
				CONTRATO DE ADESÃO
				</p>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
					<?php echo $dadosassociado[0]->razao_social."  CNPJ  ".tool::MascaraCampos("??.???.???/????-??",$dadosassociado[0]->cnpj); ?>
				</p>
	        </div>
		</div>
</div>
<div id="col_CONTRATANTE" style="border:0; height: auto; width: 98%; margin: 0 auto;">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbform1">
		  <tr>
		    <td style=" text-transform:uppercase; font-weight:bold; font-size:14px; line-height:30px; background-color:#f5f5f5;">contratante:</td>
		  </tr>
		  <tr>
		    <td style="border:0; padding:0;"><table  width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0;">
		      <tr >
		        <td width="11%" align="center" class="titulo">Nº Contrato</td>
		        <td width="11%" align="center" class="titulo">Matricula</td>
		        <td width="13%" align="center" class="titulo">Data Contrato</td>
		        <td width="35%" class="titulo">Vendedor</td>
		        <td width="30%" class="titulo">Credenciado</td>
		      </tr>
		      <tr >
		        <td align="center"><?php echo str_pad($dadosassociado[0]->empresas_id.'.'.$dadosassociado[0]->convenios_id.'.'.$dadosassociado[0]->matricula ,10, "0", STR_PAD_LEFT); ?></td>
		        <td align="center"><?php echo str_pad($dadosassociado[0]->matricula ,10, "0", STR_PAD_LEFT); ?></td>
		        <td align="center"><?php echo $dcad->format("d/m/Y");?></td>
		        <td><?php echo strtoupper($dadosassociado[0]->nm_vendedor); ?></td>
		        <td><?php echo strtoupper($dadosassociado[0]->nm_convenio); ?></td>
		      </tr>
		    </table></td>
		  </tr>
		  <tr>
		    <td style="border:0;padding:0;"><table  width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0;">
		      <tr class="titulo" >
		        <td width="50%">Nome completo resp.</td>
		        <td width="13%" align="center">Data Nasc</td>
		        <td width="13%" align="center">CPF</td>
		        <td width="12%" align="center">RG</td>
		        <td width="12%" align="center">O.emissor RG</td>
		      </tr>
		      <tr >
		        <td><?php echo strtoupper($dadosassociado[0]->nm_associado); ?></td>
		        <td align="center"><?php $dnasc = new ActiveRecord\DateTime($dadosassociado[0]->dt_nascimento); echo $dnasc->format("d/m/Y");?></td>
		        <td align="center"><?php echo tool::MascaraCampos("???.???.???-??",$dadosassociado[0]->cpf); ?></td>
		        <td align="center"><?php echo $dadosassociado[0]->rg; ?></td>
		        <td align="center"><?php echo $dadosassociado[0]->orgao_emissor_rg; ?></td>
		      </tr>
		    </table></td>
		  </tr>
		  <tr>
		    <td style="border:0;padding:0;"><table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
		      <tr class="titulo" >
		        <td>Endereço completo (Rua,Av,Num)</td>
		        <td width="27%">Bairro</td>
		        <td width="12%" align="center">CEP</td>
		      </tr>
		      <tr >
		        <td><?php echo $dadosassociado[0]->nm_logradouro."-".$dadosassociado[0]->num; ?></td>
		        <td><?php echo $dadosassociado[0]->nm_bairro; ?></td>
		        <td align="center"><?php echo tool::MascaraCampos("??.???-???",$dadosassociado[0]->cep); ?></td>
		      </tr>
		    </table></td>
		  </tr>
		  <tr>
		    <td style="border:0;padding:0;"><table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
		      <tr class="titulo" >
		        <td width="276">Cidade</td>
		        <td width="48" align="center">UF</td>
		        <td width="113" align="center">Casa própria ?</td>
		        <td width="180">E-mail</td>
		        <td width="87" align="center">Tel Celular</td>
		        <td width="87" align="center">Tel Trabalho</td>
		        <td width="87" align="center">Tel Residência</td>
		      </tr>
		      <tr >
		        <td><?php echo strtoupper(utf8_encode($dadosassociado[0]->nm_cidade)); ?></td>
		        <td align="center"><?php echo $dadosassociado[0]->nm_estado; ?></td>
		        <td align="center"><?php  if($dadosassociado[0]->casa_propria=="s"){echo"Sim";}elseif($dadosassociado[0]->casa_propria=="n"){echo"Não";}else{echo "-";} ?></td>
		        <td><?php echo $dadosassociado[0]->email; ?></td>
		        <td align="center"><?php echo tool::MascaraCampos("?? ????-????",$dadosassociado[0]->fone_cel); ?></td>
		        <td align="center"><?php echo tool::MascaraCampos("?? ????-????",$dadosassociado[0]->fone_trabalho); ?></td>
		        <td align="center"><?php echo tool::MascaraCampos("?? ????-????",$dadosassociado[0]->fone_fixo); ?></td>
		      </tr>
		    </table></td>
		  </tr>
		</table>
</div>
<div id="col_dependentes" style="border:0; height: auto; width: 98%; margin: 10px auto;">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbform1">
			<tr>
			    <td style=" text-transform:uppercase; font-weight:bold; font-size:14px; line-height:30px; background-color:#f5f5f5;">dependentes:</td>
			</tr>
			<tr>
			    <td style="border:0; padding:0;">
				    <table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
				      <tr class="titulo">
				        <td height="21">Nome Completo</td>
				        <td width="100" align="center">Telefone</td>
				        <td width="100" align="center">Data nascimento</td>
				        <td width="100" align="center">Idade</td>
				        <td width="100" align="center">Parentesco</td>
				        <td width="100" align="center">Documento</td>
				      </tr>
				    </table>
			    </td>
			</tr>
			<tr>
		    	<td style="border:0;padding:0;">
				<?php if($dadosdependentes == true ){ ?>
		    	<table style="border:0;" width="100%" border="0"  cellspacing="0" cellpadding="0">
				<?php
				$list= new ArrayIterator($dadosdependentes);
				while($list->valid()):
				?>
		   		<tr class="linhas">

		        <td><?php echo strtoupper($list->current()->nome); ?></td>

		        <td width="100" align="center">-</td>

		        <td width="100" align="center"><?php  $dtn = new ActiveRecord\DateTime($list->current()->dt_nascimento);
		        if($list->current()->dt_nascimento==''){echo'00/00/0000';}else{echo $dtn->format('d/m/Y');}
		        ?>
		        </td>
		        <td width="100" align="center">
				<?php
				if($list->current()->dt_nascimento==''){echo'00/00/0000';}else{
		        $dtn= new ActiveRecord\DateTime($list->current()->dt_nascimento);
				$date = new DateTime($dtn->format("d-m-Y")); // data de nascimento formatada
				$interval = $date->diff( new DateTime( date("Y-m-d")) ); // data agora
				$idde=$interval->format( '%Y' ); //formato da idade
				echo $idde. " Anos";}
				?>
				</td>
		        <td width="100" align="center"><?php echo $list->current()->descricao;?></td>
		        <td width="100" align="center">
		        <?php
		        if($list->current()->cpf !='' and strlen($list->current()->cpf) >= 10){
		        echo tool::MascaraCampos("???.???.???-??",tool::CompletaZeros(11,$list->current()->cpf));
		    	}else{echo"-";}
		        ?>

		        </td>
		      </tr>
		      <?php
				$list->next();
				endwhile;
			   ?>
		    </table>

	<?php }else{ ?>

			<table style="border:0;" width="100%" border="0"  cellspacing="0" cellpadding="0">
		      <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		      </tr>
		      <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		      </tr>
		       <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		      </tr>
		      <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		     </tr>
		     <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		        <td width="100" align="center">-</td>
		    </tr>
		      </table>
		<?php } ?>
		</td>
		  </tr>
		</table>
	</div>

<div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">

		<div class="uk-article">
			<h1 class="uk-article-lead" style="font-size: 14px;"><strong>A CONTRATADA</strong></h1>

			<p style="font-size: 9pt; text-align: justify;"> <strong><?php echo strtoupper($dadosassociado[0]->razao_social); ?></strong> Pessoa jurídica de direito privado,devidamente inscrita no CNPJ sob o nº <strong><?php echo tool::MascaraCampos("??.???.???/????-??",$dadosassociado[0]->cnpj); ?></strong>,</ br> com sede na cidade de Araxá/MG, na Av Imbiara 349 sala 05 ,Centro CEP:38183-244, neste ato representado por seu representante legal JULIANO REZENDE, (BRASILEIRO), (CASADO), CPF n° 012.337.516-97, residente e domiciliado na Rua Jose Eduardo de Medeiros nº 30, Bairro da Novo Orozino, Araxá/MG, CEP:38181-471;</p>

			<p style="font-size: 9pt; text-align: justify; margin: 15px 0;">Os benefícios, responsabilidades e obrigações deste contrato  reger-se-ão pelas cláusulas arroladas abaixo e por eventuais modificações estipuladas.</p>
        </div>

</div>
<div id="colclausulas" style="border:0; height: auto; width: 98%; margin: 0px auto;">

			<div class="uk-article">
				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA PRIMEIRA – DO OBJETO</h1>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Primeiro: </strong>A CONTRATADA assegurará; descontos ao CONTRATANTE e seus dependentes, nos serviços de consultas médicas, exames clínicos, tratamentos odontológicos a custos reduzidos, a serem prestados pela rede credenciada.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Segundo: </strong>A CONTRATADA não se responsabiliza pela qualidade técnica e profissional dos serviços prestados pela rede credenciada, bem como pelo recebimento dos valores fixados.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Terceiro: </strong>O CONTRATANTE receberá no momento da celebração do contrato uma lista contendo a relação de todas as clínicas, profissionais e demais empresas credenciadas pela CONTRATADA.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Quarto: </strong>Poderão ser indicados como aderentes 5(Cinco)-(dependentes), sendo o cônjuge e filhos abaixo de 21 anos completos e solteiros, no caso de não haver filhos poderão ser inclusos sogro(a), Pai e mãe.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px; page-break-after: always;">
				<strong>Parágrafo Quinto: </strong>Somente o CONTRATANTE rigorosamente em dia com suas obrigações financeiras junto a CONTRATADA, terá direito aos serviços e vantagens por ela intermediados.
				</p>
	        </div>



</div>
<p style="font-family:arial; width:98%; text-align:right; font-size:7pt; position:relative;" >Pagina 2</p>

<div id="colclausulas" style="border:0; height: auto; width: 98%; margin: 5px auto;">

				        <div class="uk-article">
				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA SEGUNDA – DAS OBRIGAÇÕES DA CONTRATADA</h1>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Primeiro: </strong> A CONTRATADA se dispõe  a  atualizar sua lista de  toda rede credenciada mensalmente, do mesmo modo que se dispõe a exigir o desconto acordado com a rede credenciada.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Segundo:</strong> A CONTRATADA  não se obriga comunicar previamente ao CONTRATANTE qualquer alteração em sua lista de credenciadas e conveniadas.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Terceiro: </strong> A CONTRATADA não se responsabiliza por valores cobrados ou formas de pagamentos ofertadas pela rede credenciada, cabendo à mesma apenas a cobrança do desconto justificado em contrato com os credenciados.
				</p>
			</div>
			<div class="uk-article">

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Quarto: </strong> A CONTRATADA  se dispõe  a oferecer ao CONTRATANTE uma lista de da rede credenciada sempre que solicitada.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Quinto: </strong>A CONTRATADA não se responsabiliza por serviços prestados ou produto vendido pela rede credenciada cabe ao CONTRATANTE proceder com as reclamações ou solicitações diretamente com as credenciadas.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Sexto:</strong>A CONTRATADA declara que os serviços por ela oferecidos, não se caracterizam como plano de saúde ou seguro de saúde nos moldes da lei 9656/1998.
				</p>

			</div>

			<div class="uk-article">

				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA TERCEIRA – DAS OBRIGAÇÕES DO CONTRATANTE</h1>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Primeiro:</strong> Como contra prestação pelos serviços de descontos e benefícios que prestará a(o) CONTRATANTE, a CONTRATADA fará jus ao recebimento de importância em dinheiro, que poderá ser paga pela(o) CONTRATANTE em parcelas, obedecidos os seguintes critérios:
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Segundo:</strong> A(o) CONTRATANTE efetuará no ato do Cadastro/inscrição o  pagamento do valor correspondente ao plano escolhido, valor este demonstrado neste  contrato nos Dados do pacote, assumindo o compromisso de pagar as parcelas mensais  e sucessivas que eventualmente sejam acordadas entre as partes e na data estipulada,  conforme plano financeiro demonstrado a(o) CONTRATANTE, no ato da efetivação da  Cadastro/inscrição, exposto neste contrato ou acertado via e-mail, valendo do  referido instrumento eletrônico para comprovação em juízo dos serviços contratados  ou dos acordos firmados entre as partes.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Terceiro:</strong> O pagamento das parcelas ora avençado deverá ser efetuado  conforme indicado pela CONTRATADA e a seu exclusivo critério, em cheques pré-datados, cartão de crédito, notas promissórias ou boleto bancários.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px; position:relative;">
				<strong>Parágrafo Quarto:</strong> O não recebimento de aviso ou do boleto para pagamento das  parcelas devidas pela(o) CONTRATANTE, não justificará qualquer atraso no respectivo  pagamento, devendo a(o) CONTRATANTE solicitar segunda via do mesmo no Setor  Financeiro da CONTRATADA ou proceder conforme orientação e solicitação da mesma.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Sexto: </strong> O CONTRATANTE se declara ciente de que os serviços oferecidos pelo CONTRATADA, não se caracterizam como plano de saúde ou seguro de saúde nos moldes da lei 9656/1998.
				</p>
	        </div>
	        <div class="uk-article">
				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA QUARTA – DO INADIMPLEMENTO</h1>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Primeiro: </strong>É de responsabilidade do CONTRATANTE manter atualizados junto á CONTRATADA seu cadastro pessoal, endereço para correspondência e telefones para contato .
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Segundo: </strong> Se as parcelas não forem pagas pela(o) CONTRATANTE nas respectivas datas de vencimentos, será acrescido, nos termos da Lei, de correção monetária "pro rata die", de juros de mora conforme estabelecido em lei , calculada até data do efetivo pagamento, além de multa moratória no importe de 10% sobre o debito, e ainda de todas as despesas que a CONTRATADA tiver para o recebimento do débito.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Terceiro: </strong> O CONTRATANTE, quando beneficiado com qualquer tipo de desconto perderá este quando deixar de pagar a parcela na data de seu vencimento.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Quarto: </strong>Havendo atraso de pagamento de qualquer valor ou acréscimo previsto no presente instrumento, a CONTRATADA fica desde já autorizada a emitir contra o CONTRATANTE os títulos de créditos pertinentes; efetuar a cobrança pelos meios previstos na legislação comum aplicável, extrajudicialmente ou judicialmente; registrar seu débito, nos valores exatos das respectivas parcelas, com todos os reajustes especificados neste contrato e quando for o caso, com os acréscimos aqui previstos, nos órgãos de proteção ao crédito, bastando para tanto, em atendimento ao artigo 43 § 2º do Código de Defesa do Consumidor, uma comunicação prévia que se dará mediante o envio de carta com aviso de recebimento (A.R.)., informando a consequente inscrição no SPC, SERASA ou outro órgão do gênero, assim como poderá encaminhar ao Tabelião/Cartório de protesto as parcelas referentes às mensalidades não pagas em seu vencimento.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Quinto: </strong> Na hipótese de encaminhamento do débito a escritório(s) jurídico(s), o CONTRATANTE assume o pagamento de honorários, desde já fixados em 10% (dez por cento) em caso de pagamento extrajudicial, e 20% (vinte por cento) em caso do encaminhamento judicial.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Sexto: </strong> A presente cláusula configura anuência expressa do CONTRATANTE para que a CONTRATADA assim proceda, não podendo, o inadimplente alegar qualquer dano pela inscrição de seu nome nos cadastros de proteção ao crédito ou por qualquer ação da CONTRATADA nos moldes acima.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Stimo: </strong> O recebimento pela CONTRATADA de eventual parcela em atraso, sem encargos será interpretado como mera liberalidade, não desconstituindo as condições pertinentes ora avençadas.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Oitavo: </strong> O CONTRATANTE está ciente que o atraso superior a 30 dias no pagamento de qualquer parcela, implica na suspensão dos serviços prestados, até a efetiva regularização do débito.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin: 5px 0;">
		        	<strong>Parágrafo Nono: </strong> A cobrança administrativa e/ou judicial será feita pela CONTRATADA  ou por terceiros, a seu critério.
		        </p>
	        </div>
	        <div class="uk-article">

				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA QUINTA – DOS VALORES</h1>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Primeiro:</strong> O CONTRATANTE pagará 12 parcelas sucessivas no valor de <strong>12</strong> ( <?php echo tool::valorPorExtenso("12") ; ?>  ) totalizando o valor de <strong>R$ <?php echo number_format($dadosassociado[0]->valor*12,2,',','.'); ?></strong> ( <?php echo tool::valorPorExtenso($dadosassociado[0]->valor*12) ; ?>),   ou avista no valor de <strong>R$<?php echo  number_format($dadosassociado[0]->valor*11,2,',','.'); ?>
					</strong> ( <?php echo tool::valorPorExtenso($dadosassociado[0]->valor*11) ; ?>) , enquanto estiver vigente o presente contrato.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Segundo:</strong> O CONTRATANTE pagará um taxa de inclusão no valor de <strong>R$ <?php echo "R$ ". number_format($dadosassociado[0]->tx_adesao,2,',','.'); ?></strong>  ( <?php echo tool::valorPorExtenso($dadosassociado[0]->tx_adesao) ; ?> ), no ato da assinatura do presente contrato, que deverá ser paga diretamente ao representante do CONTRATADA.
				</p>
	        </div>
	        <div class="uk-article">

				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA SEXTA – DA VIGÊNCIA</h1>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Primeiro:</strong> O presente contrato de adesão terá;  validade de 12 ( doze ) meses, sendo renovado automaticamente por tempo indeterminado caso não haja manifestação contrária de qualquer das partes.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px; page-break-after: always;">
				<strong>Parágrafo Segundo:</strong>  O Presente contrato será reajustado todo mês de janeiro de cada ano com base no IGPM integral da FGV do ano anterior.
				</p>
			</div>
</div>
<p style="font-family:arial; width:98%; text-align:right; font-size:7pt; position:relative;" >Pagina 3</p>
<div id="colclausulas" style="border:0; height:1000px; width: 98%; margin: 5px auto;">

			<div class="uk-article">
				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA SÉTIMA – DA GRATIFICAÇÃO OU BONIFICAÇÃO</h1>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Primeiro:</strong> Para ter direito ao seguro de vida e auxilio funerário familiar o CONTRATANTE deve está em dia com suas obrigações financeiras perante a CONTRATADA. Este  benefício é acobertado pela <?php echo utf8_encode(strtoupper($dadosassociado[0]->nm_seguradora));?> -	CNPJ <?php echo tool::MascaraCampos("??.???.???/????-??",$dadosassociado[0]->cnpj_seg); ?> apólice nº <strong> ( <?php echo utf8_encode(strtoupper($dadosassociado[0]->num_apolice));?> ),</strong> o  qual é concedido aos associados da CONTRATADA com as seguintes regras:
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>A - </strong>Este benefício é concedido em forma única de gratificação ou bonificação não havendo pagamento de qualquer valor por parte do associado.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>B - </strong>Não existe obrigatoriedade do pagamento desta gratificação ou bonificação por nenhuma das partes; O mesmo pode ser cancelado ou suspenso sem prévio aviso.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>C - </strong>O titular terá direito a gratificação ou bonificação de um seguro de vida quando houver , sendo por morte acidental ou invalides total ou parcial permanente por acidente, obedecendo aos itens A e B do paragrafo primeiro.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>D - </strong> O titular e seus dependentes (cônjuge, filhos e filhas com idade entre 14 e 18 anos solteira (o) (as)), terá direito a gratificação/bonificação de auxilio funeral familiar quando houver a gratificação ou bonificação, sendo por morte acidental ou natural não cabendo à morte por suicídio, obedecendo aos itens A e B do paragrafo primeiro.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>E - </strong> Serão considerados dependentes unicamente esposo (a). Filhos (as). Este benefício não estende a outro grau de parentesco, mesmo se a pessoa falecida estiver no quadro de dependentes do CONTRATANTE.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>F - </strong>Só poderá; participar o CONTRATANTE  que tenha idade superior a 14 anos e inferior a 70 anos.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>G - </strong>O não pagamento da parcela mensal aqui avençada em até 05 (cinco dias) dias após a data de vencimento ,da direito CONTRATADA de automaticamente suspender ou cancelar o direito a  gratificação ou bonificação caso haja naquele mês.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>H - </strong>Caso haja o direito a gratificação ou bonificação  o CONTRATANTE ou DEPENDENTE LEGAL deverá; entrar em contato com a seguradora pelo telefone <strong>0800-727-5914</strong> e informar o número da apólice, Não cabendo a CONTRATADA nenhuma obrigação de comunicar.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;  ">
				<strong>I - </strong>A forma de gratificação ou bonificação aqui ofertada desobriga a CONTRATADA de emitir documentos ou comprovantes para o CONTRATANTE OU DEPENDENTE..
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>J - </strong>A CONTRATADA não se obriga de espécie alguma a comunicar ou reembolsar gratificações ou bonificações aqui oferecidas.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>L - </strong>A gratificação ou bonificação tem sua vigência mensal sendo renovada todo dia 1º de cada mês e não sendo acumulativa.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>M -</strong> O(A) CONTRATANTE se declara ciente de que só terá direito a gratificação ou bonificação oferecidos pelo CONTRATADA, se o mesmo estiver em dia com suas obrigações perante está CONTRATADA.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>O -</strong> O(A)CONTRATANTE se declara ciente de que a gratificação ou bonificação não tem custo algum, que o mesmo é distribuído de forma gratuita estando em conformidade com o Parágrafo Primeiro desta clausula.
				</p>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>P -</strong> A gratificação ou bonificação tem seu valor limite de até R$ <?php echo number_format($dadosassociado[0]->vlr_aux_fun,2,",","."); echo "( ".tool::valorPorExtenso($dadosassociado[0]->vlr_aux_fun)." )";?> de auxilio funeral para o CONTRATANTE e seus dependentes legais e de até R$ <?php echo number_format($dadosassociado[0]->vlr_apol_seg,2,",",".");  echo "( ".tool::valorPorExtenso($dadosassociado[0]->vlr_apol_seg)." )";?> de seguro de vida por morte acidental ou invalidez permanente por acidente para o CONTRATANTE não sendo oferecido ao dependentes.
				</p>
			</div>
	        <div class="uk-article">

				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA OITAVA – DA RESCISÃO</h1>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Primeiro:</strong> O titular poderá; rescindir o presente contrato no prazo de até 7 (sete) dias, contados da data de assinatura, conforme o art 49 da lei 8078/1990.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Segundo:</strong> As partes poderan rescindir o presente contrato unilateralmente sem qualquer ónus, desde que tenha transcorrido um mínimo de 6 (seis) meses do contrato, através de comunicado escrito com antecedência de 30 (trinta) dias. Em caso contrario o TITULAR pagará; uma multa de 50% das parcelas restantes para o termino da vigência do contrato.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Terceiro:</strong>  O pedido de cancelamento do contrato, deverá ser por escrito, com antecedência mínima de 30 dias, bem como o pagamento de todos os valores devidos até a efetiva rescisão contratual, acompanhado da respectiva multa.<br /><br />
				<strong>Parágrafo Quarto:</strong>  O cancelamento do contrato só se dará se o mesmo for feito de forma pessoal com a assinatura de um termo de cancelamento não sendo possível o cancelamento por telefone, mensagens, e-mails ou algo do gênero.
				</p>
	        </div>
        	<div class="uk-article">


				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA NONA - AUTORIZAÇÃO DE DEBITO</h1>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				Em adiantamento ao contrato supra,autorizo o débito para quitação do contrato de 12 (doze) meses, à vista,no valor de <strong>R$ <?php echo number_format($dadosassociado[0]->valor*11,2,',','.'); ?></strong> ( <?php echo tool::valorPorExtenso($dadosassociado[0]->valor*11) ; ?>) ou em 12  (doze) parcelas mensais no valor de <strong>R$ <?php echo  number_format($dadosassociado[0]->valor,2,',','.'); ?></strong> ( <?php echo tool::valorPorExtenso($dadosassociado[0]->valor) ; ?>) cada uma em favor da CONTRATADA.
				</p>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo Primeiro:</strong>Por ser verdade, assino esta Autorização de débito, que será; renovada automaticamente por prazo indeterminado se não houver manifestação contraria por escrito a CONTRATADA com prazo minimo de 30 dias (trinta) dias de antecedência.
				</p>

				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA DECIMA</h1>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				Esta Autorização de débito só pode ser cancelada dentro do prazo legal de 7 (sete) dias a contar da data de assinatura, nos termos do artigo 49 da lei 8078/1990 ou se quitadas as parcelas aqui autorizadas para pagamento dos 12 (doze ) meses iniciais previstos no CONTRATO, com desconto de 30% (trinta por cento).
				</p>

				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA DECIMA PRIMEIRA</h1>

				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				A correção do valor autorizado será; reajustado do mês de janeiro de cada ano com base no IGPM integral da FGV do ano anterior independentemente da data de assinatura,sendo esta divulgada em jornal de grande circulação no estado federativo onde se situa a CONTRATADA
				</p>

				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">CLÁUSULA DECIMA SEGUNDA – DO FORO</h1>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
				<strong>Parágrafo único:</strong> : Fica eleito o Foro da Comarca local, para dirimir eventuais dúvidas decorrentes deste contrato, com exclusão de qualquer outro, por mais privilegiado que seja.
				</p>
			</div>
			<div class="uk-article" style="margin-top: 20px; border: 1px solid #f5f5f5;">

			
			<div style="float:left;">

				<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">DETALHES DA COBRANÇA</h1>

					<?php
					// carne
					if($dadosassociado[0]->formascobranca_sys_id == 1){}
					// boleto
					if($dadosassociado[0]->formascobranca_sys_id == 2){

					echo'<div class="uk-article" ';

					echo'<p style="font-size: 8pt;">Forma de pagamento: Boleto Bancário</p>';
					echo'<p style="font-size: 8pt;">Dia de Vencimento: '.$dadosassociado[0]->dt_venc_p.' de cada mês. </p>';
					echo'<p style="font-size: 8pt;">Contratante: '.strtoupper($dadosassociado[0]->nm_associado).'</p>';
					echo'<p style="font-size: 8pt;">RG: '.$dadosassociado[0]->rg.'</p>';
					echo'<p style="font-size: 8pt;">CPF: '.tool::MascaraCampos("???.???.???-??",$dadosassociado[0]->cpf).'</p>';

					echo'</div>';

					}
					// conta de luz
					if($dadosassociado[0]->formascobranca_sys_id == 3){}
					// conta de agua
					if($dadosassociado[0]->formascobranca_sys_id == 4){}
					// debito automatico
					if($dadosassociado[0]->formascobranca_sys_id == 5){}
					//  cartão de credito
					if($dadosassociado[0]->formascobranca_sys_id == 6){}
					?>
			</div>
			<div style="float:right;">
<p style="font-size: 8pt; text-align: justify; margin-top: 30px; text-transform: capitalize;">
					<?php echo strtolower($dadosassociado[0]->cidade_emp." ".tool::DataAtualExtenso(1)); ?>
				</p>
				<p style="font-size: 8pt; text-align: justify; margin-top: 20px;">Assinatura e CPF do contratante.</p>
				<p style="font-size: 8pt; text-align: justify; margin-top: 5px;">________________________________</p>

			</div>

			</div>



</div>




</div> <!-- final print -->



<script type="text/javascript">

function Print(action){

  $( "#print_c" ).print();

}



</script>

