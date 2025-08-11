<?php require_once"../../sessao.php"; ?>

<div class="tabs-spacer" style="display:none;">
  <?php
// blibliotecas
  require_once("../../conexao.php");
  $cfg->set_model_directory('../../models/');



  $FRM_aut_id      = isset( $_GET['autId'])      ? $_GET['autId']              : tool::msg_erros("O Campo id da autorização é Obrigatorio.");

  $dadosautorizacao = med_autorizacoes::find_by_sql("SELECT med_autorizacoes.*,
    CASE WHEN med_autorizacoes.dependente = '1' THEN (SELECT dependentes.nome FROM dependentes  WHERE id = med_autorizacoes.dependentes_id)
    ELSE (SELECT associados.nm_associado FROM associados  WHERE matricula = med_autorizacoes.matricula) END AS  solicitante,
    CASE WHEN med_autorizacoes.dependente = '1' THEN (SELECT dependentes.dt_nascimento FROM dependentes  WHERE id = med_autorizacoes.dependentes_id)
    ELSE (SELECT associados.dt_nascimento FROM associados  WHERE matricula = med_autorizacoes.matricula) END AS  datanasc,
    med_procedimentos.vlr_custo, med_procedimentos.tx_adm,
    Count(med_proc_autorizacoes.med_autorizacoes_id) AS total,
    (SUM(med_procedimentos.vlr_custo) + SUM(med_procedimentos.tx_adm)) AS vlr_total,
    convenios.razao_social AS nm_convenio,
    empresas.logomarca,empresas.razao_social  AS nm_empresa, empresas.cnpj,empresas.nm_fantasia AS nm_fantasia_emp,
    logradouros.descricao as nm_logradouro,
    estados.sigla AS nm_estado,
    cidades.descricao AS nm_cidade,
    bairros.descricao AS nm_bairro,
    logradouros.complemento,
    associados.num AS num_casa,
    med_parceiros.id AS cod_parceiro,
    med_parceiros.compl_end AS complemento_end,
    med_parceiros.num AS num_par,
    med_parceiros.local_pgto AS local_pgto,
    CASE WHEN med_parceiros.tp_parceiro = 'F' THEN med_parceiros.nm_parceiro ELSE med_parceiros.razao_social END AS  nm_parceiro,
    med_proc_autorizacoes.med_procedimentos_id AS cod_pro,
    med_procedimentos.descricao AS desc_pro,
    med_especialidades.descricao AS desc_esp,
    lograd_par.descricao AS logradouro_med,
    bair_par.descricao AS bairro_med,
    cid_par.descricao AS cidade_med,
    uf_par.sigla as uf_med

    FROM
    med_autorizacoes 
    LEFT JOIN med_proc_autorizacoes ON med_proc_autorizacoes.med_autorizacoes_id = med_autorizacoes.id 
    LEFT JOIN med_procedimentos ON med_procedimentos.id = med_proc_autorizacoes.med_procedimentos_id
    LEFT JOIN convenios ON med_autorizacoes.convenios_id = convenios.id
    LEFT JOIN empresas ON med_autorizacoes.empresas_id = empresas.id
    LEFT JOIN associados ON associados.matricula = med_autorizacoes.matricula
    LEFT JOIN logradouros ON logradouros.id = associados.logradouros_id
    LEFT JOIN estados ON estados.id = logradouros.estados_id
    LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
    LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
    LEFT JOIN med_parceiros ON med_parceiros.id = med_autorizacoes.med_parceiros_id
    LEFT JOIN med_especialidades ON med_especialidades.id = med_autorizacoes.med_especialidades_id
    LEFT JOIN logradouros lograd_par ON lograd_par.id = med_parceiros.logradouros_id
    LEFT JOIN estados uf_par ON uf_par.id = lograd_par.estados_id
    LEFT JOIN cidades cid_par ON cid_par.id = lograd_par.cidades_id
    LEFT JOIN bairros bair_par ON bair_par.id = lograd_par.bairros_id

    WHERE
    med_autorizacoes.id='".$FRM_aut_id."'");

  $Via_guia = 1;


  ?>
</div>

<div id="areaprint" style=" height:510px; width: 945px; overflow-y:auto; background-color:#fff; margin:0; padding:2px; ">


  <link rel="stylesheet" type="text/css"  media="print"  href="framework/uikit-2.24.0/css/uikit.css?<?php echo microtime(); ?>">
  <style>

    @page {margin: 0.5cm;}

    .uk-grid {padding:0px; padding-left:2px;}
    .uk-grid div{padding:0px; padding-left:8px;}
    .uk-panel-box{border-radius:0; border-color:#666;background-color:transparent;}
    .uk-panel-box .span1{font-size:.5em; padding-left:1px;}
    .uk-panel-box .span2{font-size:.7em; padding-left:2px;}
    .uk-panel-box .br{height: 7em;}
    .header{font-size:.6em; font-weight:bold;}
    .uk-text-uppercase{text-transform: uppercase;}

  </style> 

  <div id="Area_guia1" style="display:block; padding:5px; border:0px solid #ccc;">

    <div class="uk-grid uk-grid-small " data-uk-grid-match >

      <div class="uk-width-3-10">
        <div class="uk-panel uk-panel-box" style="border:0;">
          <div class="uk-grid uk-grid-small" style="padding-top: 15px;">
            <span class="uk-width-8-10" style="font-size: 3em;"><?php echo $dadosautorizacao[0]->nm_fantasia_emp;?></span>
            <span class="span2" ><?php echo $dadosautorizacao[0]->nm_empresa;?>  </span>
            <span class="span2"> <?php echo tool::MascaraCampos("??.???.???/????-??",$dadosautorizacao[0]->cnpj);?> </span>
          </div>
        </div>
      </div>

      <div class="uk-width-5-10 uk-margin">

        <div class="uk-panel uk-panel-box uk-text-left" style="border:0; font-size:14px;  padding-left: 100px;"  >
          <strong>AUTORIZAÇÃO</strong>
        </div>
        <div class="uk-width-2-10"> </div>
      </div>

      <div class="uk-width-2-10" >
       <div class="uk-panel uk-panel-box">
        <span class="span2">1º Via</span>
        <span class="span2">Solicitante</span>
      </div>
      <div class="uk-panel uk-panel-box">
        <span class="span1">Data emissão</span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2">
          <?php  
          $now = new ActiveRecord\DateTime($dadosautorizacao[0]->dt_inclusao);
          echo $now->format('d/m/Y');  
          ?>

        </span>             
      </div>
    </div>

    <div class="uk-width-3-10 uk-margin">
      <div class="uk-panel uk-panel-box">
        <span class="span1">1- Local de pagamento </span><br>
        <span class="span2 uk-text-uppercase uk-text-bold"><?php if($dadosautorizacao[0]->local_pgto == 0 ){echo "Autorização paga para o profissional.";}else{echo "Autorização paga no emissor.";}?></span>            
      </div>
    </div>
    <div class="uk-width-5-10 uk-margin">
      <div class="uk-panel uk-panel-box">
        <span class="span1">2- Filiação </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->nm_convenio;?> </span>            
      </div>
    </div>

    <div class="uk-width-2-10" style="padding-top: 15px;">
      <div class="uk-panel uk-panel-box">
        <span class="span1">3- Autorização nº </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2"><?php echo tool::CompletaZeros(3,$dadosautorizacao[0]->empresas_id).".".tool::CompletaZeros(3,$dadosautorizacao[0]->convenios_id).".".tool::CompletaZeros(11,$dadosautorizacao[0]->matricula).".".$dadosautorizacao[0]->id;?></span>             
      </div>
    </div>

  </div> 

  <span class="header">Dados Solicitante</span><br>

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box">
        <span class="span1">4- Matricula nº </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2 "><?php echo tool::CompletaZeros(11,$dadosautorizacao[0]->matricula);?> </span>             
      </div>
    </div>
    <div class="uk-width-6-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">5- Nome </span><br>
        <span class="span2  uk-text-uppercase"><?php echo $dadosautorizacao[0]->solicitante;?> ( <?php if($dadosautorizacao[0]->dependente == 1){echo "Dependente";}else{echo "Titular";} ?> )</span>            
      </div>
    </div>
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box">
        <span class="span1">6- Data Nascimento </span><br>
        <span class="span2">
          <?php 
          $now = new ActiveRecord\DateTime($dadosautorizacao[0]->datanasc);
          echo $now->format('d/m/Y ');  
          ?>
        </span>             
      </div>
    </div>
  </div>
  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-4-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">7- Endereço </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2 uk-text-uppercase"><?php if($dadosautorizacao[0]->complemento != 'NULL'){echo $dadosautorizacao[0]->complemento.' - ';} echo utf8_encode($dadosautorizacao[0]->nm_logradouro);?></span>          
      </div>
    </div>
    <div class="uk-width-1-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">8- Numero </span><br>
        <span class="span2"><?php echo $dadosautorizacao[0]->num_casa;?></span>            
      </div>
    </div>
    <div class="uk-width-3-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">9- Bairro </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->nm_bairro;?></span>
      </div>
    </div>
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">10- Cidade/UF </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->nm_cidade.'/'.$dadosautorizacao[0]->nm_estado;?></span>
      </div>
    </div>  
  </div>

  <span class="header">Dados Parceiro</span><br>

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">11- Cod parceiro </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2"><?php echo tool::CompletaZeros(11, $dadosautorizacao[0]->cod_parceiro);?></span>             
      </div>
    </div>
    <div class="uk-width-8-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">12- Parceiro </span><br>
        <span class="span2 uk-text-uppercase "><?php echo $dadosautorizacao[0]->nm_parceiro;?></span>            
      </div>
    </div>
  </div>
  <span class="header">Dados do Atendimento</span><br>
  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">13- Especialidade </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->desc_esp; ?></span>             
      </div>
    </div>
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">14- Tipo atendimento </span><br>
        <span class="span2 uk-text-uppercase"><?php echo "Consulta"; ?></span>            
      </div>
    </div>            
    <div class="uk-width-6-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">15- Profissional </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->desc_pro;?></span>            
      </div>
    </div>
  </div>

  <span class="header">Local de Atendimento</span> 

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-4-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">16- Endereço </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2 uk-text-uppercase"> <?php if($dadosautorizacao[0]->complemento != 'NULL'){echo $dadosautorizacao[0]->complemento_end.' - ';} echo utf8_encode($dadosautorizacao[0]->logradouro_med);?> </span>          
      </div>
    </div>
    <div class="uk-width-1-10"> 
      <div class="uk-panel uk-panel-box" >
        <span class="span1">17- Numero </span><br>
        <span class="span2"><?php echo $dadosautorizacao[0]->num_par;?></span>            
      </div>
    </div>
    <div class="uk-width-3-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">18- Bairro </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->bairro_med;?></span>
      </div>
    </div>
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">19- Cidade/UF </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->cidade_med."/".$dadosautorizacao[0]->uf_med;?></span>
      </div>
    </div>  
  </div>
<!-- 
  <span class="header">Observações</span> 

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-10-10">
      <div class="uk-panel uk-panel-box" style=" background-color:transparent; padding:0px; height:50px; ">

      </div>
    </div>
  </div>    
  ///////////////////////////////////////////// FINAL BODY/////////////////////////////////////////////////////////////////// -->

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-5-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">20- Assinatura Emissor</span><br><br>
      </div>
    </div>
    <div class="uk-width-5-10">
      <div class="uk-panel uk-panel-box" style=" background-color:transparent; padding:0px;  ">
        <span class="span1">21- Assinatura Paciente</span><br><br>
      </div>
    </div>
  </div>    
</div>

<span style="font-size:.7em; margin: 0; padding: 0; margin-left: 45%; ">Corte aqui </span>

<hr style="border:1px dashed #666; margin: 0; padding:0;margin-bottom: 10px;">


<div id="Area_guia2" style="display:block; padding:5px; border:0px solid #ccc;">

  <div class="uk-grid uk-grid-small " data-uk-grid-match >

      <div class="uk-width-3-10">
        <div class="uk-panel uk-panel-box" style="border:0;">
          <div class="uk-grid uk-grid-small" style="padding-top: 15px;">
            <span class="uk-width-8-10" style="font-size: 3em;"><?php echo $dadosautorizacao[0]->nm_fantasia_emp;?></span>
            <span class="span2"><?php echo $dadosautorizacao[0]->nm_empresa;?>  </span>
            <span class="span2"> <?php echo tool::MascaraCampos("??.???.???/????-??",$dadosautorizacao[0]->cnpj);?> </span>
          </div>
        </div>
      </div>

      <div class="uk-width-5-10 uk-margin">

        <div class="uk-panel uk-panel-box uk-text-left" style="border:0; font-size:14px;  padding-left: 100px;"  >
          <strong>AUTORIZAÇÃO</strong>
        </div>
        <div class="uk-width-2-10"> </div>
      </div>

      <div class="uk-width-2-10" >
       <div class="uk-panel uk-panel-box">
        <span class="span2">2º Via</span>
        <span class="span2">Emissor</span>
      </div>
      <div class="uk-panel uk-panel-box">
        <span class="span1">Data emissão</span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2">
          <?php  
          $now = new ActiveRecord\DateTime($dadosautorizacao[0]->dt_inclusao);
          echo $now->format('d/m/Y');  
          ?>

        </span>             
      </div>
    </div>

    <div class="uk-width-3-10 uk-margin">
      <div class="uk-panel uk-panel-box">
        <span class="span1">1- Local de pagamento </span><br>
        <span class="span2 uk-text-uppercase uk-text-bold"><?php if($dadosautorizacao[0]->local_pgto == 0 ){echo "Autorização paga para o profissional.";}else{echo "Autorização paga no emissor.";}?> </span>            
      </div>
    </div>
    <div class="uk-width-5-10 uk-margin">
      <div class="uk-panel uk-panel-box">
        <span class="span1">2- Filiação </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->nm_convenio;?> </span>            
      </div>
    </div>

    <div class="uk-width-2-10" style="padding-top: 15px;">
      <div class="uk-panel uk-panel-box">
        <span class="span1">3- Autorização nº </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2"><?php echo tool::CompletaZeros(3,$dadosautorizacao[0]->empresas_id).".".tool::CompletaZeros(3,$dadosautorizacao[0]->convenios_id).".".tool::CompletaZeros(11,$dadosautorizacao[0]->matricula).".".$dadosautorizacao[0]->id;?></span>             
      </div>
    </div>

  </div> 

  <span class="header">Dados Solicitante</span><br>

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box">
        <span class="span1">4- Matricula nº </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2 "><?php echo tool::CompletaZeros(11,$dadosautorizacao[0]->matricula);?> </span>             
      </div>
    </div>
    <div class="uk-width-6-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">5- Nome </span><br>
        <span class="span2  uk-text-uppercase"><?php echo $dadosautorizacao[0]->solicitante;?> ( <?php if($dadosautorizacao[0]->dependente == 1){echo "Dependente";}else{echo "Titular";} ?> )</span>            
      </div>
    </div>
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box">
        <span class="span1">6- Data Nascimento </span><br>
        <span class="span2">
          <?php 
          $now = new ActiveRecord\DateTime($dadosautorizacao[0]->datanasc);
          echo $now->format('d/m/Y ');  
          ?>
        </span>             
      </div>
    </div>
  </div>
  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-4-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">7- Endereço </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2 uk-text-uppercase"><?php if($dadosautorizacao[0]->complemento != 'NULL'){echo $dadosautorizacao[0]->complemento.' - ';} echo utf8_encode($dadosautorizacao[0]->nm_logradouro);?></span>          
      </div>
    </div>
    <div class="uk-width-1-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">8- Numero </span><br>
        <span class="span2"><?php echo $dadosautorizacao[0]->num_casa;?></span>            
      </div>
    </div>
    <div class="uk-width-3-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">9- Bairro </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->nm_bairro;?></span>
      </div>
    </div>
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">10- Cidade/UF </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->nm_cidade.'/'.$dadosautorizacao[0]->nm_estado;?></span>
      </div>
    </div>  
  </div>

  <span class="header">Dados Parceiro</span><br>

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">11- Cod parceiro </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2"><?php echo tool::CompletaZeros(11, $dadosautorizacao[0]->cod_parceiro);?></span>             
      </div>
    </div>
    <div class="uk-width-8-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">12- Parceiro </span><br>
        <span class="span2 uk-text-uppercase "><?php echo $dadosautorizacao[0]->nm_parceiro;?></span>            
      </div>
    </div>
  </div>
  <span class="header">Dados do Atendimento</span><br>
  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">13- Especialidade </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->desc_esp; ?></span>             
      </div>
    </div>
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">14- Tipo atendimento </span><br>
        <span class="span2 uk-text-uppercase"><?php echo "Consulta"; ?></span>            
      </div>
    </div>            
    <div class="uk-width-6-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">15- Profissional </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->desc_pro;?></span>            
      </div>
    </div>
  </div>

  <span class="header">Local de Atendimento</span> 

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-4-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">16- Endereço </span><br>
        <!-- guia constituida de cdconvenio.cdpciente.cdexame -->
        <span class="span2 uk-text-uppercase"> <?php if($dadosautorizacao[0]->complemento != 'NULL'){echo $dadosautorizacao[0]->complemento_end.' - ';} echo utf8_encode($dadosautorizacao[0]->logradouro_med);?> </span>          
      </div>
    </div>
    <div class="uk-width-1-10"> 
      <div class="uk-panel uk-panel-box" >
        <span class="span1">17- Numero </span><br>
        <span class="span2"><?php echo $dadosautorizacao[0]->num_par;?></span>            
      </div>
    </div>
    <div class="uk-width-3-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">18- Bairro </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->bairro_med;?></span>
      </div>
    </div>
    <div class="uk-width-2-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">19- Cidade/UF </span><br>
        <span class="span2 uk-text-uppercase"><?php echo $dadosautorizacao[0]->cidade_med."/".$dadosautorizacao[0]->uf_med;?></span>
      </div>
    </div>  
  </div>
<!-- 
  <span class="header">Observações</span> 

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-10-10">
      <div class="uk-panel uk-panel-box" style=" background-color:transparent; padding:0px; height:50px; ">

      </div>
    </div>
  </div>    
  ///////////////////////////////////////////// FINAL BODY/////////////////////////////////////////////////////////////////// -->

  <div class="uk-grid uk-grid-small " data-uk-grid-match >
    <div class="uk-width-5-10">
      <div class="uk-panel uk-panel-box" >
        <span class="span1">20- Assinatura Emissor</span><br><br>
      </div>
    </div>
    <div class="uk-width-5-10">
      <div class="uk-panel uk-panel-box" style=" background-color:transparent; padding:0px;  ">
        <span class="span1">21- Assinatura Paciente</span><br><br>
      </div>
    </div>
  </div>    
</div>
</div>
<!-- inicio div botão de impressão -->
<div style="height:26px; padding:6px;width:938px;  position:absolute; bottom:31px; text-align:right;border:0; border-top:1px solid #ccc;margin:auto;" class="uk-gradient-cinza" >
  <div style="float: right;">
    <a href="JavaScript:void(0);" id="BtnImprimirGuia" class="uk-button uk-button-primary" ><i class="uk-icon-print" ></i> Imprimir</a>
  </div>
</div>
<script type="text/javascript">
  $(function(){
// Hook up the print link.
$("#BtnImprimirGuia").click(function(){
    // Print the DIV.
    $( "#areaprint" ).print();
    // Cancel click event.
    return( false );
  });
});
// mensagen de carregamento
jQuery("#msg_loading").html(" Carregando ");
//abre a tela de preload
modal.hide();

</script>