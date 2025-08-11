<?php
include("../../sessao.php");
echo'<div class="tabs-spacer" style="display:none;">';

include("../../conexao.php");
$cfg->set_model_directory('../../models/');

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
                                           WHERE dependentes.matricula='".$FRM_matricula."' ");

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

<div id="print_c" style=" height: 505px; width:100%; overflow:auto; padding:0; margin:0 auto;background:#fff; ">

<p style="font-family:arial; width:98%; text-align:right; font-size:7pt; position:relative;" >Pagina 1</p>

<div id="big_col_contrato" style="border:0; width: 98%; margin: 0 auto;">
    <div id="col_left_header" style="float: left; height:80px; width: 33%; ">
      <div id="col_logo" style="width: 200px; float: left; margin: 10px 10px;">
      </div>
    </div>
    <div id="col_center_header" style="float: left; height:80px; width: 64%; text-align: center; padding-top: 5px;">
      <div class="uk-article">
        <p style="font-size: 9pt; text-align: justify; margin-bottom: 5px; font-size: 16pt; font-weight: bold;">
        TERMO DE CANCELAMENTO
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
            <td width="11%" align="center" class="titulo">Nº Contrato</td>
            <td width="11%" align="center" class="titulo">Matricula</td>
            <td width="13%" align="center" class="titulo">Data Cadastro</td>
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
            <td><?php echo strtoupper($dadosassociado[0]->nm_cidade); ?></td>
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
<div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">

    <div class="uk-article">

      <p style="font-size: 9pt; text-align: justify;">
      Como CONTRATANTE dos beneficios acima identificado, solicito a exclusão a partir desta data os descritos relacionados a seguir:
      </p>
    </div>

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

<div id="colclausulas" style="border:0; height: auto; width: 98%; margin: 0px auto;">

      <div class="uk-article">
        <h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">Declaro estar ciente que está solicitação com as seguintes responsábilidades:</h1>
        <p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
        1) O desligamento do benefício resulta na imediata quitação dos debitos pendentes ou remanecentes em qualquer um dos recursos.
        </p>

        <p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
        2) O CONTRATANTE é plenamente responsável pelo pagamento integral de despesas que resultem no uso indevido, por parte dele próprio ou de seus dependentes após a,formalização deste pedido.
        </p>

        <p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
        Portanto, na condição de CONTRATANTE autorizo a cobrança dos valores devidos em folha de pagamento, ou na impossibilidade de utilização desse meio de pagamento o associado deve efetuar o pagamento na hora em espécie ou em outras formas dispónives na ocasião.
        </p>
    </div>


      <div class="uk-article" style="margin-top: 50px; border: 1px solid #f5f5f5;">
        <p style="font-size: 8pt; text-align: justify; margin-top: 30px; text-transform: capitalize;">
          <?php echo strtolower($dadosassociado[0]->cidade_emp." ".tool::DataAtualExtenso(1)); ?>
        </p>
        <p style="font-size: 8pt; text-align: justify; margin-top: 50px;">Assinatura e CPF do contratante.</p>
        <p style="font-size: 8pt; text-align: justify; margin-top: 5px;">________________________________</p>
        <p style="font-size: 8pt; text-align: justify; margin-top: 50px;">Assinatura e CPF do atendente.</p>
        <p style="font-size: 8pt; text-align: justify; margin-top: 5px;">________________________________</p>
      </div>


</div>



</div> <!-- final print -->



<script type="text/javascript">

function Print(action){

  $( "#print_c" ).print();

}



</script>

