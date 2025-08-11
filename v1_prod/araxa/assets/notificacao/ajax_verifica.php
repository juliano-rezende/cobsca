<?php
require_once("../../sessao.php");
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


/* query das notificações */
$Q_notificacoes=notificacoes::find_by_sql("SELECT count(id) as total  FROM notificacoes WHERE status='0' AND operador_id='".$COB_Usuario_Id."'");

if($Q_notificacoes[0]->total > 0){echo "1";}else{echo "erro";}

	


?>