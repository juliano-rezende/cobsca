<?php
    
    $Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro
    
    require_once "../../../sessao.php";
    require_once("../../../conexao.php");
    require_once("../../../config_ini.php");
    $cfg -> set_model_directory('../../../models/');


// cartão de credito
    
    $FRM_nm_cli = $_POST['nm_cli'];
    $FRM_numero_cc = preg_replace("/[^0-9]/", "", $_POST['n_cc']);
    $FRM_vm = $_POST['vm'];
    $FRM_vy = $_POST['vy'];
    $FRM_cod_seg_cc = $_POST['c_s_cc'];
    $FRM_dc = $_POST['dc']; // dado de cobrança do cliente
    $FRM_ca_c_id = $_POST['a_c_id']; // id de cadastro do cliente na api de integração intermediaria APICOB
    
    $Query_dados_cobranca = dados_cobranca ::find_by_sql("SELECT * FROM dados_cobranca WHERE id='" . $FRM_dc . "'");


    if ($Query_dados_cobranca[0] -> api_cob_cliente_id == "") {
        
        $dadosassociado = associados ::find_by_sql("SELECT SQL_CACHE
                                                        associados.nm_associado, associados.fone_cel,  associados.num,  associados.email,
                                                        associados.cpf, associados.dt_nascimento, logradouros.descricao as nm_logradouro,
                                                        logradouros.complemento,  logradouros.cep,  estados.sigla AS nm_estado,
                                                        cidades.descricao AS nm_cidade, bairros.descricao AS nm_bairro
                                                        FROM
                                                        associados
                                                        LEFT JOIN logradouros ON logradouros.id = associados.logradouros_id
                                                        LEFT JOIN estados ON estados.id = logradouros.estados_id
                                                        LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
                                                        LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
                                                        WHERE
                                                        associados.matricula = '" . $Query_dados_cobranca[0] -> matricula . "'");
        
        
        $end = $dadosassociado[0] -> complemento . " " . $dadosassociado[0] -> nm_logradouro . ", " . $dadosassociado[0] -> num;
        $compl = "";
        $end1 = "{$dadosassociado[0]->nm_cidade}";
        $end2 = "{$dadosassociado[0]->nm_bairro}";
        $end3 = "{$dadosassociado[0]->cep}";
        
        if (
            empty($end) ||
            empty($end1) ||
            empty($end2) ||
            empty($end3)
        ) {
            $json = '":"","failCad":"true","message":"Cod 53 Transação não realizada. Endereço incompleto, favor completar o cadastro';
            echo $json;
            die();
        }

        
        $fullName = explode(" ", $dadosassociado[0] -> nm_associado);
        $name = $fullName[0];
        $lastName = str_replace("$name", "", $dadosassociado[0] -> nm_associado);
        
        $dt_nasc = new ActiveRecord\DateTime($dadosassociado[0] -> dt_nascimento);
        
        require_once "../../../classes/FuturaApi/paymentBuyer.php";
        $sendCreateBuyer = (new paymentBuyer())
            -> setToken(TOKEN_API_COB)
            -> setCompany(COMPANY_ID_API_COB)
            -> createClient("{$Query_dados_cobranca[0]->matricula}",
                "{$end}",
                "{$compl}",
                "{$dadosassociado[0]->nm_bairro}",
                "{$dadosassociado[0]->nm_cidade}",
                "{$dadosassociado[0]->nm_estado}",
                "{$dadosassociado[0]->cep}",
                "{$name}",
                "{$lastName}",
                "{$dadosassociado[0]->email}",
                "{$dadosassociado[0]->fone_cel}",
                "{$dadosassociado[0]->cpf}",
                "{$dt_nasc->format('Y-m-d')}",
                "Associado Cob Barbacena Matricula: " . $Query_dados_cobranca[0] -> matricula . "");
        
        $resultSendCreateBuyer = json_decode($sendCreateBuyer);

        if ($resultSendCreateBuyer -> error == "true") {
            
            $json = '":"","failCad":"true","message":"Cod 88 Erro:' . $resultSendCreateBuyer -> message;
            echo $json;
            die();
            
        } else {
            
            require_once "../../../classes/FuturaApi/paymentAddCard.php";
            
            $sendAddCard = (new paymentAddCard())
                -> setToken(TOKEN_API_COB)
                -> setCompany(COMPANY_ID_API_COB)
                -> AddCard("{$Query_dados_cobranca[0]->matricula}",
                    "{$FRM_nm_cli}",
                    "{$FRM_vm}",
                    "{$FRM_vy}",
                    "{$FRM_numero_cc}",
                    "{$FRM_cod_seg_cc}",
                    "{$resultSendCreateBuyer->apicobclienteid}");
            
            $resultSendAddCard = json_decode($sendAddCard);
            
            if ($resultSendAddCard -> error == "true") {
                
                $json = '":"","failCad":"true","message":"Cod 111 Erro:' . $resultSendAddCard -> message;
                echo $json;
                die();
                
            } else {
                
                /*
                 * criar o cartão na tabela associados_cards
                 */
                $create_card = associados_cards ::create(
                    array(
                        'matricula' => "{$Query_dados_cobranca[0]->matricula}",
                        'number_card' => "{$FRM_numero_cc}",
                        'api_cob_card_id' => "{$resultSendAddCard->apicobcardid}",
                        'api_futura_card_token' => "{$resultSendAddCard->apifuturacardtoken}",
                        'status' => 1
                    ));
                
                $last_add_card = associados_cards ::last();
                
                $updateDadosCobranca = dados_cobranca ::find($Query_dados_cobranca[0] -> id);
                $updateDadosCobranca -> update_attributes(
                    array(
                        'api_cob_cliente_id' => "{$resultSendCreateBuyer->apicobclienteid}",
                        'associados_card_id' => "{$last_add_card->id}"
                    ));

                $json = '":"","failCad":"false","message":"Cartão cadastrado com sucesso.';
                echo $json;
                die();
            }
        }
        
    } else {
        
        require_once "../../../classes/FuturaApi/paymentAddCard.php";
        
        $sendAddCard = (new paymentAddCard())
            -> setToken(TOKEN_API_COB)
            -> setCompany(COMPANY_ID_API_COB)
            -> AddCard("{$Query_dados_cobranca[0]->matricula}",
                "{$FRM_nm_cli}",
                "{$FRM_vm}",
                "{$FRM_vy}",
                "{$FRM_numero_cc}",
                "{$FRM_cod_seg_cc}",
                "{$Query_dados_cobranca[0]->api_cob_cliente_id}");
        
        $resultSendAddCard = json_decode($sendAddCard);

        if ($resultSendAddCard -> error == "true") {
            
            $json = '":"","failCad":"true","message":"Cod 163 Erro:' . $resultSendAddCard -> message;
            echo $json;
            die();
            
        } else {
            
            /*
             * criar o cartão na tabela associados_cards
             */
            $create_card = associados_cards ::create(
                array(
                    'matricula' => "{$Query_dados_cobranca[0]->matricula}",
                    'number_card' => "{$FRM_numero_cc}",
                    'api_cob_card_id' => "{$resultSendAddCard->apicobcardid}",
                    'api_futura_card_token' => "{$resultSendAddCard->apifuturacardtoken}",
                    'status' => 1
                ));
            
            $last_add_card = associados_cards ::last();
            
            $updateDadosCobranca = dados_cobranca ::find($Query_dados_cobranca[0] -> id);
            $updateDadosCobranca -> update_attributes(
                array(
                    'api_cob_cliente_id' => "{$Query_dados_cobranca[0]->api_cob_cliente_id}",
                    'associados_card_id' => "{$last_add_card->id}"
                ));
            
            $json = '":"","failCad":"false","message":"Cartão cadastrado com sucesso.';
            echo $json;
            die();
        }
    }
