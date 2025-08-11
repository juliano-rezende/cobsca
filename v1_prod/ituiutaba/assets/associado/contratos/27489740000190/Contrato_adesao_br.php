<?php
include("../../../../sessao.php");
?>

<div class="tabs-spacer" style="display:none;">

<?php

include("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');

//$FRM_matricula    = isset( $_GET['matricula'])  ? $_GET['matricula']/* variavel com os ids das parcelas*/
//                        : tool::msg_erros("O Campo matricula Obrigatorio faltando.");


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
										  associados.matricula = '1'");



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

<div id="print_c" style=" height: 455px; width:100%; overflow:auto; padding:0; margin:0 auto;background:#fff; ">


<p style="font-family:arial; width:98%; text-align:right; font-size:7pt; position:relative;" >Pagina 1</p>

<div id="big_col_contrato" style="border:0; width: 98%; margin: 0 auto;">
		<div id="col_left_header" style="float: left; height:80px; width: 33%; ">
			<div id="col_logo" style="width: 200px; float: left; margin: 3px 10px;">

			</div>
		</div>
		<div id="col_center_header" style="float: left; height:80px; width: 64%; text-align: center; padding-top: 5px;">
			<div class="uk-article">
				<p style="font-size: 9pt; text-align: justify; margin-bottom: 5px; font-size: 16pt; font-weight: bold;">
				CONTRATO DE ADESÃO
				</p>
				<p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
					<?php echo $dadosassociado[0]->razao_social."  CNPJ".tool::MascaraCampos("??.???.???/????-??",$dadosassociado[0]->cnpj); ?>
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
		        <td width="11%" height="30" align="center" class="titulo">Nº Contrato</td>
		        <td width="11%" height="30" align="center" class="titulo">Matricula</td>
		        <td width="13%" height="30" align="center" class="titulo">Data contrato</td>
		        <td width="35%" height="30" class="titulo">Vendedor</td>
		        <td width="30%" height="30" class="titulo">Credenciado</td>
		      </tr>
		      <tr >
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30">&nbsp;</td>
		        <td height="30">&nbsp;</td>
		      </tr>
		    </table></td>
		  </tr>
		  <tr>
		    <td style="border:0;padding:0;"><table  width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0;">
		      <tr class="titulo" >
		        <td width="50%" height="30">Nome completo resp.</td>
		        <td width="13%" height="30" align="center">Data Nasc</td>
		        <td width="13%" height="30" align="center">CPF</td>
		        <td width="12%" height="30" align="center">RG</td>
		        <td width="12%" height="30" align="center">O.emissor RG</td>
		      </tr>
		      <tr >
		        <td height="30">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		      </tr>
		    </table></td>
		  </tr>
		  <tr>
		    <td style="border:0;padding:0;"><table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
		      <tr class="titulo" >
		        <td height="30">Endereço completo (Rua,Av,Num)</td>
		        <td width="27%" height="30">Bairro</td>
		        <td width="12%" height="30" align="center">CEP</td>
		      </tr>
		      <tr >
		        <td height="30">&nbsp;</td>
		        <td height="30">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		      </tr>
		    </table></td>
		  </tr>
		  <tr>
		    <td style="border:0;padding:0;"><table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
		      <tr class="titulo" >
		        <td width="276" height="30">Cidade</td>
		        <td width="48" height="30" align="center">UF</td>
		        <td width="113" height="30" align="center">Casa própria ?</td>
		        <td width="180" height="30">E-mail</td>
		        <td width="87" height="30" align="center">Tel Celular</td>
		        <td width="87" height="30" align="center">Tel Trabalho</td>
		        <td width="87" height="30" align="center">Tel Residência</td>
		      </tr>
		      <tr >
		        <td height="30">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
		        <td height="30" align="center">&nbsp;</td>
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
				        <td height="30">Nome Completo</td>
				        <td width="100" height="30" align="center">Telefone</td>
				        <td width="100" height="30" align="center">Data nascimento</td>
				        <td width="100" height="30" align="center">Idade</td>
				        <td width="100" height="30" align="center">Parentesco</td>
				        <td width="100" height="30" align="center">Documento</td>
				      </tr>
				    </table>
			    </td>
			</tr>
			<tr>
		    	<td style="border:0;padding:0;"><table style="border:0;" width="100%" border="0"  cellspacing="0" cellpadding="0">

		   		<tr class="linhas">

		        <td>&nbsp;</td>

		        <td width="100" align="center">&nbsp;</td>

		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		      </tr>
		       </table>
		    	  <table style="border:0;" width="100%" border="0"  cellspacing="0" cellpadding="0">
		      <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" height="30" align="center">&nbsp;</td>
		      </tr>
		      <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" height="30" align="center">&nbsp;</td>
		      </tr>
		       <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" height="30" align="center">&nbsp;</td>
		      </tr>
		      <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" height="30" align="center">&nbsp;</td>
		     </tr>
		     <tr class="linhas">
		        <td></td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" align="center">&nbsp;</td>
		        <td width="100" height="30" align="center">&nbsp;</td>
		    </tr>
		      </table>
		</td>
		  </tr>
		</table>
	</div>

<div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">

<div class="uk-article">
			<p style="font-size: 9pt; text-align: justify;">Contrato de Adesão LÍDER COBRANÇAS E SERVIÇOS, CNPJ: Sob o número  26.245.294/0001-05, localizada na Rua Paranaíba, número 66, centro,  Itumbiara-GO. </p>

		 <p style="font-size: 9pt; text-align: justify;"> Este  contrato de adesão garante que os clientes da Líder Cobranças e Serviços terão  o direito de usufruir do sistema de clínicas médicas populares de atendimento  básico e primário em casos sem complexidade, oferecido pela: AMA SAÚDE LTDA,  Fantasia UNICLÍNICA, localizado na Rua Dr. Valdivino Vaz n: 52 A, Centro, na  cidade de Itumbiara-GO, inscrita no CNPJ: sob n: 19.953.952/0001-58, mediante  as seguintes cláusulas e condições. </p>

<p style="font-size: 9pt; text-align: justify;">1.	A CONTRATADA obriga-se, em iguais condições, para todos que estão subscritos nesse contrato, assim sendo Titular/Aderente e seus dependentes, garantir os atendimentos básicos e primários em casos sem complexidades, oferecidos pela Clínica Médica UNICLINICA, de acordo com as listas disponibilizadas na sede da Clínica, respeitando os valores e profissionais já praticados e existentes na mesma. </p>

<p style="font-size: 9pt; text-align: justify;">
2. Cada ADERENTE declara ter sido esclarecido e ter conhecimento de que os serviços de saúde oferecidos pela UNICLÍNICA não se caracteriza como PLANO DE SAÚDE ou SEGURO DE ASSISTÊNCIA INTEGRAL E PRIVADO DE SAÚDE, nos termos da Lei: 9.656/98, pelo que a mesma Lei não se aplica ao presente contrato e negócio celebrado entre as partes. 
</p>

<p style="font-size: 9pt; text-align: justify;">
3. Todos os pagamentos de consultas e exames são de responsabilidade do paciente, sendo este pagamento à vista diretamente para UNICLÍNICA no dia do atendimento.
</p>

<p style="font-size: 9pt; text-align: justify;">
4. Todos os valores estarão sempre atualizados e disponíveis na UNICLÍNICA. (AS CONSULTAS DEVERÃO SER PREVIAMENTE AGENDADAS, NÃO HAVENDO ATENDIMENTOS DE URGÊNCIA E EMERGÊNCIA).
</p>

<p style="font-size: 9pt; text-align: justify;">
5. A UNICLINICA não se obriga a comunicar previamente ao TITULAR/DEPENDENTES desse contrato qualquer alteração de valores e profissionais em sua lista ou tabela
</p>

<p style="font-size: 9pt; text-align: justify;">
6. Será considerado ADERENTE TITULAR a pessoa que assinar este contrato e ADERENTES DEPENDENTES todos que possuírem qualquer grau de parentesco, até o número de 4 (quatro) pessoas, no máximo, que receberão, em igualdade de condições com o ADERENTE, todos os serviços oferecidos pela UNICLÍNICA, (NÃO FAZEMOS INCLUSÃO DE DEPENDENTES APÓS O CADASTRAMENTO DO CONTRATO).
</p>


</div>

<div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">
<p style="font-family:arial; width:98%; text-align:right; font-size:7pt; position:relative;" >Pagina 2</p>
<div class="uk-article">
<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">GRATIFICAÇÃO E BONIFICAÇÃO</h1>
<p style="font-size: 9pt; text-align: justify;">
CONDIÇÕES GERAIS: <br>
Para ter direito ao seguro de vida e auxílio funerário familiar o titular deverá efetuar o pagamento até a data de vencimento perante a LÍDER COBRANÇAS E SERVIÇOS CNPJ: 26.245.294/0001-05.  Este benefício é concedido em forma única de gratificação ou bonificação. 
Este benefício tem como estipulante a empresa LÍDER COBRANÇAS E SERVIÇOS CNPJ: 26.245.294/0001-05 o qual está acobertada pela MONGERAL AEGON, com as seguintes regras: 
</p>
	
<p style="font-size: 9pt; text-align: justify;">
7. Serão considerados dependentes para inclusão no auxílio funeral unicamente conjugue e filhos (as). Este benefício não estende a outro grau de parentesco, mesmo se a pessoa falecida estiver no quadro de dependentes da Líder Cobrança e Serviços.  
</p>			

<p style="font-size: 9pt; text-align: justify;">
7.1. Só poderá participar o associado que se encontra em perfeitas condições de saúde e que tenha idade superior a 18 anos e inferior a 64 anos e 11 meses.
</p>

<p style="font-size: 9pt; text-align: justify;">
7.2.  Só será incluso dependentes, cônjugue e filhos com idade de 14 a 21 anos para terem direito ao benefício do auxílio funeral.
</p>

<p style="font-size: 9pt; text-align: justify;">
7.3.  O benefício entra em vigor após 50 dias da assinatura deste contrato.
</p>

<p style="font-size: 9pt; text-align: justify;">
7.4. A contratada não se obriga de espécie alguma a comunicar ou reembolsar gratificações ou bonificações aqui oferecidas.
</p>

<p style="font-size: 9pt; text-align: justify;">
7.5. A forma de gratificação ou bonificação aqui ofertada desobriga a CONTRATADA de emitir documentos ou comprovantes para o CONTRATANTE OU DEPENDENTES.
</p>

<p style="font-size: 9pt; text-align: justify;">
7.6 A gratificação ou bonificação tem sua vigência mensal sendo renovada todo dia 1º de cada mês e não sendo acumulativa.
</p>

</div>
</div>

<div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">

<div class="uk-article">
<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">DOS VALORES</h1>

<p style="font-size: 9pt; text-align: justify;">

8.  O seguro de vida será concedido apenas no caso de morte acidental ou invalidez permanente por acidente, é válido apenas para o titular da apólice, não extensivo a dependentes cadastrados na Líder Cobranças e Serviços. O valor é de R$ 5.000,00 (cinco mil reais).
</p>

<p style="font-size: 9pt; text-align: justify;">
9.  O auxílio funeral terá o valor de R$ 3.500,00 (três mil quinhentos reais) para seus dependentes conforme critério disposto no art 7.2.
</p>

</div>
</div>

<div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">

<div class="uk-article">
<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">DAS OBRIGAÇÕES</h1>

<p style="font-size: 9pt; text-align: justify;">
10. O não pagamento da parcela mensal estipulada pela prestadora do serviço até a data de vencimento da mensalidade gera extinção de benefícios. Não sendo realizado o pagamento até a data de vencimento, gerará a caducidade do direito do auxílio funeral e seguro de vida. 
</p>

<p style="font-size: 9pt; text-align: justify;">
11. Para comunicado de sinistro o associado deverá entrar em contato com a seguradora pelo telefone 0800-026-0909 e informar o número da apólice, nome, CPF do assegurado.
</p>

<p style="font-size: 9pt; text-align: justify;">
12.  O beneficiário terá que dar entrada para receber o benefício do seguro de vida ou auxílio funeral até 10 dias do fato ocorrido.              
</p>

</div>
</div>

<div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">

<div class="uk-article">
<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">AUTORIZAÇÃO DE DÉBITO</h1>

<p style="font-size: 9pt; text-align: justify;">
13. A taxa de adesão de R$ <span style="font-size: 8pt; text-align: justify; margin-bottom: 5px;"><strong><?php echo "R$ ". number_format($dadosassociado[0]->tx_adesao,2,',','.'); ?></strong> ( <?php echo tool::valorPorExtenso($dadosassociado[0]->tx_adesao) ; ?> )</span>, deverá ser paga no ato da assinatura </p>

<p style="font-size: 9pt; text-align: justify; line-height: 35px;">
14. Em adiantamento ao contrato supra, o titular autoriza o débito para quitação do contrato de:
<br /> - (12) (doze) meses, à vista, no valor de R$ <span style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">______________( _________________________________________________________________)</span> ou em
<br />
 - (12) (doze) parcelas mensais no valor de R$<span style="font-size: 8pt; text-align: justify; margin-bottom: 5px;"><strong>____________</strong> ( ______________________________________________________________________________) </span> <br />cada uma em favor da LÍDER COBRANÇAS E SERVIÇOS </p>

<p style="font-size: 9pt; text-align: justify;">
15. A forma de cobrança poderá ser feita através de boleto bancário, cartão de crédito, anual à vista. A mensalidade deverá estar em dia com a LÍDER COBRANÇAS E SERVIÇOS para poder usufruir dos serviços oferecidos pela UNICLÍNICA E MONGERAL AEGON SEGUROS E PREVIDÊNCIA, independente do uso.
</p>

<p style="font-size: 9pt; text-align: justify;">
16. Por ser verdade, assino esta Autorização de débito, que será renovada automaticamente por prazo indeterminado se não houver manifestação contraria por escrito a CONTRATADA com prazo mínimo de 30 dias (trinta) dias de antecedência.
</p>

<p style="font-size: 9pt; text-align: justify;">
17. Esta Autorização de débito só poderá ser cancelada pelo titular, na sede da LÍDER COBRANÇAS E SERVIÇOS, Rua. Paranaíba, 66, Centro, Itumbiara, não cancelamos via telefone. A rescisão poderá ocorrer Dentro do prazo legal de 7 (sete) dias a contar da data de assinatura desde contrato, nos termos do artigo 49 da lei 8078/1990 ou se quitadas as parcelas aqui autorizadas para pagamento dos 12 (doze) meses iniciais previstos no CONTRATO. Passando os 12 (doze) meses iniciais, a rescisão só poderá ser concretizada se as parcelas estiverem em dia.
</p>

<p style="font-size: 9pt; text-align: justify;">
18. O não recebimento do boleto bancário para pagamento das parcelas devidas pela (o) TITULAR, não justificará qualquer atraso no respectivo pagamento, devendo a (o) TITULAR solicitar segunda via do mesmo no setor Financeiro da CONTRATADA ou proceder conforme orientação e solicitação da mesma. 
</p>

<p style="font-size: 9pt; text-align: justify;">
19. É de responsabilidade da (o) TITULAR manter atualizado junto a CONTRATADA seu cadastro pessoal, endereço para correspondência e telefones para contato
</p>

<p style="font-size: 9pt; text-align: justify;">
20. Havendo atraso de pagamento de qualquer valor ou acréscimo previsto no presente instrumento, a CONTRATADA fica desde já autorizada a emitir Contra o TITULAR os títulos de créditos pertinentes; efetuar a cobrança pelos meios previstos na legislação comum aplicável, extrajudicialmente ou judicialmente; registrar débitos, nos valores exatos das respectivas parcelas, com todos os reajustes especificados neste contrato e quando for o caso, com acréscimos aqui previstos, nos órgãos de proteção ao crédito, bastando para tanto, em atendimento ao artigo 43 do Código de Defesa do Consumidor, informado a conseqüente inscrição no SPC, SERASA ou outro órgão do gênero, assim como poderá encaminhar ao Tabelião/Cartório de Protesto as parcelas referentes às mensalidades não pagas em seu vencimento. 
</p>


<p style="font-size: 9pt; text-align: justify;">
21. A presente cláusula configura anuência expressa do TITULAR para que a CONTRATADA assim proceda, não podendo, o inadimplente alegar qualquer dano pela inscrição de seu nome nos cadastros de proteção ao crédito ou por qualquer ação da CONTRATADA nos moldes acima. 
</p>

<p style="font-family:arial; width:98%; text-align:right; font-size:7pt; position:relative;" >Pagina 3</p>
<p style="font-size: 9pt; text-align: justify;">
22. A correção do valor autorizado será reajustada do mês de janeiro de cada ano com base no IGPM integral da FGV do ano anterior independentemente da data de assinatura, sendo esta divulgada em jornal de grande circulação no estado federativo onde se situa a CONTRATADA.
</p>

<p style="font-size: 9pt; text-align: justify;">
23. As partes elegem o fórum da Comarca de Itumbiara Estado de Goiás, para dirimir quaisquer dúvidas ou litígios oriundos do presente contrato.</p>

</div>
</div>
<div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">

<div class="uk-article">			
<div style="float:left;">
<h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">DETALHES DA COBRANÇA</h1>

					<div class="uk-article"> 

					<p style="font-size: 8pt;">Forma de pagamento: ___________________________</p>
					<p style="font-size: 8pt;">Dia de Vencimento: _____ de cada mês. </p>
					<p style="font-size: 8pt;">Contratante: _____________________________________</p>
					<p style="font-size: 8pt;">RG: </p>
					<p style="font-size: 8pt;">CPF: </p>

					</div>

					
</div>
<div style="float:right;">
<p style="font-size: 8pt; text-align: justify; margin-top: 30px; text-transform: capitalize;">

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

