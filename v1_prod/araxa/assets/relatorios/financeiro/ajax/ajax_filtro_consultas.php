<div class="uk-modal-dialog" style="width:500px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header ">
		<h2><i class="uk-icon-filter uk-icon-small" ></i> Filtro</h2>
		</div>
                <form name="FrmFiltroConsultas" method="post" id="FrmFiltroConsultas"> 
                <fieldset style=" width:500px;border:0; padding:0; padding-top:0px;">
               
                <label> 
                <span>Pesquisar por</span> 
                <select name="pesquisarpor" class="select " id="pesquisarpor">
                    <option value="" selected="selected">Todos</option>
                    <option value="0">Data da Consulta</option>
                    <option value="1">Tipo(C/R)</option>
                    <option value="2">Convênio</option>
                    <option value="3">Formas de Pgto</option>
                    <option value="4">Parceiro</option>
                    <option value="5">Procedimento</option>
                    <option value="6">Especialidade</option>
                </select>
                </label>                
               
                <label id="labpar">  <!--pesquisar por parceiro -->
                <span>Parceiro</span> 
                <select name="cdparceiro" class="select" id="cdparceiro" >
                <option value="" selected>Todos</option>
                <?php
                    $parceiros=parceiros::find('all',array('conditions'=>array('ativo= ? AND cdempresa= ?','S', $SCM_Id_empresa)));
                    $listParceiro= new ArrayIterator($parceiros);
                    while($listParceiro->valid()):
                    echo'<option value="'.$listParceiro->current()->cdparceiro.'" >'.utf8_encode($listParceiro->current()->nome).'</option>';
                    $listParceiro->next();
                    endwhile;
                ?>
                </select>    
                </label>
                
				<label id="labtipo">  <!--pesquisar por tipo de consulta -->
                <span>Tipo</span> 
                <select name="tipo" class="select " id="tipo">
                    <option value="" selected >Todos</option>
                    <option value="R">Retornos</option>
                    <option value="C">Consultas</option>
                </select>
                </label>
                
				<label id="labconv"><!--pesquisar por convenio --> 
                <span>Convênio</span> 
                <select name="cdconvenio" class="select" id="cdconvenio" >
                <option value="" selected>Todos</option>
                <?php
                    $convenio=convenio::find('all',array('conditions'=>array('ativo= ? AND cdempresa= ?','S', $SCM_Id_empresa)));
                    $listaconvenios= new ArrayIterator($convenio);
                    while($listaconvenios->valid()):
                    echo'<option value="'.$listaconvenios->current()->cdconvenio.'" >'.utf8_encode($listaconvenios->current()->fantasia).'</option>';
                    $listaconvenios->next();
                    endwhile;
                ?>
                </select>    
                </label>
                
                <label id="labfpgto"><!--pesquisar por forma de pagamento --> 
                <span>Forma de Pagamento</span> 
                <select name="fpgto" class="select" id="fpgto" >
                <option value="" selected>Todos</option>
                <?php
					 $formas=formaspagamento::find('all', array('conditions' => array('indice = ?', '1')));
					 $descformas= new ArrayIterator($formas);
					 while($descformas->valid()):
					 echo'<option value="'.$descformas->current()->cdformapagamento.'" >'.$descformas->current()->descricao.'</option>';
					 $descformas->next();
					 endwhile;
                ?>
                </select>    
                </label>

                <label id="labproced"><!--pesquisar por procedimento --> 
                <span>Procedimento</span> 
                <select name="cdprocedimento" class="select" id="cdprocedimento" >
                <option value="" selected>Todos</option>
                <?php
					 $procconvenios=consultas::all(array('group' => 'cdprocedimentoconvenio'));
					 $desconv= new ArrayIterator($procconvenios);
					 while($desconv->valid()):
					 $descricaoprocvon=procedimentosconvenio::find_by_cdprocedimentoconvenio($desconv->current()->cdprocedimentoconvenio);
					 $descricaopro=procedimentos::find_by_cdprocedimento($descricaoprocvon->cdprocedimento);
					 echo'<option value="'.$desconv->current()->cdprocedimentoconvenio.'" >'.$descricaopro->descricao.'</option>';
					 $desconv->next();
					 endwhile;
                ?>
                </select>    
                </label>

                <label id="labesp"><!--pesquisar por forma de pagamento --> 
                <span>Especialidade</span> 
                <select name="cdespecialidade" class="select" id="cdespecialidade" >
                <option value="" selected>Todos</option>
                <?php
					 $especi=consultas::all(array('group' => 'cdespecialidade'));
					 $descespeci= new ArrayIterator($especi);
					 while($descespeci->valid()):
					 $descricaoesp=especialidades::find_by_cdespecialidade($descespeci->current()->cdespecialidade);

					 echo'<option value="'.$descespeci->current()->cdespecialidade.'" >'.$descricaoesp->descricao.'</option>';
					 $descespeci->next();
					 endwhile;
                ?>
                </select>    
                </label>

                
                <label> 
                <span>Situação</span> 
                <select name="pago" class="select " id="pago">
                    <option value="" selected="selected">Todos</option>
                    <option value="0">Pendentes</option>
                    <option value="1">Recebidas</option>
                    <option value="2">Faturadas</option>
                </select>
                </label>
                
                <label> 
                <span>Data Inicial</span> 
                <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i>
                <input name="periodoinicio" value="<?php echo date("d/m/Y"); ?>"  type="text" class="input_text w_100 center periodo" id="periodoinicio" data-uk-datepicker="{format:'DD/MM/YYYY'}"    />
                </div>
                </label>
                <label> 
                <span>Data Final</span>
                <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i> 
                <input name="periodofim" value="<?php echo date("d/m/Y"); ?>"  type="text" class="input_text w_100 center periodo" id="periodofim" data-uk-datepicker="{format:'DD/MM/YYYY'}"   />
                </div>
                </label>
                
                </fieldset>
                
                </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="BtnPesquisar" class="uk-button uk-button-small" ><i class="uk-icon-search" ></i> Pesquisar</a>
            <a href="JavaScript:void(0);" id="BtnCancelarFiltro" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
		</div>
</div>

<script type="text/javascript">
	
jQuery("#periodoinicio").mask("99/99/9999");
jQuery("#periodofim").mask("99/99/9999");

jQuery("#labpar").hide();	
jQuery("#labtipo").hide();	
jQuery("#labconv").hide();	
jQuery("#labfpgto").hide();
jQuery("#labproced").hide();
jQuery("#labesp").hide();	


// aciona o botao pesquisar
$("#BtnPesquisar").click(function (){

var cdparceiro=jQuery("#cdparceiro").val();// id da conta que sera pesquisado
var pesquisapor=jQuery("#pesquisarpor").val();// id da conta que sera pesquisado
var tipo=jQuery("#tipo").val();// id da conta que sera pesquisado
var cdconvenio=jQuery("#cdconvenio").val();// id da conta que sera pesquisado
var fpgto=jQuery("#fpgto").val();// id da conta que sera pesquisado
var cdprocedimento=jQuery("#cdprocedimento").val();// id da conta que sera pesquisado
var cdespecialidade=jQuery("#cdespecialidade").val();// id da conta que sera pesquisado
var pago=jQuery("#pago").val();// tipo se e entrada ou saida
var inicio=jQuery("#periodoinicio").val(); //data inicial
var fim=jQuery("#periodofim").val();// data final



LoadContent('assets/relatorios/consultas/ajax_grid_consultas.php?cdespecialidade='+cdespecialidade+'&cdprocedimento='+cdprocedimento+'&cdparceiro='+cdparceiro+'&tipo='+tipo+'&cdconvenio='+cdconvenio+'&fpgto='+fpgto+'&pago='+pago+'&dataini='+inicio+'&datafim='+fim+'','Grid_Consultas');

});


$("#BtnPrint").click(function (){

var cdparceiro=jQuery("#cdparceiro").val();// id da conta que sera pesquisado
var pesquisapor=jQuery("#pesquisarpor").val();// id da conta que sera pesquisado
var tipo=jQuery("#tipo").val();// id da conta que sera pesquisado
var cdconvenio=jQuery("#cdconvenio").val();// id da conta que sera pesquisado
var fpgto=jQuery("#fpgto").val();// id da conta que sera pesquisado
var cdprocedimento=jQuery("#cdprocedimento").val();// id da conta que sera pesquisado
var cdespecialidade=jQuery("#cdespecialidade").val();// id da conta que sera pesquisado
var pago=jQuery("#pago").val();// tipo se e entrada ou saida
var inicio=jQuery("#periodoinicio").val(); //data inicial
var fim=jQuery("#periodofim").val();// data final


//abre a tela de preload
modal.show();

New_window('900','500','Imprimir Grid','assets/relatorios/consultas/consultas_print.php?cdespecialidade='+cdespecialidade+'&cdprocedimento='+cdprocedimento+'&cdparceiro='+cdparceiro+'&tipo='+tipo+'&cdconvenio='+cdconvenio+'&fpgto='+fpgto+'&pago='+pago+'&dataini='+inicio+'&datafim='+fim+'');


});


jQuery("#pesquisarpor").change(function(){
	
/*
<option value="" selected="selected">Todos</option>
<option value="0">Data da Consulta</option>
<option value="1">Tipo(C/R)</option>
<option value="2">Convênio</option>
<option value="3">Formas de Pgto</option>
<option value="4">Parceiro</option>
*/	
	
	var pesquisarpor=jQuery(this).val();
	
	if(pesquisarpor==0){
		
		jQuery("#labpar").hide();	
		jQuery("#labtipo").hide();	
		jQuery("#labconv").hide();	
		jQuery("#labfpgto").hide();	
		jQuery("#labproced").hide();
		jQuery("#labesp").hide();	


	}if(pesquisarpor==1){
	
		jQuery("#labpar").hide();	
		jQuery("#labtipo").show();	
		jQuery("#labconv").hide();	
		jQuery("#labfpgto").hide();
		jQuery("#labproced").hide();
		jQuery("#labesp").hide();	
	
			
	}if(pesquisarpor==2){
		
		jQuery("#labpar").hide();	
		jQuery("#labtipo").hide();	
		jQuery("#labconv").show();	
		jQuery("#labfpgto").hide();
		jQuery("#labproced").hide();
		jQuery("#labesp").hide();	
	
		
	}if(pesquisarpor==3){
	
		jQuery("#labpar").hide();	
		jQuery("#labtipo").hide();	
		jQuery("#labconv").hide();	
		jQuery("#labfpgto").show();
		jQuery("#labproced").hide();
		jQuery("#labesp").hide();	
	
		
		
	}if(pesquisarpor==4){
		
		jQuery("#labpar").show();	
		jQuery("#labtipo").hide();	
		jQuery("#labconv").hide();	
		jQuery("#labfpgto").hide();
		jQuery("#labproced").hide();
		jQuery("#labesp").hide();	
	
		
	}if(pesquisarpor==5){
		
		jQuery("#labpar").hide();	
		jQuery("#labtipo").hide();	
		jQuery("#labconv").hide();	
		jQuery("#labfpgto").hide();
		jQuery("#labproced").show();
		jQuery("#labesp").hide();	
	
		
	}if(pesquisarpor==6){
		
		jQuery("#labpar").hide();	
		jQuery("#labtipo").hide();	
		jQuery("#labconv").hide();	
		jQuery("#labfpgto").hide();
		jQuery("#labproced").hide();
		jQuery("#labesp").show();	
		
	}

});

</script>