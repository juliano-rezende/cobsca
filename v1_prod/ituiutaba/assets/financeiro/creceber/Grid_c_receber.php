<script type="text/javascript" src="framework/uikit-2.24.0/js/core/toggle.min.js"></script>
<div class="tabs-spacer" style="display:none;">
<?php
// blibliotecas
require_once("../../../sessao.php");
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


if(isset( $_GET['action'])){

$erro="";
/*validações para evitar injeção de dados*/
$contas_bancarias_id	= isset( $_GET['contas_bancarias_id'])	? $_GET['contas_bancarias_id']: tool::msg_erros("O Campo Obrigatorio 0-1 Faltando.");
$status					= isset( $_GET['status'])				? $_GET['status']:  			tool::msg_erros("O Campo Obrigatorio 0-2 Faltando.");
$intervalo				= isset( $_GET['intervalo'])			? $_GET['intervalo']:  			tool::msg_erros("O Campo Obrigatorio 0-3 Faltando.");
$p_inicio				= isset( $_GET['p_inicio'])				? $_GET['p_inicio']:  			tool::msg_erros("O Campo Obrigatorio 0-4 Faltando.");
$p_fim					= isset( $_GET['p_fim'])				? $_GET['p_fim']:  				tool::msg_erros("O Campo Obrigatorio 0-5 Faltando.");


/* definição do campo de data a ser pesquisado*/
if($status == "" ){$campo="contas_receber.dt_vencimento";}elseif($status == 0 ){$campo="contas_receber.dt_vencimento";}else{$campo="contas_receber.dt_recebimento";}

	/*todas as contas*/
if($intervalo==""){
	$periodo="";
	}// mes atual
elseif($intervalo==0){
	$periodo="AND ".$campo." Between '".date("Y-m")."-01' And '".date("Y-m")."-31'";
	}// mes atual
elseif($intervalo==1){
	$periodo="AND ".$campo." Between '".date("Y-m")."-01' And '".date("Y-m")."-31'";
	}//mes atual
elseif($intervalo==2){
	$periodo="AND ".$campo." Between '".tool::subDayIntoDate(date("Ymd"),7)."' And '".date("Y-m")."-31'";
	}// 7 dias
elseif($intervalo==3){
	$periodo="AND ".$campo." Between '".tool::subDayIntoDate(date("Ymd"),15)."' And '".date("Y-m-d")."'";
	}// 15 dias
elseif($intervalo==4){
	$periodo="AND ".$campo." Between '".tool::subDayIntoDate(date("Ymd"),30)."' And '".date("Y-m-d")."'";
	}// 30 dias
elseif($intervalo==5){
	/* data hoje menos 30 dias*/
	$ini=date('Y-m', strtotime('-1 months', strtotime(date('Y-m-d'))))."-01";
	$fim=date("Y-m-t", strtotime("-1 Month",strtotime(date('Y/m/d H:i:s'))));
	$periodo="AND ".$campo." Between '".$ini."' And '".$fim."'";
	}// mes anterior
elseif($intervalo==6){
	$periodo="AND ".$campo." Between '".tool::InvertDateTime(tool::LimpaString($p_inicio),0)."' And '".tool::InvertDateTime(tool::LimpaString($p_fim),0)."'";
	}// periodo especifico

/*carrega a tela normal sem filtros*/
}else{

$contas_bancarias_id= "";
$status 			= "0";
$periodo			= "";
$campo 				= "contas_receber.dt_vencimento";
}


/* variavel para pesquisa da conta bancaria*/
if($contas_bancarias_id== "" ){$contas_id="";}else{$contas_id= "AND contas_receber.contas_bancarias_id='".$contas_bancarias_id."'";}
/* variavel para pesquisa do status*/
$status_p = "AND contas_receber.status='".$status."'";


// query de pesquisa
$query=contas_receber::find_by_sql("SELECT contas_receber.*,
										   clientes_fornecedores.tipo,
                                           clientes_fornecedores.nm_fantasia,
                                           clientes_fornecedores.nm_cliente
                                    FROM contas_receber
                                    LEFT JOIN clientes_fornecedores ON clientes_fornecedores.id = contas_receber.clientes_fornecedores_id
                                    WHERE contas_receber.empresas_id='".$COB_Empresa_Id."' ".$contas_bancarias_id." ".$status_p." ".$periodo." GROUP BY ".$campo."");

// laço que loopa os lançamentos dos convenios
$list= new ArrayIterator($query);


$grid=0;
while($list->valid()):


$dtv=new ActiveRecord\DateTime($list->current()->dt_vencimento);
$datapesquisa= $dtv->format('Y-m-d');

/* variavel para pesquisa da conta bancaria*/
/* variavel para pesquisa da conta bancaria*/
if($contas_bancarias_id == "" ){$contas_banc_id="";}else{$contas_banc_id= "AND contas_receber.contas_bancarias_id='".$contas_bancarias_id."'";}

if($status == "" ){$status_f="";}else{$status_f = "AND contas_receber.status='".$status."'";}

// query que agrupa os lançamentos po convenios
$querycontasrec=contas_receber::find_by_sql("SELECT contas_receber.*,
													clientes_fornecedores.nm_fantasia,
													contas_bancarias.nm_conta,
													clientes_fornecedores.tipo,
													clientes_fornecedores.nm_cliente
										   	FROM contas_receber
										   	LEFT JOIN clientes_fornecedores ON clientes_fornecedores.id = contas_receber.clientes_fornecedores_id
										   	LEFT JOIN contas_bancarias ON contas_bancarias.id = contas_receber.contas_bancarias_id
										   	WHERE contas_receber.dt_vencimento ='".$datapesquisa."' ".$contas_banc_id." ".$status_f."  ORDER BY contas_receber.dt_vencimento ASC ");


if(substr($dtv->format('d/m/Y'),3,2)==(date("m"))){// se for o mês corrente

				if((substr($dtv->format('d/m/Y'),0,2)-1)==(date("d")) && $list->current()->status == 0){//se o dia do pagamento for amanha
					$style="uk-button-success";
					$txt="( Vence amanhã! )";
				}else{
						if(substr($dtv->format('d/m/Y'),0,2)==(date("d")) && $list->current()->status == 0){// se o dia do pagamento for hoje
							$style="uk-button-warning";
							$txt="( Vence Hoje! )";
							}else{

								if(substr($dtv->format('d/m/Y'),0,2)<(date("d")) && $list->current()->status == 0){//se o dia do pagamento ja passou
										$style="uk-button-danger";
										$txt="( Vencido! )";
										}else{
												$style="uk-button-primary";
												$txt="";
												}
								}
					}
}else{$style="uk-button-primary"; $txt="";}
$vlr_total=0;
?>
</div>
<div style="width: 200px; padding: 2px;">
<div id="grid_header" class="uk-text-center uk-text-bold uk-text-small <?php echo $style; ?>"  style="padding:5px; color:#fff; width:165px; border:0;  float: left; ">
     <i class="uk-icon-calendar"></i> <?php echo $dtv->format('d/m/Y')." ".$txt; ?>
</div>
<div id="grid_header_ico" class="uk-text-center uk-text-bold uk-text-small <?php echo $style; ?>"  style="color:#fff; width:16px; height: 26px;cursor: pointer; border:0;margin-left: 175px; text-align: center; ">
<i class="icon_toggle uk-icon-small uk-icon-angle-double-<?php if($dtv->format('Ymd') > date('Ymd')){echo 'down';}else{echo 'up';} ?>" data-uk-toggle="{target:'#grid_content_<?php echo $list->current()->id; ?>', animation:'uk-animation-slide-bottom, uk-animation-slide-left'}" style="margin-top: 30%; padding-right: 2px;"></i>
</div>
</div>
<div class="uk-panel"  style="border-top:1px solid #ccc;">
<div style="border:1px solid #eaeaea;" id="grid_content_<?php echo $list->current()->id; ?>" class="<?php if($dtv->format('Ymd') > date('Ymd')){echo 'uk-hidden';} ?> uk-animation-slide-bottom">
	<table class="uk-table uk-table-striped uk-table-hover" id="grid_c_pagar">
	  	<thead >
		    <tr style="line-height:20px;border-top: 0;" class="uk-gradient-cinza" >
		      	<th class="uk-width uk-text-center" style="width:90px;" >Cod</th>
		      	<th  class="uk-width uk-text-center" style="width:100px;" >Nº Doc</th>
		      	<th  class="uk-width uk-text-center" style="width:100px;" >Nº Parcela</th>
		        <th  class="uk-width uk-text-center" style="width:100px;" >Dt Inclusão</th>
		        <th  class="uk-width uk-text-center" style="width:100px;" >Dta Venc</th>
		        <th  class="uk-width uk-text-left" style="width:250px; min-width:200px; max-width: 200px;">Receber de</th>
		        <th  class="uk-text-left" >Historico</th>
		        <th  class="uk-text-center uk-width " style="width:200px;" >Conta Debito</th>
		        <th  class="uk-width uk-text-center" style="width:120px;"  >Valor</th>
		    </tr>
	  	</thead>
		<tbody style="text-transform:uppercase;">
<?php $listconta= new ArrayIterator($querycontasrec);while($listconta->valid()): ?>
		    <tr style="line-height:20px;" data-creceber-id="<?php echo tool::completazeros("10",$listconta->current()->id);?>" >
		      	<td class="uk-width uk-text-center"  style="width:90px;" ><?php echo tool::completazeros("10",$listconta->current()->id);?></td>
		      	<td  class="uk-width uk-text-center" style="width:100px;" ><?php echo $listconta->current()->num_doc;?></td>
		      	<td  class="uk-width uk-text-center" style="width:100px;" ><?php echo $listconta->current()->n_parcela;?></td>
		        <td  class="uk-width uk-text-center" style="width:100px;" ><?php $now=new ActiveRecord\DateTime($listconta->current()->dt_cadastro);echo $now->format('d/m/Y');?></td>
		        <td  class="uk-width uk-text-center" style="width:100px;" ><?php $now=new ActiveRecord\DateTime($listconta->current()->dt_vencimento);echo $now->format('d/m/Y');?></td>
		        <td  class="uk-width uk-text-left"   style="width:250px; min-width:200px; max-width: 200px; white-space: nowrap;">
		        <?php
		        if($listconta->current()->tipo == 1){
				  echo utf8_encode($listconta->current()->nm_cliente);
				}else{
				  echo utf8_encode($listconta->current()->nm_fantasia);
				}
		        ?>
		        </td>
		        <td  class="uk-text-left" ><?php echo $listconta->current()->historico;?></td>
		        <td  class="uk-text-center uk-width" style="width:200px;" ><?php echo $listconta->current()->nm_conta;?></td>
		        <td  class="uk-width uk-text-center" style="width:120px;"  ><?php echo number_format($listconta->current()->vlr_nominal,2,",",".");?></td>
		    </tr>
<?php $vlr_total+=$listconta->current()->vlr_nominal; $listconta->next(); endwhile; ?>
		</tbody>
		<tfoot style="text-transform:uppercase;">
		    <tr style="line-height:20px;" class="uk-text-bold">
		      	<th class="uk-width uk-text-center"  style="width:90px;" >Total -></th>
		      	<th  class="uk-width uk-text-center" style="width:100px;" colspan="6"></th>
		      	<th  class="uk-text-center uk-width" style="width:200px;" ></th>
		        <th  class="uk-width uk-text-center" style="width:120px;"  ><?php echo number_format($vlr_total,2,",",".");?></th>
		    </tr>
		</tfoot>
	</table>
</div>
</div>

<?php $grid++; $list->next(); endwhile; ?>

<script type="text/javascript">
/* função para visualizar detalhes da parcela */
jQuery("#Grid_contas_receber table tbody tr").click(function(){
    var creceber_id = jQuery(this).closest('tr').attr('data-creceber-id');/*pega a linha de exibição*/
    jQuery(".Window").remove(); /*  fecha demais janelas abertas para prevenir falhas*/
    New_window('money','700','500','Editar Lançamento','assets/financeiro/creceber/Frm_creceber.php?creceber_id='+creceber_id+'',true,false,'Aguarde ...');/* abre a janela atualizada*/
});
/* altera o icone do toggle*/
jQuery( ".icon_toggle" ).click(function() {

$str= jQuery(this).hasClass("uk-icon-angle-double-up");
if($str == true){
	jQuery( this ).removeClass("uk-icon-angle-double-up").addClass("uk-icon-angle-double-down");
}else{
	jQuery( this ).removeClass("uk-icon-angle-double-down").addClass("uk-icon-angle-double-up");
}});
</script>