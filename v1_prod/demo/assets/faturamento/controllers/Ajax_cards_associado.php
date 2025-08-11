<?php
$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once "../../../sessao.php";
require_once("../../../conexao.php");
require_once("../../../config_ini.php");
$cfg->set_model_directory('../../../models/');

$FRM_matricula = $_GET['mat'];

$Cards = associados_cards::find_by_sql("SELECT 
                                            associados_cards.id,associados_cards.number_card,dados_cobranca.associados_card_id
                                            FROM 
                                            associados_cards
                                             LEFT JOIN dados_cobranca ON dados_cobranca.matricula = associados_cards.matricula
                                            WHERE 
                                            associados_cards.matricula= '" . intval($FRM_matricula) . "' AND associados_cards.status = '1'"
                                    );
$CardsList = new ArrayIterator($Cards);
$cards = "";
while ($CardsList->valid()):

    if ($CardsList->current()->associados_card_id == $CardsList->current()->id) {
        $select = 'selected="selected"';
        $recorrencia = "Cobrança recorrente";
    } else {
        $select = "";
        $recorrencia = "";
    }

    $novo = str_split($CardsList->current()->number_card, 4);
    $card_number = $novo[0] . str_repeat('*', 8) . $novo[3];

    $cards .= '<option  value="' . $CardsList->current()->id . '" ' . $select . ' >' . $card_number . '  '.$recorrencia.'</option>';

    $CardsList->next();
endwhile;

echo $cards;