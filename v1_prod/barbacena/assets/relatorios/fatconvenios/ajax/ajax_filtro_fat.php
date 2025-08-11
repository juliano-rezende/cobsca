<div class="uk-modal-dialog" style="width:500px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header ">
		<h2><i class="uk-icon-filter uk-icon-small" ></i> Filtro</h2>
		</div>
                <form name="FrmFiltroRelFatConv" method="post" id="FrmFiltroRelFatConv" class="uk-form"> 
                <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
                
				<label id="labconv"><!--pesquisar por convenio --> 
                <span>Convênio</span> 
                <select name="cdconvenio" class="select" id="cdconvenio" >
                <option value="" selected>Todos</option>
                <?php
                    $convenio=convenios::find('all',array('conditions'=>array('ativo= ? AND cdempresa= ?','S', $SCM_Id_empresa)));
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
					 $formas=formaspagamento::find('all',array('conditions'=>array('indice= ? ',1)));
					 $descformas= new ArrayIterator($formas);
					 while($descformas->valid()):
					 echo'<option value="'.$descformas->current()->cdformapagamento.'" >'.utf8_encode($descformas->current()->descricao).'</option>';
					 $descformas->next();
					 endwhile;
                ?>
                </select>    
                </label>

                
                <label> 
                <span>Situação</span> 
                <select name="status" class="select " id="status">
                    <option value="" selected="selected">Todos</option>
                    <option value="0">A Faturar</option>
                    <option value="1">Faturadas</option>
                    <option value="2">Canceladas</option>
                </select>
                </label>

                <label> 
                <span>Dt Venc inicial</span>
                <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i>
                <input name="dataini"   type="text" class="input_text w_100 center" id="dataini" data-uk-datepicker="{format:'DD/MM/YYYY'}"    />
                </div>
                </label>
                
                <label> 
                <span>Dt Venc Final</span> 
                <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i>
                <input name="datafim"   type="text" class="input_text w_100 center " id="datafim" data-uk-datepicker="{format:'DD/MM/YYYY'}"    />
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
	
jQuery("#dataini").mask("99/99/9999");
jQuery("#datafim").mask("99/99/9999");

// aciona o botao pesquisar
$("#BtnPesquisar").click(function (){

var cdconvenio=jQuery("#cdconvenio").val();// id da conta que sera pesquisado
var fpgto=jQuery("#fpgto").val();// id da conta que sera pesquisado
var status=jQuery("#status").val();// id da conta que sera pesquisado
var dataini=jQuery("#dataini").val();// id da conta que sera pesquisado
var datafim=jQuery("#datafim").val();// id da conta que sera pesquisado

if(cdconvenio==""){UIkit.modal.alert('Selecione um Convênio!');exit;}


LoadContent('assets/relatorios/fatconvenios/ajax_grid_fat.php?cdconvenio='+cdconvenio+'&fpgto='+fpgto+'&status='+status+'&dataini='+dataini+'&datafim='+datafim+'','Grid_Faturamentos');

});


$("#BtnPrint").click(function (){

var cdconvenio=jQuery("#cdconvenio").val();// id da conta que sera pesquisado
var fpgto=jQuery("#fpgto").val();// id da conta que sera pesquisado
var status=jQuery("#status").val();// id da conta que sera pesquisado
var dataini=jQuery("#dataini").val();// id da conta que sera pesquisado
var datafim=jQuery("#datafim").val();// id da conta que sera pesquisado

if(cdconvenio==""){UIkit.modal.alert('Selecione um Convênio!');exit;}


//abre a tela de preload
modal.show();

New_window('980','530','Imprimir Grid','assets/relatorios/fatconvenios/faturamento_print.php?cdconvenio='+cdconvenio+'&fpgto='+fpgto+'&status='+status+'&dataini='+dataini+'&datafim='+datafim+'');

});

</script>