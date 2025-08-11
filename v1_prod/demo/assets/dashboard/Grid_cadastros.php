<button type="button" class="uk-modal-close uk-close"></button>
<div class="tabs-spacer" style="display:none;">

<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');


/*
DEFINE OS DADOS DA PAGINAÇÃO
*/

$A  = isset( $_GET['year'])  ? $_GET['year'] : date("Y");


/* query das recebimentos */
$Query_fat="SELECT SQL_CACHE

(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-01-01' AND '".$A."-01-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rjan,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-02-01' AND '".$A."-02-29' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rfev,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-03-01' AND '".$A."-03-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rmar,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-04-01' AND '".$A."-04-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rabr,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-05-01' AND '".$A."-05-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rmai,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-06-01' AND '".$A."-06-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rjun,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-07-01' AND '".$A."-07-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rjul,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-08-01' AND '".$A."-08-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rago,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-09-01' AND '".$A."-09-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rset,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-10-01' AND '".$A."-10-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rout,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-11-01' AND '".$A."-11-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rnov,
(SELECT count(matricula) FROM associados WHERE dt_cadastro between '".$A."-12-01' AND '".$A."-12-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rdez
FROM associados WHERE empresas_id='".$COB_Empresa_Id."'";

//echo $Query_fat;
$Q_pa_pgto=associados::find_by_sql($Query_fat);


?>
</div>
		<div class="uk-modal-header"> <i class="uk-icon-table" ></i> Visão Geral de Novos Contratos.</div>
			<div class="uk-modal-body ">
			<div class="uk-grid uk-grid-match uk-grid-det" data-uk-grid-match="{target:'.uk-panel'}" data-uk-grid-margin="">
				<div class="uk-width-medium-1-4 uk-row-first">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Janeiro
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-01"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rjan; ?>
						</h2>
					</div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Fevereiro
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-02"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rfev; ?>
						</h2>
					</div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Março
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-03"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rmar; ?>
						</h2>
					</div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Abril
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-04"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rabr; ?>
						</h2>
					</div>
				</div>
			</div>
			<div class="uk-grid uk-grid-match uk-grid-det" data-uk-grid-match="{target:'.uk-panel'}" data-uk-grid-margin="">
				<div class="uk-width-medium-1-4 uk-row-first">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Maio
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-05"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rmai; ?>
						</h2>
					</div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Junho
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-06"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rjun; ?>
						</h2>
					</div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Julho
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-07"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rjul; ?>
						</h2>
					</div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Agosto
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-08"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rago; ?>
						</h2>
					</div>
				</div>
			</div><div class="uk-grid uk-grid-match uk-grid-det" data-uk-grid-match="{target:'.uk-panel'}" data-uk-grid-margin="">
				<div class="uk-width-medium-1-4 uk-row-first">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Setembro
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-09"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rset; ?>
						</h2>
					</div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Outubro
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-10"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rout; ?>
						</h2>
					</div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Novembro
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-11"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rnov; ?>
						</h2>
					</div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="uk-panel uk-panel-box uk-text-center" style="min-height: 60px;padding-left: 5px;">
						<div class="uk-text-primary uk-text-bold" >
							Dezembro
						</div>
						<div style="height: 100%; width: 25px; border-left: 1px solid #f1f1f1; background-color: #f5f5f5; right: 0px; top:0;  position: absolute;"" >
						<a href="#" style="right: 1px; top:32%;  position: absolute;" uk-data-ref="<?php echo $A."-12"; ?>">
	         				<i class="uk-icon-angle-right uk-icon-large" style="position: absolute; right: 5px;"></i>
	         			</a>
	         			</div>
						<h2 class="uk-text-bold" >
							<?php echo $Q_pa_pgto[0]->rdez; ?>
						</h2>
					</div>
				</div>
			</div>

			</div>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_new_cad_prev" class="uk-button uk-button-primary" uk-data-year="<?php echo $A-1; ?>" style="right:200px; position: absolute;">
            <i class="uk-icon-angle-double-left" ></i> Anterior</a>
            <ul class="uk-pagination" style="position:absolute; right: 130px; margin:3px 0;" data-uk-pagination="{edges:1,items:1, itemsOnPage:1, currentPage:1}"><li><?php echo $A; ?></li></ul>
            <a href="JavaScript:void(0);" id="Btn_new_next" class="uk-button uk-button-primary" uk-data-year="<?php echo $A+1; ?>">Proximo <i class="uk-icon-angle-double-right" ></i></a>
		</div>

<script type="text/javascript">

/* detalhamento do recebimentos do mes*/
jQuery(".uk-grid-det a").click(function(event) {
        event.preventDefault();
        var ref = jQuery(this).attr("uk-data-ref");

        jQuery(".uk-modal-det").html('<i class="uk-icon-spinner uk-icon-spin"></i><span > Carregando </span>').load("assets/dashboard/Grid_ajax_cadastros.php?ref="+ref+"");

    });

/* botão retroceder year*/
jQuery("#Btn_new_cad_prev").click(function(event) {
        event.preventDefault();
        var year = jQuery(this).attr("uk-data-year");
		jQuery(".uk-modal-det").html('<i class="uk-icon-spinner uk-icon-spin"></i><span > Carregando </span>').load("assets/dashboard/Grid_cadastros.php?year="+year+"");
    });

/* botão avançar year*/
jQuery("#Btn_new_next").click(function(event) {
        event.preventDefault();
        var year = jQuery(this).attr("uk-data-year");
		jQuery(".uk-modal-det").html('<i class="uk-icon-spinner uk-icon-spin"></i><span > Carregando </span>').load("assets/dashboard/Grid_cadastros.php?year="+year+"");
    });

</script>