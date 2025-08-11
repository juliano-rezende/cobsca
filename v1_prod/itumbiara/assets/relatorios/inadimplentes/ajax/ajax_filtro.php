<div class="uk-modal-dialog" style="width:500px;">
  <!--<button type="button" class="uk-modal-close uk-close"></button> -->
  <div class="uk-modal-header ">
      <h2><i class="uk-icon-filter uk-icon-small" ></i> Filtro Inadimplentes</h2>
  </div>
  <form name="FrmFiltroInad" method="post" id="FrmFiltroInad" class="uk-form">
    <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">

        <label id="lab02"><!--pesquisar por convenio -->
            <span>Convênio</span>
            <select name="convenio_id" class="select" id="convenio_id" >
                <option value="" selected></option>
                <?php
                $convenio=convenios::find('all',array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
                $listaconvenios= new ArrayIterator($convenio);
                while($listaconvenios->valid()):
                    echo'<option value="'.$listaconvenios->current()->id.'" >'.utf8_encode($listaconvenios->current()->nm_fantasia).'</option>';
                    $listaconvenios->next();
                endwhile;
                ?>
            </select>
        </label>

        <label>
            <!--            data-uk-datepicker="{format:'DD/MM/YYYY'}"-->
            <span>Pesquisar por</span>
            <div class="uk-form-icon">
                <select name="pesquisar_por" class="select w_200" id="pesquisar_por">
                    <option value="ANO" selected>Ano de cadastro</option>
                    <option value="PERIODO">Data de vencimento da parcela</option>
                </select>
            </div>
        </label>

        <label class="vencimento">
<!--data-uk-datepicker="{format:'DD/MM/YYYY'}"-->
            <span>Vencimento Inicial</span>
            <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i>
                <input name="dtini" value="<?php echo date("d/m/Y"); ?>"  type="text" class="input_text w_100 center periodo" id="dtini"/>
            </div>
        </label>
        <label class="vencimento">
            <span>Vencimento Final</span>
            <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i>
                <input name="dtfim" value="<?php echo date("d/m/Y"); ?>"  type="text" class="input_text w_100 center periodo" id="dtfim"/>
            </div>
        </label>
        <label class="ano">
            <span>Ano</span>
            <div class="uk-form-icon">
                <input name="ano_pesquisa" type="text" class="input_text w_100 center" id="ano_pesquisa" placeholder="0000"/>
            </div>
        </label>

    </fieldset>

</form>
<div class="uk-modal-footer uk-text-right">
    <a href="JavaScript:void(0);" id="Btn_tt_00" class="uk-button uk-button-small" ><i class="uk-icon-search" ></i> Visualizar</a>
    <a href="JavaScript:void(0);" id="Btn_tt_02" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
</div>
</div>

<script type="text/javascript">

    $(".vencimento").hide();
    
    $("#pesquisar_por").change(function () {
        
        let $this = $(this);
        
        if($this.val() == "ANO"){

            $(".ano").show();
            $(".vencimento").hide();
            
        }else{
            
            $(".ano").hide();
            $(".vencimento").show();
        }

    });
    

    jQuery("#dtini,#dtfim").mask("99/99/9999");

    jQuery(function() {

     jQuery("#Btn_tt_00").click(function(event) {


       /* mensagen de carregamento*/
       jQuery("#msg_loading").html("Pesquisando ");

       /*abre a tela de preload*/
       modal.show();

       /*desabilita o envento padrao do formulario*/
       event.preventDefault();
      
       console.log(jQuery("#FrmFiltroInad").serialize());

       jQuery.ajax({
        async: true,
        url: "assets/relatorios/inadimplentes/ajax_grid.php",
        type: "post",
        data:jQuery("#FrmFiltroInad").serialize(),
        success: function(resultado) {
           /*abre a tela de preload*/
           jQuery("#GridVeiw").html(resultado);
           /*abre a tela de preload*/
           modal.hide();
       },
       error:function (){
         UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
         modal.hide();
     }

 });

   });

 });

</script>