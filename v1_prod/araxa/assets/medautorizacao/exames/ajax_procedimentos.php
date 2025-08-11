<?php
require_once("../../../conexao.php");
require_once("../../../config_sys.php");
require_once('../../../functions/funcoes.php');
$cfg->set_model_directory('../../../models/');

$keyword = $_POST['keywordpro'];
$cdconvenio = $_POST['cdconvenio'];
$cdparceiro = $_POST['cdparceiro'];

	

$query_p=procedimentos::find_by_sql("SELECT
									  ".$Prefixo_SYS."tbprocedimentosconvenio.cdprocedimentoconvenio,
									  ".$Prefixo_SYS."tbprocedimentosconvenio.valordecusto,
									  ".$Prefixo_SYS."tbprocedimentosconvenio.valorconvenio,
									  ".$Prefixo_SYS."tbprocedimentos.cdprocedimento,
									  ".$Prefixo_SYS."tbprocedimentos.descricao
									FROM
									  ".$Prefixo_SYS."tbprocedimentosconvenio
									  INNER JOIN ".$Prefixo_SYS."tbprocedimentos ON ".$Prefixo_SYS."tbprocedimentos.cdprocedimento =
										".$Prefixo_SYS."tbprocedimentosconvenio.cdprocedimento
									WHERE
									  ".$Prefixo_SYS."tbprocedimentosconvenio.cdconvenio='".$cdconvenio."' AND ".$Prefixo_SYS."tbprocedimentosconvenio.cdparceiro='".$cdparceiro."' 
									  AND ".$Prefixo_SYS."tbprocedimentos.descricao LIKE '".$keyword."%' ORDER BY descricao"
									  );

$procedimentos= new ArrayIterator($query_p);

if($query_p){
while($procedimentos->valid()):

$vlpro=$procedimentos->current()->valordecusto + $procedimentos->current()->valorconvenio;	
	// add new option
	echo"<tr  style=\"line-height:22px;\" onclick=\"set_item_pro('".$procedimentos->current()->cdprocedimentoconvenio."')\">";
    echo"<td class=\"uk-width uk-text-center\" style=\"width:50px;\">".$procedimentos->current()->cdprocedimento."</td>";
    echo"<td class=\"uk-text-left\"  >".$procedimentos->current()->descricao."</td>";
	echo"<td class=\"uk-width uk-text-center\" style=\"width:80px;\">R$ ".number_format($vlpro,2,",",".")."</td>";
    echo"</tr>";

$procedimentos->next();
endwhile;

}else{
		// add new option
	echo"<tr  style=\"line-height:22px;\" >";
    echo"<td class=\"uk-text-center\" >Procedimento Não Encontrado</td>";
    echo"<td class=\"uk-text-left\"  ></td>";
    echo"</tr>";

	}
?>
