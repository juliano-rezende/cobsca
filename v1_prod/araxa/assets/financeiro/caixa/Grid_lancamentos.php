<?php /* biblioteca*/require_once("../../../sessao.php");?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


$dadosusuario=users::find($COB_Usuario_Id);

if(isset($_GET['conta_id'])){
							$conta="caixa.contas_bancarias_id=".$_GET['conta_id']." AND ";
							$contabancaria="caixa.contas_bancarias_id='".$_GET['conta_id']."'";
							$querycontabancaria=contas_bancarias::find($_GET['conta_id']);
							//$descricaoconta=$querycontabancaria->descricao;

							}else{
								$querycontabancaria=contas_bancarias::find_by_status_and_empresas_id_and_tp_conta(1,$COB_Empresa_Id,0);
								$conta="caixa.contas_bancarias_id='".$querycontabancaria->id."' AND ";
								$contabancaria="caixa.contas_bancarias_id='".$querycontabancaria->id."'";
								//$descricaoconta=$querycontabancaria->descricao;
								}// define a conta

if(isset($_GET['tipo'])){
						if($_GET['tipo']=='0'){$tipo=''; $tipolancamento="AND caixa.tipolancamento='1'";
						}elseif($_GET['tipo']=='1'){
							$tipo="AND caixa.tipolancamento='0'";
						}else{$tipo="AND caixa.tipo='".$_GET['tipo']."'"; $tipolancamento="AND caixa.tipolancamento='1'";}

}else{
	$tipo='';
	$tipolancamento="";
}

// define o tipo se entradas , saidas ou todos

if(isset($_GET['periodo'])){

$intervalo=$_GET['periodo'];

$ini=date('Y-m', strtotime('-1 months', strtotime(date('Y-m-d'))))."-01";
$fim=date("Y-m-t", strtotime("-1 Month",strtotime(date('Y/m/d H:i:s'))));

if($intervalo==0){
	$periodo="AND caixa.data Between '".date("Y-m")."-01' And '".date("Y-m")."-31'";
	$saldoanterior=date("Y-m").'-01';
	}// mes atual
elseif($intervalo==1){
	$periodo="AND caixa.data Between '".date("Y-m")."-01' And '".date("Y-m")."-31'";
	$saldoanterior=date("Y-m").'-01';
	}//mes atual
elseif($intervalo==2){
	$periodo="AND caixa.data Between '".tool::subDayIntoDate(date("Ymd"),7)."' And '".date("Y-m")."-31'";
	$saldoanterior=tool::subDayIntoDate(date("Ymd"),7);
	}// 7 dias
elseif($intervalo==3){
	$periodo="AND caixa.data Between '".tool::subDayIntoDate(date("Ymd"),15)."' And '".date("Y-m-d")."'";
	$saldoanterior=tool::subDayIntoDate(date("Ymd"),15);
	}// 15 dias
elseif($intervalo==4){
	$periodo="AND caixa.data Between '".tool::subDayIntoDate(date("Ymd"),30)."' And '".date("Y-m-d")."'";
	$saldoanterior=tool::subDayIntoDate(date("Ymd"),30);
	}// 30 dias
elseif($intervalo==5){
	$periodo="AND caixa.data Between '".$ini."' And '".$fim."'";
	$saldoanterior=$ini;
	}// mes anterior
elseif($intervalo==6){
	$periodo="AND caixa.data Between '".tool::InvertDateTime(tool::LimpaString($_GET['inicio']),0)."' And '".tool::InvertDateTime(tool::LimpaString($_GET['final']),0)."'";
	$saldoanterior=tool::InvertDateTime(tool::LimpaString($_GET['inicio']),0);
	}// periodo especifico
}

// query listagem de registros
$query= caixa::find_by_sql("SELECT SQL_CACHE
							  caixa.id,
							  caixa.historico,
							  caixa.data,
							  caixa.valor,
							  trim( leading 0 from caixa.numdoc) as numdoc,
							  caixa.tipolancamento,
							  caixa.tipo,
							CASE
								WHEN SubString(caixa.clientes_fornecedores_id FROM 1 FOR 1 ) = 0
								THEN (SELECT associados.nm_associado
									  FROM associados
									  WHERE associados.matricula = SubString(caixa.clientes_fornecedores_id FROM 3 FOR 10 ))
								WHEN  SubString(caixa.clientes_fornecedores_id FROM 1 FOR 1 ) = 1
								THEN (SELECT
											CASE
											WHEN clientes_fornecedores.tipo = '1' THEN clientes_fornecedores.nm_cliente
											WHEN clientes_fornecedores.tipo = '2' THEN clientes_fornecedores.razao_social
											ELSE 'ERRO CLIENTES E FORNECEDORES' END AS nm_clientes_fornecedores
											FROM clientes_fornecedores WHERE clientes_fornecedores.id = SubString(caixa.clientes_fornecedores_id FROM 3 FOR 10 ))
								WHEN SubString(caixa.clientes_fornecedores_id FROM 1 FOR 1 )  = 2 THEN 'RECEBIMENTOS BANCARIOS'
								WHEN SubString(caixa.clientes_fornecedores_id FROM 1 FOR 1 )  = 3 THEN 'TRASNFERÊNCIA ENTRE CONTAS'
								ELSE 'FAVORECIDO NÃO ENCONTRADO'
								END AS favorecido
							FROM
							  caixa
							  LEFT JOIN clientes_fornecedores ON clientes_fornecedores.id =	caixa.clientes_fornecedores_id
							WHERE
							 ".$contabancaria." ".$tipo." ".$tipolancamento." ".$periodo."  AND caixa.empresas_id='".$COB_Empresa_Id."'   ORDER BY data,id ASC");

$linhacaixa= new ArrayIterator($query);

// soma todas as entradas
$result= caixa::find_by_sql("SELECT SUM(valor) AS sum FROM caixa WHERE ".$conta."  data < '".$saldoanterior."' AND tipo='c'  AND empresas_id='".$COB_Empresa_Id."'");
$entradas=$result[0]->sum;
// soma todas as saidas
$result = caixa::find_by_sql("SELECT SUM(valor) AS sum FROM caixa WHERE ".$conta." data < '".$saldoanterior."' AND tipo='d'  AND empresas_id='".$COB_Empresa_Id."'");
$saidas=$result[0]->sum;
//saldo da conta
$total=$entradas-$saidas;
$i = $saldo = $total;
$l =$saldoent=0;
$i =$saldosaid=0;
?>
</div>
<table class="uk-table uk-table-striped uk-table-hover" id="grid_fluxo_caixa" style="border:1px solid #ccc;">
<tbody >
    <tr>
        <td class="uk-width uk-text-center" style="width:20px;" ></td>
		<td class="uk-width uk-text-center" style="width:90px;" ></td>
        <td class="uk-width uk-text-center" style="width:100px;" >
		  <?php
          $mesano=date('m/Y', strtotime('-1 months', strtotime(date('Y-m'))));
          echo date('d/m/Y', strtotime('-1 day', strtotime($saldoanterior)));
           ?>
        </td>
        <td class=" uk-text-left"  >SALDO ANTERIOR</td>
        <td class="uk-width uk-text-left" style="width:200px;" ></td>
        <td class="uk-text-center" style="color:#fff;" ></td>
        <td class="uk-width uk-text-center" style="width:100px;" ></td>
        <td class="uk-width uk-text-center" style="width:100px;" >0,00</td>
        <td class="uk-width uk-text-center" style="width:100px;" >0,00</td>
        <td class="uk-width uk-text-center" style="width:100px;" ><?php echo number_format($saldo,2,",","."); ?></td>
    </tr>
<?php


$dt="";

while($linhacaixa->valid()):



$now=new ActiveRecord\DateTime($linhacaixa->current()->data);

/*coloca em negrido o saldo do dia*/
if($now->format('d/m/Y') != $dt){$style_sd_dia="uk-text-bold";}else{$style_sd_dia="uk-text-muted";}

if($linhacaixa->current()->tipo=="d") {
    $saldo -= $linhacaixa->current()->valor;
	$saldosaid+=$linhacaixa->current()->valor;
		    } else {
			$saldo += $linhacaixa->current()->valor;
			$saldoent+=$linhacaixa->current()->valor;
			}


 ?>
        <tr onclick="Editar_Linha('<?php echo $linhacaixa->current()->id; ?>','<?php echo $linhacaixa->current()->tipo; ?>');">
        <th class="uk-width uk-text-left" style="width:20px;" >
			<?php
if($linhacaixa->current()->tipolancamento=='0'){

			if($linhacaixa->current()->tipo=='d'){
				echo '<i class="uk-icon-exchange" style="color:#F00; font-size:10px; margin-top:5px;" title="Debito Transferencia" ></i>';
				}else{
					echo '<i class="uk-icon-exchange" style="color:#0CC; font-size:10px;margin-top:5px;" title="Credito Transferencia"></i>';
					}

			}else{

			if($linhacaixa->current()->tipo=='d'){
				echo '<i class="uk-icon-reply" style="color:#F00; font-size:12px;margin-top:5px;" title="Pagamento"></i>';
			}else{
				echo '<i class="uk-icon-share" style="color:#0CC; font-size:12px;margin-top:5px;" title="Deposito"></i>';
				}

}
		?>
        </th>
		<td class="uk-width uk-text-center" style="width:90px;" ><?php echo tool::CompletaZeros(11,$linhacaixa->current()->id); ?></td>
        <td class="uk-width uk-text-center" style="width:100px;" >
			<?php echo $now->format('d/m/Y');?>
        </td>
        <td class="uk-width uk-text-left" style="width:300px; text-transform:uppercase;max-width:300px;white-space:nowrap;text-overflow:ellipsis; overflow:hidden;" >
        	<?php echo  str_replace('COMPENSAÃ‡ÃƑO', 'COMPENSAÇÃO', $linhacaixa->current()->historico);?>
        </td>
        <td class="uk-width uk-text-left" style="width:200px;text-transform:uppercase;max-width:200px;white-space:nowrap;text-overflow:ellipsis; overflow:hidden;"  >
        <?php echo  $linhacaixa->current()->favorecido;?>
        </td>
        <td class="uk-text-center" style="color:#fff;" ></td>

        <td class="uk-width uk-text-center" style="width:150px; max-width:150px;white-space: nowrap;text-overflow: ellipsis; overflow:hidden;" >
           <?php echo strtoupper($linhacaixa->current()->numdoc); ?>
        </td>
        <td class="uk-width uk-text-center" style="width:100px; color:#06F;" >
          <?php if($linhacaixa->current()->tipo=='c'){echo number_format($linhacaixa->current()->valor,2,",",".");}else{echo '-';} ?>
        </td>
        <td class="uk-width uk-text-center" style="width:100px; color:#F00;" >
          <?php if($linhacaixa->current()->tipo=='d'){echo number_format($linhacaixa->current()->valor,2,",",".");}else{echo '-';} ?>
        </td>
        <td class="uk-width uk-text-center <?php echo $style_sd_dia; ?>" style="width:100px;" >
          <?php echo number_format($saldo,2,",","."); ?>
        </td>
   	</tr>
<?php

$dt=$now->format('d/m/Y');

$linhacaixa->next();
endwhile;
?>
 </tbody>
 <tfoot  >
      <tr style="height:25px;" >
        <th class="uk-width uk-text-center" style="width:20px;" ></th>
		<th class="uk-width uk-text-center" style="width:90px;" ></th>
        <th class="uk-width uk-text-center" style="width:100px;" ></th>
        <th class="uk-text-left" style="color:#fff;" ></th>
        <th class="uk-width uk-text-left" style="width:200px; color:#fff;"></th>
        <th class="uk-text-center" style="color:#fff;" ></th>
        <th class="uk-width uk-text-center" style="width:100px; color:#fff;" ></th>
        <th class="uk-width uk-text-center" style="width:100px; color:#06C;" ><?php echo number_format($saldoent,2,",",".");?></th>
        <th class="uk-width uk-text-center" style="width:100px; color:#F00;" ><?php echo number_format($saldosaid,2,",",".");?></th>
        <th class="uk-width uk-text-center" style="width:100px; color:#090;" ><?php echo number_format($saldo,2,",",".");?></th>
        </tr>
 </tfoot>
</table>
<script type="text/javascript">
// carrega com o ponteiro no fim da pagina
var scroll=$("#grid_fluxo_caixa").height();
$("#gridlancamentos").scrollTop(scroll);

function Editar_Linha(id,tipo){

// mensagen de carregamento
jQuery("#msg_loading").html(" Aguarde ");
//abre a tela de preload
modal.show();

//fecha janelas abertas
jQuery(".Window").remove();

var tipo=tipo;

if(tipo == 'c'){

	New_window('money','700','440','Depositar','assets/financeiro/caixa/Frm_depositar.php?id='+id+'',true,false,'Aguarde ...');// se a linha a ser editar for credito

}else{

	New_window('money','700','440','Pagar','assets/financeiro/caixa/Frm_pagar.php?id='+id+'',true,false,'Aguarde ...');// se a linha a ser editar for credito
}

}

</script>