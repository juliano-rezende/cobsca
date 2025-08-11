<link rel="stylesheet" href="framework/uikit-2.24.0/css/components/accordion.min.css">
<script type="text/javascript" src="framework/uikit-2.24.0/js/components/accordion.min.js"></script>
<script type="text/javascript" src="framework/uikit-2.24.0/js/components/scrollspy.min.js"></script>

<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

$D=date("d");
$M=date("m");
$A=date("Y");


// recupera os dados do associado
$query_assoc=" SELECT count(matricula) as total_cancel,
		(SELECT count(matricula) FROM associados WHERE status ='1' and dt_cadastro between '".$A."-".$M."-01' and '".$A."-".$M."-31' and empresas_id='".$COB_Empresa_Id."') as total_novos,
		(SELECT count(id) FROM faturamentos  WHERE status ='1' and dt_pagamento between '".$A."-".$M."-01' and '".$A."-".$M."-31' and empresas_id='".$COB_Empresa_Id."') as titulos_pgto,
		(SELECT count(id) FROM faturamentos  WHERE status ='0' and dt_vencimento between '".$A."-".$M."-01' and '".$A."-".$M."-31' and empresas_id='".$COB_Empresa_Id."' and titulos_bancarios_id > 0) as titulos_abertos
		 FROM associados WHERE status='0' and dt_cancelamento between '".$A."-".$M."-01' and '".$A."-".$M."-".$D."' and empresas_id='".$COB_Empresa_Id."'";
$dadosassociado= associados::find_by_sql($query_assoc);


/* query para alimentar o grupos da header*/
$Q_U_receb=faturamentos::find_by_sql("SELECT SQL_CACHE
										faturamentos.id,faturamentos.titulos_bancarios_id,faturamentos.dt_pagamento,faturamentos.valor,
										faturamentos.valor_pago,associados.matricula,associados.nm_associado
									FROM faturamentos
									LEFT JOIN associados ON faturamentos.matricula= associados.matricula
									WHERE faturamentos.status='1' AND faturamentos.empresas_id='".$COB_Empresa_Id."' AND MONTH(faturamentos.dt_pagamento)  = '".$M."' and YEAR(faturamentos.dt_pagamento) ='".$A."'	ORDER BY faturamentos.dt_pagamento DESC LIMIT 20");
$list_parcelas= new ArrayIterator($Q_U_receb);


/* query das notificações */
$Q_notificacoes=notificacoes::find_by_sql("SELECT SQL_CACHE * FROM notificacoes WHERE status='0' ORDER BY id DESC limit 20");
$list_notif= new ArrayIterator($Q_notificacoes);

/* query das recebimentos */
$Query_fat="SELECT SQL_CACHE

(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-01-01' AND '".(date("Y")-1)."-01-31' AND empresas_id='".$COB_Empresa_Id."') as pjan16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-02-01' AND '".(date("Y")-1)."-02-29' AND empresas_id='".$COB_Empresa_Id."') as pfev16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-03-01' AND '".(date("Y")-1)."-03-31' AND empresas_id='".$COB_Empresa_Id."') as pmar16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-04-01' AND '".(date("Y")-1)."-04-31' AND empresas_id='".$COB_Empresa_Id."') as pabr16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-05-01' AND '".(date("Y")-1)."-05-31' AND empresas_id='".$COB_Empresa_Id."') as pmai16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-06-01' AND '".(date("Y")-1)."-06-31' AND empresas_id='".$COB_Empresa_Id."') as pjun16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-07-01' AND '".(date("Y")-1)."-07-31' AND empresas_id='".$COB_Empresa_Id."') as pjul16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-08-01' AND '".(date("Y")-1)."-08-31' AND empresas_id='".$COB_Empresa_Id."') as pago16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-09-01' AND '".(date("Y")-1)."-09-31' AND empresas_id='".$COB_Empresa_Id."') as pset16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-10-01' AND '".(date("Y")-1)."-10-31' AND empresas_id='".$COB_Empresa_Id."') as pout16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-11-01' AND '".(date("Y")-1)."-11-31' AND empresas_id='".$COB_Empresa_Id."') as pnov16,
(SELECT  count(id) FROM faturamentos WHERE dt_vencimento between '".(date("Y")-1)."-12-01' AND '".(date("Y")-1)."-12-31' AND empresas_id='".$COB_Empresa_Id."') as pdez16,

(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-01-01' AND '".(date("Y")-1)."-01-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rjan16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-02-01' AND '".(date("Y")-1)."-02-29' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rfev16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-03-01' AND '".(date("Y")-1)."-03-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rmar16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-04-01' AND '".(date("Y")-1)."-04-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rabr16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-05-01' AND '".(date("Y")-1)."-05-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rmai16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-06-01' AND '".(date("Y")-1)."-06-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rjun16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-07-01' AND '".(date("Y")-1)."-07-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rjul16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-08-01' AND '".(date("Y")-1)."-08-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rago16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-09-01' AND '".(date("Y")-1)."-09-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rset16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-10-01' AND '".(date("Y")-1)."-10-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rout16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-11-01' AND '".(date("Y")-1)."-11-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rnov16,
(SELECT  count(id) FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-12-01' AND '".(date("Y")-1)."-12-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rdez16,

(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-01-01' AND '".date("Y")."-01-31' AND empresas_id='".$COB_Empresa_Id."') as pjan17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-02-01' AND '".date("Y")."-02-29' AND empresas_id='".$COB_Empresa_Id."') as pfev17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-03-01' AND '".date("Y")."-03-31' AND empresas_id='".$COB_Empresa_Id."') as pmar17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-04-01' AND '".date("Y")."-04-31' AND empresas_id='".$COB_Empresa_Id."') as pabr17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-05-01' AND '".date("Y")."-05-31' AND empresas_id='".$COB_Empresa_Id."') as pmai17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-06-01' AND '".date("Y")."-06-31' AND empresas_id='".$COB_Empresa_Id."') as pjun17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-07-01' AND '".date("Y")."-07-31' AND empresas_id='".$COB_Empresa_Id."') as pjul17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-08-01' AND '".date("Y")."-08-31' AND empresas_id='".$COB_Empresa_Id."') as pago17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-09-01' AND '".date("Y")."-09-31' AND empresas_id='".$COB_Empresa_Id."') as pset17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-10-01' AND '".date("Y")."-10-31' AND empresas_id='".$COB_Empresa_Id."') as pout17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-11-01' AND '".date("Y")."-11-31' AND empresas_id='".$COB_Empresa_Id."') as pnov17,
(SELECT count(id) FROM faturamentos WHERE dt_vencimento between '".date("Y")."-12-01' AND '".date("Y")."-12-31' AND empresas_id='".$COB_Empresa_Id."') as pdez17,

(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-01-01' AND '".date("Y")."-01-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rjan17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-02-01' AND '".date("Y")."-02-29' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rfev17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-03-01' AND '".date("Y")."-03-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rmar17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-04-01' AND '".date("Y")."-04-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rabr17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-05-01' AND '".date("Y")."-05-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rmai17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-06-01' AND '".date("Y")."-06-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rjun17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-07-01' AND '".date("Y")."-07-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rjul17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-08-01' AND '".date("Y")."-08-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rago17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-09-01' AND '".date("Y")."-09-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rset17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-10-01' AND '".date("Y")."-10-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rout17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-11-01' AND '".date("Y")."-11-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rnov17,
(SELECT count(id) FROM faturamentos WHERE dt_pagamento between '".date("Y")."-12-01' AND '".date("Y")."-12-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."') as rdez17

FROM faturamentos WHERE dt_pagamento between '".(date("Y")-1)."-01-01' AND '".(date("Y")-1)."-01-31' AND status='1' AND empresas_id='".$COB_Empresa_Id."'";
//echo $Query_fat;
$Q_pa_pgto=faturamentos::find_by_sql($Query_fat);

$data_pfat_2016= $Q_pa_pgto[0]->pjan16.",".
				 $Q_pa_pgto[0]->pfev16.",".
				 $Q_pa_pgto[0]->pmar16.",".
				 $Q_pa_pgto[0]->pabr16.",".
				 $Q_pa_pgto[0]->pmai16.",".
				 $Q_pa_pgto[0]->pjun16.",".
				 $Q_pa_pgto[0]->pjul16.",".
				 $Q_pa_pgto[0]->pago16.",".
				 $Q_pa_pgto[0]->pset16.",".
				 $Q_pa_pgto[0]->pout16.",".
				 $Q_pa_pgto[0]->pnov16.",".
				 $Q_pa_pgto[0]->pdez16;

$data_rfat_2016= $Q_pa_pgto[0]->rjan16.",".
				 $Q_pa_pgto[0]->rfev16.",".
				 $Q_pa_pgto[0]->rmar16.",".
				 $Q_pa_pgto[0]->rabr16.",".
				 $Q_pa_pgto[0]->rmai16.",".
				 $Q_pa_pgto[0]->rjun16.",".
				 $Q_pa_pgto[0]->rjul16.",".
				 $Q_pa_pgto[0]->rago16.",".
				 $Q_pa_pgto[0]->rset16.",".
				 $Q_pa_pgto[0]->rout16.",".
				 $Q_pa_pgto[0]->rnov16.",".
				 $Q_pa_pgto[0]->rdez16;

$data_pfat_2017= $Q_pa_pgto[0]->pjan17.",".
				$Q_pa_pgto[0]->pfev17.",".
				$Q_pa_pgto[0]->pmar17.",".
				$Q_pa_pgto[0]->pabr17.",".
				$Q_pa_pgto[0]->pmai17.",".
				$Q_pa_pgto[0]->pjun17.",".
				$Q_pa_pgto[0]->pjul17.",".
				$Q_pa_pgto[0]->pago17.",".
				$Q_pa_pgto[0]->pset17.",".
				$Q_pa_pgto[0]->pout17.",".
				$Q_pa_pgto[0]->pnov17.",".
				$Q_pa_pgto[0]->pdez17;

$data_rfat_2017= $Q_pa_pgto[0]->rjan17.",".
				$Q_pa_pgto[0]->rfev17.",".
				$Q_pa_pgto[0]->rmar17.",".
				$Q_pa_pgto[0]->rabr17.",".
				$Q_pa_pgto[0]->rmai17.",".
				$Q_pa_pgto[0]->rjun17.",".
				$Q_pa_pgto[0]->rjul17.",".
				$Q_pa_pgto[0]->rago17.",".
				$Q_pa_pgto[0]->rset17.",".
				$Q_pa_pgto[0]->rout17.",".
				$Q_pa_pgto[0]->rnov17.",".
				$Q_pa_pgto[0]->rdez17;

/* emissão de carnê */
$next_month=date('Y-m-d', strtotime('+1 months', strtotime(date('Y-m-d'))));


$query_reserva=faturamentos::find_by_sql("SELECT SQL_CACHE
									  		faturamentos.matricula,
									  		associados.nm_associado,
									  		associados.cpf,
									  		convenios.razao_social,
									  		associados.rg,
									  		associados.fone_cel,
									  		associados.fone_fixo,
									  		(SELECT max(referencia) FROM faturamentos WHERE matricula = associados.matricula AND faturamentos.status<'3') as referencia,
									  		associados.fone_trabalho
										FROM
									  		faturamentos
									 	LEFT JOIN associados ON associados.matricula = faturamentos.matricula
									 	LEFT JOIN convenios ON convenios.id = associados.convenios_id
										WHERE
									  		associados.status = '1' AND faturamentos.status  <'3' AND
									  		faturamentos.referencia =  concat (EXTRACT(YEAR FROM CURDATE()),'-',EXTRACT(MONTH FROM CURDATE()),'-01')
									  	AND
									  	NOT EXISTS(
                                          SELECT m.matricula FROM faturamentos AS m
									 	WHERE
											 m.matricula = faturamentos.matricula  AND
                                             m.referencia = '".$next_month."'
                                        )
										ORDER BY
									  	associados.convenios_id ASC ,faturamentos.matricula ASC");




$list_reserva= new ArrayIterator($query_reserva);


/* previsão de titulos */

$query_seguro=seguros::find_by_sql("SELECT SQL_CACHE
									  		seguros.matricula,
									  		seguros.referencia,
									  		seguros.nm_assegurado,
									  		seguros.cpf,
									  		seguros.estado_civil,
									  		convenios.razao_social,
									  		associados.fone_cel,
									  		associados.estado_civil,
									  		associados.fone_fixo,
									  		associados.fone_trabalho
										FROM
									  		seguros
									 	LEFT JOIN associados ON associados.matricula = seguros.matricula
									 	LEFT JOIN convenios ON convenios.id = seguros.convenios_id
										WHERE
										seguros.referencia =  concat (EXTRACT(YEAR FROM CURDATE()),'-',EXTRACT(MONTH FROM CURDATE()),'-01')
										ORDER BY
									  	seguros.matricula ASC");

$list_seguro= new ArrayIterator($query_seguro);



/* previsão de titulos atrazados*/

$query_inadimplentes=titulos::find_by_sql("SELECT SQL_CACHE
									  		faturamentos.matricula,
									  		associados.nm_associado,
									  		associados.convenios_id,
									  		associados.fone_fixo,
									  		associados.fone_cel,
									  		convenios.razao_social,
									  		(SELECT max(referencia) FROM faturamentos WHERE matricula = associados.matricula AND faturamentos.status='1') as ult_pgto,
									  		(SELECT count(id)
									          FROM faturamentos
									          WHERE matricula = associados.matricula AND
									                faturamentos.status='0' AND
									                faturamentos.referencia BETWEEN CONCAT(EXTRACT(YEAR FROM curdate()),'-01-01') AND
									                                                CONCAT(EXTRACT(YEAR FROM curdate()),'-',EXTRACT(MONTH FROM curdate()),'-01')
									        ) as p_abertas
										FROM
									  		faturamentos
									 	LEFT JOIN associados ON associados.matricula = faturamentos.matricula
									 	LEFT JOIN convenios ON convenios.id = associados.convenios_id
										WHERE
									 		faturamentos.referencia BETWEEN CONCAT(EXTRACT(YEAR FROM curdate()),'-01-01') AND
									                                        CONCAT(EXTRACT(YEAR FROM curdate()),'-',EXTRACT(MONTH FROM curdate()),'-01') AND
									        faturamentos.status='0' AND
									        faturamentos.titulos_bancarios_id >'0' AND
									        faturamentos.dt_vencimento <'2017-03-24'
										GROUP BY faturamentos.matricula
										ORDER BY
									  	faturamentos.matricula");

$list_inadimplentes= new ArrayIterator($query_inadimplentes);


?>


<div id="big_col_0"  style="margin: 0 auto;"  >


<div id="Det_dash" class="uk-modal">
<div class="uk-modal-dialog uk-modal-det" style="width: 980px;">
</div>
</div>


	<div class="uk-grid uk-width"  style="padding:10px; width: 90%; margin: 0 auto; ">
		<div class="uk-width-medium-1-4 uk-scrollspy-inview uk-animation-slide-top"  >
			<div class="uk-panel uk-panel-box uk-grid-dash" style="padding: 0; height: 130px; text-align: center;">

	         	<a href="#" style=" float:right; right: 5px; top:5px; position: absolute;" class="uk-icon-hover uk-icon-search uk-icon-small uk-text-primary" uk-data-det="Grid_recebimentos">
	         	</a>
				<i class="uk-icon-barcode uk-icon-extra-large uk-text-success" style="margin-top: 5px;"></i>
				<div class="uk-text-success uk-text-bold" >
				<?php echo $dadosassociado[0]->titulos_pgto; ?>
				</div>
				<div class="uk-panel uk-badge-success uk-text-center" style="bottom:0px; color: #fff; position: absolute; width: 94%; padding: 3%;">
					Mensalidades pagas - [<?php echo $M."/".$A; ?>]
				</div>
			</div>
		</div>
		<div class="uk-width-medium-1-4  k-scrollspy-inview uk-animation-slide-bottom" >
			<div class="uk-panel uk-panel-box uk-grid-dash" style="padding:0; height: 130px; text-align: center; ">
				<a href="#" style=" float:right; right: 5px; top:5px; position: absolute;" class="uk-icon-hover uk-icon-search uk-icon-small uk-text-warning" uk-data-det="Grid_m_abertas">
	         	</a>
				<i class="uk-icon-calendar uk-icon-extra-large uk-text-warning" style="margin-top: 5px;font-size:450%; margin-bottom: 5px;"></i>
				<div class="uk-text-warning uk-text-bold"><?php echo $dadosassociado[0]->titulos_abertos; ?></div>
				<div class="uk-panel uk-badge-warning uk-text-center" style="bottom:0px; color: #fff; position: absolute; width: 94%; padding: 3%;">
					Mensalidades Vencidas - [<?php echo $M."/".$A; ?>]
				</div>
			</div>
		</div>	
		<div class="uk-width-medium-1-4 k-scrollspy-inview uk-animation-slide-bottom" >
			<div class="uk-panel uk-panel-box uk-grid-dash" style="padding: 0; height: 130px; text-align: center;">
				<a href="#" style=" float:right; right: 5px; top:5px; position: absolute;" class="uk-icon-hover uk-icon-search uk-icon-small uk-text-success" uk-data-det="Grid_cadastros">
	         	</a>

				<i class="uk-icon-users uk-icon-extra-large uk-text-primary" style="margin-top: 5px;font-size:450%; margin-bottom: 5px; "></i>
				<div class="uk-text-primary uk-text-bold"><?php echo $dadosassociado[0]->total_novos; ?></div>
				<div class="uk-panel uk-badge-primary uk-text-center" style="bottom:0px; color: #fff; position: absolute; width: 94%; padding: 3%;">
					Cadastros/Reativações - [<?php echo $M."/".$A; ?>]
				</div>
			</div>
		</div>
		<div class="uk-width-medium-1-4 k-scrollspy-inview uk-animation-slide-bottom" >
			<div class="uk-panel uk-panel-box uk-grid-dash" style="padding: 0; height: 130px; text-align: center;">
				<a href="#" style=" float:right; right: 5px; top:5px; position: absolute;" class="uk-icon-hover uk-icon-search uk-icon-small uk-text-danger" uk-data-det="Grid_cancelamentos">
	         	</a>

				<i class="uk-icon-user uk-icon-extra-large uk-text-danger" style="margin-top: 5px; "></i>
				<div class="uk-text-danger uk-text-bold"><?php echo $dadosassociado[0]->total_cancel; ?></div>
				<div class="uk-panel uk-badge-danger uk-text-center" style="bottom:0px; color: #fff; position: absolute; width: 94%; padding: 3%;">
					Cancelamentos - [<?php echo $M."/".$A; ?>]
				</div>
			</div>
		</div>
	</div>
</div>

<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div id="big_col_1" class="uk-width-1-1 " style="height:350px; background-color: #EEEEEE;" >

	<div class="uk-grid uk-width"  style="padding:10px; width: 90%; margin: 0 auto; ">
		<div class="uk-width-medium-1-2 uk-scrollspy-inview uk-animation-slide-bottom">
			<div class="uk-panel uk-panel-box" >
				<h3 class="uk-panel-title"><i class="uk-icon-list"></i> Ultimos Recebimentos</h3>
				<table  class="uk-table uk-table-striped uk-table-hover" style="border-top: 1px solid #ccc;" >
				   <thead>
				      <tr style="line-height:25px;">
				        <th class="uk-width uk-text-center" style="width:70px;"  >Data</th>
				        <th class="uk-width uk-text-center" style="width:90px;" >Mat</th>
				        <th class="uk-text-left" 		 >Nome</th>
				        <th class="uk-width uk-text-center" style="width:100px;" >Valor</th>
				        <th class="uk-width uk-text-center" style="width:100px;" >Vlr pago</th>
				      </tr>
				    </thead>
				</table>
				<div class="uk-panel uk-text-left" style="height: 225px; overflow-y: auto; width:100%;">
				<table  class="uk-table uk-table-striped uk-table-hover" style="border-top: 0px solid #ccc;" >
					<tbody>
						<?php
						while($list_parcelas->valid()):

						$dtp = new ActiveRecord\DateTime($list_parcelas->current()->dt_pagamento);
						?>
						<tr style="line-height:23px;" >
							<td class="uk-text-center"  ><?php echo $dtp->format('d/m/Y'); ?></td>
							<td class="uk-width uk-text-center" style="width:90px;" ><?php echo $list_parcelas->current()->matricula; ?></td>
							<td class="uk-width uk-text-left" style="text-transform: capitalize;width:175px;max-width:175px;white-space:nowrap;text-overflow:ellipsis; overflow:hidden;" ><?php echo strtolower($list_parcelas->current()->nm_associado); ?></td>
							<td class="uk-width uk-text-center" style="width:90px;" ><?php echo number_format($list_parcelas->current()->valor,2,',','.'); ?></td>
							<td class="uk-width uk-text-center" style="width:90px; " ><?php echo number_format($list_parcelas->current()->valor_pago,2,',','.'); ?></td>
						</tr>
						<?php $list_parcelas->next(); endwhile;	?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
		<div class="uk-width-medium-1-2 uk-scrollspy-inview uk-animation-slide-top">
			<div class="uk-panel uk-panel-box" style=" padding-left: 2px;padding-right: 2px;">
				<h3 class="uk-panel-title"><i class="uk-icon-comments-o"></i> Notificações</h3>
				<div class="uk-panel uk-text-left" style="height: 260px; overflow-y: auto; width:100%; padding-right: 3px; ">
					<div class="uk-accordion" data-uk-accordion="{showfirst:false,toggle:'.uk-icon-toggle'}">

					<?php

						if(count($Q_notificacoes) > 0){

						while($list_notif->valid()):

						$dtp = new ActiveRecord\DateTime($list_notif->current()->data_hora);
					?>
						<h3 class="uk-accordion-title uk-text-muted <?php echo notificacoes::Status_msg($list_notif->current()->indice,$list_notif->current()->status); ?> uk-text-small" >

							<i class="uk-icon-calendar" style="margin-right: 5px;"></i> <?php echo $dtp->format('d/m/Y h:m:s')." - ".utf8_encode($list_notif->current()->msg); ?>

							<i class="uk-icon-plus uk-text-muted uk-icon-toggle" style="float:right; margin-top: 2px;" uk-data-st="<?php echo $list_notif->current()->status; ?>" uk-data-id="<?php echo $list_notif->current()->id; ?>"></i>
						</h3>
						<div data-wrapper="true" style="height: 0px; position: relative; overflow: hidden;" aria-expanded="false">
							<div class="uk-accordion-content">
	                            <p><?php echo utf8_encode($list_notif->current()->obs); ?></p>
	                    	</div>
	                    </div>

					<?php
					$list_notif->next();
					endwhile;
					}else{

						echo '<div class="uk-alert"> Não há novas notificações.</div>';
					}
					?>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>

<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div id="big_col_2" class="uk-width-1-1 uk-scrollspy-inview uk-animation-slide-left" style="height:390px; background-color: #f5f5f5;" >

    <div class="uk-grid uk-width"  style="padding:10px; width: 90%; margin: 0 auto; ">
        <div class="uk-width-medium-1-9 uk-row-first">
            <div class="uk-panel uk-panel-box">
                <h3 class="uk-panel-title uk-text-primary">
                	<i class="uk-icon-barcode" ></i> Emissão de Carnês 
                	<i class="uk-icon-print uk-icon-medium" onclick="Print('big_col_2_grid');" style="cursor: pointer; float: right;" data-uk-tooltip="" title="" data-cached-title="Imprimir listagem"></i>
                </h3>
                <div class="uk-panel uk-text-left" style="height: 300px; overflow-y:none; width:100%; padding-right: 3px; ">
					<table  class="uk-table" style="border-top: 1px solid #ccc;" >
					   <thead>
					      <tr style="line-height:25px;">
					        <th class="uk-width uk-text-center" style="width:20px;"></th>
					        <th class="uk-width uk-text-center" style="width:100px;">Matricula</th>
					        <th class="uk-text-left" style="width: 300px;" >Nome</th>
					        <th class="uk-width uk-text-center" style="width:150px;" >Ult Parcela</th>
					        <th class="uk-width uk-text-center" style="width:100px;"  >Fone Cel</th>
					        <th class="uk-width uk-text-center" style="width:100px;"  >Fone Fixo</th>
					        <th class="uk-width uk-text-center" style="width:100px;"  >Fone Trab</th>
					        <th class="uk-width uk-text-left" style="width:200px; "  >Convênio</th>
					        <th></th>
					      </tr>
					    </thead>
					</table>
					<div class="uk-panel uk-text-left" id="big_col_2_grid" style="height: 275px; overflow-y: auto; width:100%;">
					<table  class="uk-table uk-table-striped uk-table-hover" style="border-top: 0px solid #ccc;font-size: 11px;" >
					<tbody>
<?php
$c=1;
while($list_reserva->valid()):

// valida 9 digito do celular
if(strlen(tool::LimpaString($list_reserva->current()->fone_cel)) == "10"){
	$fone_cel= tool::MascaraCampos("(??) ????? ????",substr(tool::LimpaString($list_reserva->current()->fone_cel),0,2)."0".substr(tool::LimpaString($list_reserva->current()->fone_cel),2,8));
}else{
	$fone_cel= tool::MascaraCampos("(??) ????? ????",substr(tool::LimpaString($list_reserva->current()->fone_cel),0,2)." ".substr(tool::LimpaString($list_reserva->current()->fone_cel),2,8));
}

// data da ultima parcela paga
$data_parcela = new ActiveRecord\DateTime($list_reserva->current()->referencia);

?>
							<tr style="line-height:25px;" >
								<th class="uk-width uk-text-center" style="width:20px;" >
								<?php echo $c; ?>
								</th>
								<td class="uk-width uk-text-center" style="width:100px;" >
									<?php echo tool::CompletaZeros(10,$list_reserva->current()->matricula); ?>
								</td>
								<td class="uk-width uk-text-left uk-text-uppercase uk-text-overflow" style="width:300px;max-width: 300px;" >
									<?php echo strtoupper($list_reserva->current()->nm_associado); ?>
								</td>
								<td class="uk-width uk-text-center" style="width:150px;" ><?php echo $data_parcela->format('m/Y');?></td>
								<td class="uk-width uk-text-center" style="width:100px;" ><?php echo $fone_cel; ?></td>
								<td class="uk-width uk-text-center" style="width:100px;" ><?php echo tool::MascaraCampos("(??) ???? ????",$list_reserva->current()->fone_fixo); ?></td>
								<td class="uk-width uk-text-center" style="width:100px;" ><?php echo tool::MascaraCampos("(??) ???? ????",$list_reserva->current()->fone_trabalho); ?></td>
								<td class="uk-width uk-text-left  uk-text-uppercase uk-text-overflow" style="width:200px;max-width: 200px;" ><?php echo strtoupper($list_reserva->current()->razao_social); ?></td>
								<td style="text-align: right; padding-right: 10px;">
									<a  onclick="viewAssociado('<?php echo $list_reserva->current()->matricula; ?>');"><i class="uk-icon-search uk-icon-small "></i></a>
								</td>
							</tr>

<?php $c++; $list_reserva->next(); endwhile;	?>
						</tbody>
					</table>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div id="big_col_3" class="uk-scrollpsy uk-width-1-1 " style="height:390px; background-color: #EEEEEE;" >
     <div class="uk-grid uk-width"  style="padding:10px; width: 90%; margin: 0 auto; ">
        <div class="uk-width-medium-1-9 uk-row-first">
            <div class="uk-panel uk-panel-box">
                <h3 class="uk-panel-title uk-text-danger">
                	<i class="uk-icon-users" style="cursor: pointer;"></i> Inadimplentes (ultimos 90 dias)
                	<i class="uk-icon-print uk-icon-medium" onclick="Print('big_col_3_grid');" style="cursor: pointer; float: right;" data-uk-tooltip="" title="" data-cached-title="Imprimir listagem"></i>
                </h3>
                <div class="uk-panel uk-text-left" style="height: 300px; overflow-y:none; width:100%; padding-right: 3px; ">
					<table  class="uk-table" style="border-top: 1px solid #ccc;" >
					   <thead>
					      <tr style="line-height:25px;">
					      <th class="uk-width uk-text-center" style="width:20px;"></th>
					        <th class="uk-width uk-text-center" style="width:100px;"  >Matricula</th>
					        <th class="uk-text-left" style="width: 300px;" >Nome</th>
					        <th class="uk-width uk-text-center" style="width:150px;" >Ult Pgto</th>
					        <th class="uk-width uk-text-center" style="width:100px;" >Fone Cel</th>
					        <th class="uk-width uk-text-center" style="width:100px;" >Fone Fixo</th>
					        <th class="uk-width uk-text-center" style="width:100px;" >Parcelas Ab</th>
					        <th class="uk-width uk-text-left" style="width:200px; "  >Convênio</th>
					        <th></th>
					      </tr>
					    </thead>
					</table>
					<div class="uk-panel uk-text-left" id="big_col_3_grid" style="height: 275px; overflow-y: auto; width:100%;">
					<table  class="uk-table uk-table-striped uk-table-hover" style="border-top: 0px solid #ccc;font-size: 11px;" >
					<tbody>
<?php
$inad='1';
while($list_inadimplentes->valid()):

// valida 9 digito do celular
if(strlen(tool::LimpaString($list_inadimplentes->current()->fone_cel)) == "10"){
	$fone_cel= tool::MascaraCampos("(??) ????? ????",substr(tool::LimpaString($list_inadimplentes->current()->fone_cel),0,2)."0".substr(tool::LimpaString($list_inadimplentes->current()->fone_cel),2,8));
}else{
	$fone_cel= tool::MascaraCampos("(??) ????? ????",substr(tool::LimpaString($list_inadimplentes->current()->fone_cel),0,2)." ".substr(tool::LimpaString($list_inadimplentes->current()->fone_cel),2,8));
}

// data da ultima parcela paga
$data_parcela = new ActiveRecord\DateTime($list_inadimplentes->current()->ult_pgto);

?>
							<tr style="line-height:25px;" >
								<th class="uk-width uk-text-center" style="width:20px;" >
								<?php echo $inad; ?>
								</th>
								<td class="uk-width uk-text-center" style="width:100px;" >
									<?php echo tool::CompletaZeros(10,$list_inadimplentes->current()->matricula); ?>
								</td>
								<td class="uk-width uk-text-left uk-text-uppercase uk-text-overflow" style="width:310px;max-width: 310px;" >
									<?php echo $list_inadimplentes->current()->nm_associado; ?>
								</td>
								<td class="uk-width uk-text-center" style="width:150px;" ><?php echo $data_parcela->format('m/Y');?></td>
								<td class="uk-width uk-text-center" style="width:100px;" ><?php echo $fone_cel; ?></td>
								<td class="uk-width uk-text-center" style="width:100px;" ><?php echo tool::MascaraCampos("(??) ???? ????",$list_inadimplentes->current()->fone_fixo); ?></td>
								<td class="uk-width uk-text-center" style="width:100px;" ><?php echo $list_inadimplentes->current()->p_abertas; ?></td>
								<td class="uk-width uk-text-left  uk-text-uppercase uk-text-overflow" style="width:200px;max-width: 200px;" ><?php echo $list_inadimplentes->current()->razao_social; ?></td>
								<td style="text-align: right; padding-right: 10px;">
									<a  onclick="viewAssociado('<?php echo $list_inadimplentes->current()->matricula; ?>','<?php echo $list_inadimplentes->current()->convenios_id; ?>');"><i class="uk-icon-search uk-icon-small "></i></a>
								</td>
							</tr>
<?php $inad++;$list_inadimplentes->next(); endwhile;	?>
						</tbody>
					</table>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php if($COB_Acesso_Id >= 3 ){?>

<div id="big_col_4" class="uk-scrollpsy uk-width-1-1"  style="height:490px; background-color: #f5f5f5;">
	 <div class="uk-grid uk-width"  style="padding:10px; width: 90%; margin: 0 auto; ">
        <div class="uk-width-medium-1-9  uk-row-first">
			<div class="uk-panel uk-panel-box">
				<h3 class="uk-panel-title"><i class="uk-icon-bar-chart"></i> Faturamentos</h3>
				<div class="uk-panel  uk-text-center" id="faturamento" style="width: 90%; "> </div>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div id="big_col_5" class="uk-scrollpsy uk-width-1-1" style="height:490px; background-color: #EEEEEE;" >
	<div class="uk-grid uk-width"  style="padding:10px; width: 90%; margin: 0 auto; ">
        <div class="uk-width-medium-1-9  ">
			<div class="uk-panel uk-panel-box">
				<h3 class="uk-panel-title"><i class="uk-icon-bar-chart"></i> Evolução de Recebivéis</h3>
				<div class="uk-panel  uk-text-center" id="evolucao"> </div>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div id="big_col_6" class="uk-scrollpsy uk-width-1-1" style="height:390px; background-color: #f5f5f5;" >

    <div class="uk-grid uk-width"  style="padding:10px; width: 90%; margin: 0 auto;  ">
        <div class="uk-width-medium-1-9 uk-row-first" >
            <div class="uk-panel uk-panel-box">
                <h3 class="uk-panel-title uk-text-primary">
                	<i class="uk-icon-users"  style="cursor: pointer;"></i> Assegurados no mês
                	<i class="uk-icon-print uk-icon-medium" onclick="Print('big_col_6_grid');" style="cursor: pointer; float: right;" data-uk-tooltip="" title="" data-cached-title="Imprimir listagem"></i>
                </h3>
                <div class="uk-panel uk-text-left" style="height: 300px; overflow-y:none; width:100%; padding-right: 3px; ">
					<table  class="uk-table" style="border-top: 1px solid #ccc;" >
					   <thead>
					      <tr style="line-height:25px;">
					      <th class="uk-width uk-text-center" style="width:20px;"></th>
					        <th class="uk-width uk-text-center" style="width:100px;"  >Matricula</th>
					        <th class="uk-text-left" style="width: 300px;" >Nome</th>
					        <th class="uk-width uk-text-center" style="width:150px;" >Cpf</th>
					        <th class="uk-width uk-text-center" style="width:100px;"  >Fone Cel</th>
					        <th class="uk-width uk-text-center" style="width:100px;"  >Fone Fixo</th>
					        <th class="uk-width uk-text-center" style="width:100px;"  >Referencia</th>
					        <th class="uk-width uk-text-left" style="width:200px; "  >Estado Civil</th>
					      </tr>
					    </thead>
					</table>
					<div class="uk-panel uk-text-left" id="big_col_6_grid" style="height: 275px; overflow-y: auto; width:100%;">
					<table  class="uk-table uk-table-striped uk-table-hover" style="border-top: 0px solid #ccc; font-size: 11px;" >
					<tbody>
<?php
$seg=1;
while($list_seguro->valid()):

// valida 9 digito do celular
if(strlen(tool::LimpaString($list_seguro->current()->fone_cel)) == "10"){
	$fone_cel= tool::MascaraCampos("(??) ????? ????",substr(tool::LimpaString($list_seguro->current()->fone_cel),0,2)."0".substr(tool::LimpaString($list_seguro->current()->fone_cel),2,8));
}else{
	$fone_cel= tool::MascaraCampos("(??) ????? ????",substr(tool::LimpaString($list_seguro->current()->fone_cel),0,2)." ".substr(tool::LimpaString($list_seguro->current()->fone_cel),2,8));
}

// data da ultima parcela paga
$referencia = new ActiveRecord\DateTime($list_seguro->current()->referencia);

?>
							<tr style="line-height:25px;" >
								<th class="uk-width uk-text-center" style="width:20px;" >
								<?php echo $seg; ?>
								</th>
								<td class="uk-width uk-text-center" style="width:100px;" >
									<?php echo tool::CompletaZeros(10,$list_seguro->current()->matricula); ?>
								</td>
								<td class="uk-width uk-text-left uk-text-uppercase uk-text-overflow" style="width:310px;max-width: 310px;" >
									<?php echo strtoupper($list_seguro->current()->nm_assegurado); ?>
								</td>
								<td class="uk-width uk-text-center" style="width:150px;" ><?php echo tool::MascaraCampos("???.???.???-??",$list_seguro->current()->cpf);?></td>
								<td class="uk-width uk-text-center" style="width:100px;" ><?php echo $fone_cel; ?></td>
								<td class="uk-width uk-text-center" style="width:100px;" ><?php echo tool::MascaraCampos("(??) ???? ????",$list_seguro->current()->fone_fixo); ?></td>
								<td class="uk-width uk-text-center" style="width:100px;" ><?php echo $referencia->format('m/Y');?></td>
								<td class="uk-width uk-text-left  uk-text-uppercase uk-text-overflow" style="width:200px;max-width: 200px;" >
								<?php
								            if($list_seguro->current()->estado_civil == "C"){echo"Casado (a)";}
								            elseif($list_seguro->current()->estado_civil == "S"){echo"Solteiro(a)";}
								            elseif($list_seguro->current()->estado_civil == "V"){echo"Viuvo(a)";}
								            elseif($list_seguro->current()->estado_civil == "A"){echo"Amasiado(a)";}
								            elseif($list_seguro->current()->estado_civil == "D"){echo"Divorciado(a)";}
								            else{echo "Não informado";}

								?></td>

							</tr>
<?php $seg++;$list_seguro->next(); endwhile;	?>
						</tbody>
					</table>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">


//FUNÇÃO DETALHES
jQuery(".uk-grid-dash a").click(function(event) {
        event.preventDefault();
        var pg = jQuery(this).attr("uk-data-det");

        if(pg==""){UIkit.modal.alert("Acesso ainda não liberado!");exit();}

        var modal = UIkit.modal("#Det_dash");
		    modal.show();

		    jQuery(".uk-modal-det").html('<i class="uk-icon-spinner uk-icon-spin"></i><span > Carregando </span>').load("assets/dashboard/"+pg+".php");
    });

/* grafico de faturamentos*/
jQuery('#faturamento').highcharts({
       chart: {type: 'column'},
        title: {text: 'Comparativo de Recebimentos'},
        subtitle: {text: ''},
        xAxis: {categories: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']},
        yAxis: {
            min: 0,
            title: {text: 'Base de Medida (Quant)'}
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px ba">{point.key}</span><table>',
            pointFormat: '<tr style="line-height: 26px;"><td style="color:{series.color};padding:0; min-width: 100px; font-size: 11px;">{series.name}: </td>' +
                			'<td style="padding:0;min-width: 70px; font-size: 11px; text-align: center;"><b>{point.y:.0f} </b></td>'+
                			'<td style="padding:0;font-size: 11px;"><b>Parcelas</b></td>'+'</tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
		credits: {enabled: false},
        plotOptions: {column: {pointPadding: 0.2,borderWidth: 0}},
        series: [{
            name: 'Previsto - 2017',
            data: [<?php echo $data_pfat_2016; ?>]
        },{
            name: 'Realizado - 2017',
            data: [<?php echo $data_rfat_2016; ?>]
        },{
            name: 'Previsto - 2018',
            data: [<?php echo $data_pfat_2017; ?>]
        },{
            name: 'Realizado - 2018',
            data: [<?php echo $data_rfat_2017; ?>]
        }],
            exporting: {enabled: false}
        });

/* grafico evolução*/
jQuery(function () {

    jQuery('#evolucao').highcharts({
        chart: {type:'line'},
        title: {text:'Todas as Contas'},
        subtitle: {text:''},
        xAxis: {categories: [<?php for($i=1;$i<(date("d")+1); $i++){echo $i.",";} ?>]},
        yAxis: {title:{text: ' <?php echo strftime('%B ', strtotime('today'))."/".date("Y"); ?> (R$) '}},
        plotOptions: {
            line: {
                dataLabels: { enabled: true},
                enableMouseTracking: true
            }
        },
        tooltip: {valueDecimals: 2,},
        colors: { valueDecimals: 2,},
        credits: {enabled: false},
        series: [
			<?php
			// loop das contas bancarias
			$Query_contas_bancarias=contas_bancarias::find_by_sql("Select id,nm_conta FROM contas_bancarias WHERE empresas_id='".$COB_Empresa_Id."' and pg_inicial='1'");
			$List_contas= new ArrayIterator($Query_contas_bancarias);
			while($List_contas->valid()):
			?>
        {
            name: '<?php echo strtoupper($List_contas->current()->nm_conta); ?>',
            data: [<?php
			for($i=1;$i<(date("d")+1); $i++){
				$dt=date("Y-m-").$i;
				// soma todas as entradas
				$e_d= caixa::find_by_sql("SELECT SUM(valor) AS total FROM caixa WHERE contas_bancarias_id='".$List_contas->current()->id."' AND tipo='c' AND tipolancamento='1' AND empresas_id='".$COB_Empresa_Id."' AND data= '".$dt."' ");
				//saldo da conta
				$totaldi=$e_d[0]->total;
				echo number_format($totaldi,2,".","").",";
			}?>]},
		<?php
		$List_contas->next();
		endwhile;
		?>
		{
            name: 'ACUMULADO',
            color:'#4CAF50',
            data: [<?php
            	$total=0;
			for($i=1;$i<(date("d")+1); $i++){
				$dt=date("Y-m-").$i;
				// soma todas as entradas
				$e_s= caixa::find_by_sql("SELECT SUM(valor) AS total FROM caixa WHERE tipo='c' AND tipolancamento='1' AND empresas_id='".$COB_Empresa_Id."' AND data= '".$dt."' ");
				//saldo da conta
				$total+=$e_s[0]->total;
				echo number_format($total,2,".","").",";
			}
			?>
			]
			},{
            name: 'SAIDAS',
            color:'#C62828',
            data: [<?php
            	$total=0;
			for($i=1;$i<(date("d")+1); $i++){
				$dt=date("Y-m-").$i;
				// soma todas as entradas
				$e_s= caixa::find_by_sql("SELECT SUM(valor) AS total FROM caixa WHERE tipo='D' AND tipolancamento='1' AND empresas_id='".$COB_Empresa_Id."' AND data= '".$dt."' ");
				//saldo da conta
				$total+=$e_s[0]->total;
				echo number_format($total,2,".","").",";
			}
			?>
			]
			}
			],exporting: {
								 enabled: false
        					}
    });
});


/* evento no click da classe uk-icon-folder para indicar que a notificação ja foi lida */

jQuery(".uk-icon-plus").click(function(){

var msg=jQuery(this).parent();
jQuery(this).toggleClass("uk-icon-minus");

	if(jQuery(this).attr("uk-data-st") == 0){

		jQuery.ajax({
			async: true,
			url: "assets/notificacao/Controller_notificacao.php",
			type: "POST",
			data: "notif_id="+jQuery(this).attr("uk-data-id")+"",
			success: function(resultado) {
				if(jQuery.isNumeric(resultado)){
						msg.removeClass('uk-text-danger').addClass('uk-text-muted');// altera a cor do texto
				}else{
					UIkit.modal.alert(resultado);
				}
			},
			error:function (){
			UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
			}
		});
	}
});

/* botao de atalho para visulizar o cadastro do associado*/
function viewAssociado(matricula,convenio){

			New_window('list','950','500','Faturamento','assets/faturamento/Frm_faturamento.php?matricula='+matricula+'&convenio_id='+convenio+'',true,false,'Carregando...');

}


</script>