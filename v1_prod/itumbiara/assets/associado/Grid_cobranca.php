<?php
require_once("../../sessao.php");
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$matId = $_GET['mat'];
$prtId = $_GET['id'];

$query_a=protestos_detalhes::find_by_sql("SELECT 
                                            protestos_detalhes.* ,
                                            usuarios.nm_usuario
                                            FROM protestos_detalhes
                                            INNER JOIN usuarios ON usuarios.id = protestos_detalhes.usuario_id
                                            WHERE protesto_id='".$prtId."'");
$associados= new ArrayIterator($query_a);
?>
<nav class="uk-navbar uk-gradient-cinza">
    <div class="uk-navbar-content uk-hidden-small uk-form">
    </div>
    <div class="uk-navbar-content uk-navbar-flip">
        <a id="btn_new_ass" class="uk-button uk-button-success"><i class="uk-icon-plus"></i> Novo</a>
    </div>
</nav>
<div id="gridHist" style="height:434px; overflow-y:auto; padding:10px; margin-top: 2px; ">
    <?php

    while($associados->valid()):
        $datacad= new ActiveRecord\DateTime($associados->current()->created_at);

        echo '<div class="uk-article">

                                <h6 class="uk-article-title" style=" text-transform: uppercase; font-size: 18px;">Usuário: '.$associados->current()->nm_usuario.'</h6>
                                <p class="uk-article-meta">Histórico adcionado em: '.$datacad->format("d/m/Y h:i:s").'</p>
                                <p>Histórico:</p>
                                <p>'.$associados->current()->detalhes.'</p>
                            </div><hr>';
        $associados->next();
    endwhile;
    ?>
</div>
<script>
    jQuery("#btn_new_ass").click(function () {
        New_window('archive', '550', '270', 'Adcionar histórico', 'assets/associado/Frm_cobranca_adv.php?id=<?=$prtId;?>', true, false, 'Carregando...');
    });
</script>
