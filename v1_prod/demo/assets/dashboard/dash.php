<link rel="stylesheet" href="framework/uikit-2.24.0/css/components/accordion.min.css">
<script type="text/javascript" src="framework/uikit-2.24.0/js/components/accordion.min.js"></script>
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
		(SELECT count(id) FROM faturamentos  WHERE dt_vencimento between '".$A."-".$M."-01' and '".$A."-".$M."-31' and empresas_id='".$COB_Empresa_Id."') as titulos_emitidos,
		(SELECT count(id) FROM faturamentos  WHERE status ='1' and dt_pagamento between '".$A."-".$M."-01' and '".$A."-".$M."-31' and empresas_id='".$COB_Empresa_Id."') as titulos_pgto,
		(SELECT count(id) FROM faturamentos  WHERE status ='0' and dt_vencimento between '".$A."-".$M."-01' and '".$A."-".$M."-31' and empresas_id='".$COB_Empresa_Id."' and titulos_bancarios_id > 0) as titulos_abertos
		 FROM associados WHERE status='0' and dt_cancelamento between '".$A."-".$M."-01' and '".$A."-".$M."-".$D."' and empresas_id='".$COB_Empresa_Id."'";
$dadosassociado= associados::find_by_sql($query_assoc);


/* query para alimentar o grupos da header*/
$Q_U_receb=faturamentos::find_by_sql("SELECT SQL_CACHE
										faturamentos.id,faturamentos.titulos_bancarios_id,faturamentos.dt_pagamento,faturamentos.valor,
										faturamentos.valor_pago,faturamentos.tipo_baixa,associados.matricula,associados.nm_associado
									FROM faturamentos
									LEFT JOIN associados ON faturamentos.matricula= associados.matricula
									WHERE faturamentos.status='1' AND faturamentos.empresas_id='".$COB_Empresa_Id."' AND MONTH(faturamentos.dt_pagamento)  = '".$M."' and YEAR(faturamentos.dt_pagamento) ='".$A."'	ORDER BY faturamentos.dt_pagamento  DESC LIMIT 30");
$list_parcelas= new ArrayIterator($Q_U_receb);

/* query das notificações */
$Q_notificacoes=notificacoes::find_by_sql("SELECT SQL_CACHE * FROM notificacoes WHERE status='0' ORDER BY id DESC limit 20");
$list_notif= new ArrayIterator($Q_notificacoes);






/* previsão de titulos atrazados*/
$prev_month11=date('Y-m-d', strtotime('-11 months', strtotime(date('Y-m')."-01")));
$prev_month1=date('Y-m-d', strtotime('-1 months', strtotime(date('Y-m')."-01")));

$query_inadimplentes=titulos::find_by_sql("SELECT SQL_CACHE
									  		faturamentos.matricula,
									  		associados.nm_associado,
									  		associados.convenios_id,
									  		associados.fone_fixo,
									  		associados.fone_cel,
									  		convenios.razao_social,
									  		(SELECT max(referencia) FROM faturamentos WHERE matricula = associados.matricula AND faturamentos.status='1') as ult_pgto,
									  		(SELECT count(id) FROM faturamentos WHERE matricula = associados.matricula AND faturamentos.status='0'  ) as p_abertas
										FROM
									  		faturamentos
									 	LEFT JOIN associados ON associados.matricula = faturamentos.matricula
									 	LEFT JOIN convenios ON convenios.id = associados.convenios_id
										WHERE
									        faturamentos.referencia >'".$prev_month11."' AND faturamentos.referencia <'".$prev_month1."' AND faturamentos.status='0'
										GROUP BY faturamentos.matricula
										ORDER BY faturamentos.matricula");

$list_inadimplentes= new ArrayIterator($query_inadimplentes);



/* lista de todos titulos emitidos no mes corrente*/

$query_titulos_gerados=titulos::find_by_sql("SELECT   titulos_bancarios.id,
													  titulos_bancarios.cod_remessa,
													  titulos_bancarios.numero_doc,
											          titulos_bancarios.nosso_numero,
											          titulos_bancarios.dv_nosso_numero,
											          titulos_bancarios.sacado,
											          titulos_bancarios.dt_emissao,
											          titulos_bancarios.dt_vencimento,
											          titulos_bancarios.vlr_nominal,
											          contas_bancarias.cod_banco,
											          faturamentos.convenios_id,
											          faturamentos.referencia,
											          associados.matricula
											          FROM
											          titulos_bancarios
											          LEFT JOIN contas_bancarias ON titulos_bancarios.contas_bancarias_id = contas_bancarias.id
											          LEFT JOIN faturamentos ON faturamentos.titulos_bancarios_id = titulos_bancarios.id
											          LEFT JOIN associados ON faturamentos.matricula = associados.matricula
											          WHERE  dt_emissao BETWEEN concat (EXTRACT(YEAR FROM CURDATE()),'-',EXTRACT(MONTH FROM CURDATE()),'-01') AND CURDATE() GROUP BY faturamentos.titulos_bancarios_id ORDER BY associados.matricula ASC, titulos_bancarios.nosso_numero ASC ");

$list_titulos= new ArrayIterator($query_titulos_gerados);


$indRecebiveis = ($dadosassociado[0]->titulos_pgto/$dadosassociado[0]->titulos_emitidos)*100;


?>



<div id="big_col_0"  style="margin: 0 auto;"  >


<div id="Det_dash" class="uk-modal">
<div class="uk-modal-dialog uk-modal-det" style="width: 980px;">
</div>
</div>


	<div class="uk-grid uk-width"  style="padding:10px; width: 90%; margin: 0 auto; ">
		<div class="uk-width-medium-1-4 uk-scrollspy-inview uk-animation-slide-top"  >
			<div class="uk-panel uk-panel-box uk-grid-dash" style="padding: 0; min-height: 150px; text-align: center;">

	         	<a href="#" style=" float:right; right: 5px; top:5px; position: absolute;" class="uk-icon-hover uk-icon-search uk-icon-small uk-text-success" uk-data-det="Grid_recebimentos">
	         	</a>
				<i class="uk-icon-barcode uk-icon-extra-large uk-text-success" style="margin-top: 5px;"></i>
				<div class="uk-text-success uk-text-bold" >
				<?php echo $dadosassociado[0]->titulos_pgto." / ".$dadosassociado[0]->titulos_emitidos." = (".number_format($indRecebiveis,2,".","")."%)"; ?>
				</div>
				<div class="uk-panel uk-badge-success uk-text-center" style="bottom:0px; color: #fff; position: absolute; width: 94%; padding: 3%;">
					Parcelas pagas - [<?php echo $M."/".$A; ?>]
				</div>
			</div>
		</div>
		<div class="uk-width-medium-1-4  k-scrollspy-inview uk-animation-slide-bottom" >
			<div class="uk-panel uk-panel-box uk-grid-dash" style="padding:0; min-height: 150px;  text-align: center; ">
				<a href="#" style=" float:right; right: 5px; top:5px; position: absolute;" class="uk-icon-hover uk-icon-search uk-icon-small uk-text-warning" uk-data-det="Grid_m_abertas">
	         	</a>
				<i class="uk-icon-calendar uk-icon-extra-large uk-text-warning" style="margin-top: 5px;font-size:450%; margin-bottom: 5px;"></i>
				<div class="uk-text-warning uk-text-bold"><?php echo $dadosassociado[0]->titulos_abertos; ?></div>
				<div class="uk-panel uk-badge-warning uk-text-center" style="bottom:0px; color: #fff; position: absolute; width: 94%; padding: 3%;">
					Parcelas Vencidas - [<?php echo $M."/".$A; ?>]
				</div>
			</div>
		</div>	
		<div class="uk-width-medium-1-4 k-scrollspy-inview uk-animation-slide-bottom" >
			<div class="uk-panel uk-panel-box uk-grid-dash" style="padding: 0; min-height: 150px;  text-align: center;">
				<a href="#" style=" float:right; right: 5px; top:5px; position: absolute;" class="uk-icon-hover uk-icon-search uk-icon-small uk-text-primary" uk-data-det="Grid_cadastros">
	         	</a>

				<i class="uk-icon-users uk-icon-extra-large uk-text-primary" style="margin-top: 5px;font-size:450%; margin-bottom: 5px; "></i>
				<div class="uk-text-primary uk-text-bold"><?php echo $dadosassociado[0]->total_novos; ?></div>
				<div class="uk-panel uk-badge-primary uk-text-center" style="bottom:0px; color: #fff; position: absolute; width: 94%; padding: 3%;">
					Cadastros/Reativações - [<?php echo $M."/".$A; ?>]
				</div>
			</div>
		</div>
		<div class="uk-width-medium-1-4 k-scrollspy-inview uk-animation-slide-bottom" >
			<div class="uk-panel uk-panel-box uk-grid-dash" style="padding: 0; min-height: 150px;  text-align: center;">
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
				        <th class="uk-width uk-text-center" style="width:20px;" >TB</th>
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
							<td class="uk-text-center" style="width:70px;"  ><?php echo $dtp->format('d/m/Y'); ?></td>
							<td class="uk-width uk-text-center" style="width:90px;" ><?php echo $list_parcelas->current()->matricula; ?></td>
							<td class="uk-width uk-text-left" style="text-transform: capitalize;width:25px;max-width:25px;white-space:nowrap;text-overflow:ellipsis; overflow:hidden;" ><?php echo strtolower($list_parcelas->current()->nm_associado); ?></td>
							<td class="uk-width uk-text-center" style="width:100px;" ><?php echo number_format($list_parcelas->current()->valor,2,',','.'); ?></td>
							<td class="uk-width uk-text-center" style="width:100px; " ><?php echo number_format($list_parcelas->current()->valor_pago,2,',','.'); ?></td>
							<td class="uk-width uk-text-center" style="width:20px; " ><?php echo $list_parcelas->current()->tipo_baixa; ?></td>
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

<div id="big_col_3" class="uk-scrollpsy uk-width-1-1 " style="height:390px; background-color: #EEEEEE; margin-bottom:25px;" >
     <div class="uk-grid uk-width"  style="padding:10px; width: 90%; margin: 0 auto; ">
        <div class="uk-width-medium-1-9 uk-row-first">
            <div class="uk-panel uk-panel-box">
                <h3 class="uk-panel-title uk-text-danger">
                	<i class="uk-icon-users" style="cursor: pointer;"></i> Inadimplentes (ultimos 12 Meses)
                	<i class="uk-icon-print uk-icon-small " onclick="Print('big_col_3_grid');" style="cursor: pointer; float: right;" data-uk-tooltip="" title="" data-cached-title="Imprimir listagem"></i>
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