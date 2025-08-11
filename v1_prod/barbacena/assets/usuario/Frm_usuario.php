<?php
require_once "../../sessao.php";

include("../../conexao.php");
$cfg->set_model_directory('../../models/');

if (isset($_GET[ 'user_id' ])) {


    // recupera os dados do usuario
    $dadosusuario = users::find($_GET[ 'user_id' ]);

    $cdusuario = $dadosusuario->id;

    $query = menu::all();

    if ($dadosusuario->status != 2) {

        $subclasse = 'uk-alert-warning';

    } else {

        $subclasse = 'uk-alert-success';
    }


} else {

    $st = "";
}

?>
<link rel="stylesheet" href="framework/uikit-2.24.0/css/components/form-password.min.css">
<form method="post" id="FrmUsuario" class="uk-form uk-form-shadow " style="width: 900px;">
    <div id="menu-float" style="text-align:center;margin:0 900px;">
        <a id="Btn_user_0" class="uk-icon-button uk-icon-search" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Pesquisar" data-cached-title="Pesquisar"></a>

        <hr class="uk-article-divider">

        <?php if (isset($dadosusuario)): ?>
            <a id="Btn_user_1" class="uk-icon-button uk-icon-plus" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Novo usuario" data-cached-title="Novo usuario"></a>
            <a id="Btn_user_2" class="uk-icon-button uk-icon-list-ul" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Permissoes" data-cached-title="Permissoes"></a>
            <a id="Btn_user_3" class="uk-icon-button  uk-icon-pencil " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Salvar Alterações" data-cached-title="Salvar Alterações"></a>

        <?php
        else:
            ?>
            <a id="Btn_user_3" class="uk-icon-button  uk-icon-floppy-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Gravar Novo" data-cached-title="Gravar Novo"> </a>

        <?php endif;
        ?>
    </div>
    <fieldset style="width:870px;">

        <legend><i class="uk-icon-user "></i> Dados do Usuario</legend>

        <label> <span>Codigo</span> <input value="<?php if (isset($dadosusuario)):
                echo tool::CompletaZeros(3, $dadosusuario->id); endif; ?>" name="usuario_id" type="text" class="w_80 uk-text-center  <?php echo $subclasse; ?>" readonly id="usuario_id"/>
        </label>


        <?php if ($COB_Acesso_Id == 3 or $COB_Acesso_Id == 4) { ?>

            <label> <span>Empresa</span> <select class="select" name="empresa_id" id="empresa_id">
                    <?php


                    if (isset($dadosusuario)):

                        $empresausuario = empresas::find($dadosusuario->empresas_id);

                        echo '<option value="' . $empresausuario->id . '" selected="selected">' . utf8_encode($empresausuario->nm_fantasia) . '</option>';

                        $descricaoempresa = empresas::find('all');
                        $descricao = new ArrayIterator($descricaoempresa);
                        while ($descricao->valid()):
                            echo '<option value="' . $descricao->current()->id . '" >' . utf8_encode($descricao->current()->nm_fantasia) . '</option>';
                            $descricao->next();
                        endwhile;
                    else:
                        echo '<option value="" ></option>';
                        $descricaoempresa = empresas::find('all');
                        $descricao = new ArrayIterator($descricaoempresa);
                        while ($descricao->valid()):
                            echo '<option value="' . $descricao->current()->id . '" >' . utf8_encode($descricao->current()->nm_fantasia) . '</option>';
                            $descricao->next();
                        endwhile;
                    endif;

                    ?>
                </select> </label>
        <?php } ?>

        <label> <span>Nome Completo</span> <input value="<?php if (isset($dadosusuario)):
                echo $dadosusuario->nm_usuario; endif; ?>" name="nmcompleto" type="text" class="w_400 uk-text-left" id="nmcompleto" autocomplete="off"/>
        </label>

        <label> <span>Usuario</span> <input value="<?php if (isset($dadosusuario)):
                echo $dadosusuario->login; endif; ?>" name="username" type="text" class="w_200 uk-text-left" id="username" autocomplete="off"/>
        </label>

        <label> <span>Senha </span>
            <a href="" class="uk-form-password-toggle" data-uk-form-password="" style="margin:10px 560px;">Show</a>
            <input value="<?php if (isset($dadosusuario)):echo $dadosusuario->senha; endif; ?>" name="pwd" type="password" class="w_150 uk-text-left" id="pwd" autocomplete="off"/>

            <input value="<?php if (isset($dadosusuario)):echo $dadosusuario->senha; endif; ?>" name="r_pwd" type="hidden" id="r_pwd"/>
            <input value="<?php if (isset($dadosusuario)):echo $dadosusuario->salt; endif; ?>" name="st" type="hidden" id="st"/>
        </label>

        <?php if ($COB_Acesso_Id >= 3 || $COB_Acesso_Id >= 4) { ?>
            <label> <span>Nivel de acesso ?</span> <select name="access" id="access" class="select w_100">

                    <?php
                    if (isset($dadosusuario)):

                        $acessosuser = acessos::find($dadosusuario->acessos_id);
                        echo '<option value="' . $acessosuser->id . '" selected="selected">' . $acessosuser->nivel . '</option>';
                        $acessosuser = acessos::find('all');
                        $descricao = new ArrayIterator($acessosuser);
                        while ($descricao->valid()):
                            echo '<option value="' . $descricao->current()->id . '" >' . $descricao->current()->nivel . '</option>';
                            $descricao->next();
                        endwhile;
                    else:
                        echo '<option value="" ></option>';
                        $acessosuser = acessos::find('all');
                        $descricao = new ArrayIterator($acessosuser);
                        while ($descricao->valid()):
                            echo '<option value="' . $descricao->current()->id . '" >' . $descricao->current()->nivel . '</option>';
                            $descricao->next();
                        endwhile;
                    endif;
                    ?>

                </select> </label>

        <?php } ?>


        <label> <span>Expirar Senha ?</span> <select name="password_expires" id="password_expires" class="select w_100">
                <?php
                if (isset($dadosusuario)):
                    if ($dadosusuario->senha_expira == 0):
                        echo '
             <option value="1">Sim</option>
             <option value="0" selected>Não</option>
            ';
                    else:
                        echo '
             <option value="1" selected>Sim</option>
             <option value="0" >Não</option>
            ';
                    endif;
                else:
                    echo '
           <option value="" selected></option>
           <option value="1">Sim</option>
           <option value="0">Não</option>
         ';
                endif;
                ?>
            </select> </label>

        <label> <span>Data Expira</span>

            <input name="date_expires_pass" type="text" class="w_100 uk-text-center" id="date_expires_pass" placeholder="00/00/0000" data-uk-datepicker="{format:'DD/MM/YYYY'}" value="<?php
            if (isset($dadosusuario)):
                $now = new ActiveRecord\DateTime($dadosusuario->data_senha_expira);
                echo $now->format('d/m/Y');
            else:
            endif;
            ?>"> </label>

        <label> <span>Notificar</span> <select name="notificar" id="notificar" class="select w_100">
                <?php
                if (isset($dadosusuario)):
                    if ($dadosusuario->notificar == 0):
                        echo '
             <option value="1">Sim</option>
             <option value="0" selected>Não</option>
            ';
                    else:
                        echo '
             <option value="1" selected>Sim</option>
             <option value="0" >Não</option>
            ';
                    endif;
                else:
                    echo '
           <option value="" selected></option>
           <option value="1">Sim</option>
           <option value="0">Não</option>
         ';
                endif;
                ?>
            </select>
            <div class="uk-badge uk-text-warning" style="background-color:transparent;">Este Usuario irá receber notificações do sistema ?</div>
        </label>
    </fieldset>

    <fieldset style="width:870px;">
        <legend><i class="uk-icon-user "></i> Dados do Acesso</legend>

        <label> <span>Ativo ?</span> <select name="status" id="status" class="select w_100">
                <?php
                if (isset($dadosusuario)):
                    if ($dadosusuario->status == 0):
                        echo '
             <option value="1">Ativo</option>
             <option value="0" selected>Inativo</option>
            ';
                    else:
                        echo '
             <option value="1" selected>Ativo</option>
             <option value="0" >Inativo</option>
            ';
                    endif;
                else:
                    echo '
           <option value="" selected></option>
           <option value="1">Ativo</option>
           <option value="0">Inativo</option>
         ';
                endif;
                ?>
            </select> </label>

        <label> <span>Dias de acesso</span>

            <select name="day_acs_user" id="day_acs_user" class="select w_100">
                <?php if (isset($dadosusuario)): ?>

                    <option value="1111111" <?php if ($dadosusuario->day_access_user == 0000000) {
                        echo 'selected="selected"';
                    } ?>></option>
                    <option value="1000000" <?php if ($dadosusuario->day_access_user == 1000000) {
                        echo 'selected="selected"';
                    } ?>>Seg
                    </option>
                    <option value="1100000" <?php if ($dadosusuario->day_access_user == 1100000) {
                        echo 'selected="selected"';
                    } ?>>Seg / Ter
                    </option>
                    <option value="1110000" <?php if ($dadosusuario->day_access_user == 1110000) {
                        echo 'selected="selected"';
                    } ?>>Seg / Ter / Qua
                    </option>
                    <option value="1111000" <?php if ($dadosusuario->day_access_user == 1111000) {
                        echo 'selected="selected"';
                    } ?>>Seg / Ter / Qua / Qui
                    </option>
                    <option value="1111100" <?php if ($dadosusuario->day_access_user == 1111100) {
                        echo 'selected="selected"';
                    } ?>>Seg / Ter / Qua / Qui / Sex
                    </option>
                    <option value="1111110" <?php if ($dadosusuario->day_access_user == 1111110) {
                        echo 'selected="selected"';
                    } ?>>Seg / Ter / Qua / Qui / Sex / Sab
                    </option>
                    <option value="1111111" <?php if ($dadosusuario->day_access_user == 1111111) {
                        echo 'selected="selected"';
                    } ?>>Seg / Ter / Qua / Qui / Sex / Sab / Dom
                    </option>
                <?php
                else:
                    ?>
                    <option value="1111111" selected="selected"></option>
                    <option value="1000000">Seg</option>
                    <option value="1100000">Seg / Ter</option>
                    <option value="1110000">Seg / Ter / Qua</option>
                    <option value="1111000">Seg / Ter / Qua / Qui</option>
                    <option value="1111100">Seg / Ter / Qua / Qui / Sex</option>
                    <option value="1111110">Seg / Ter / Qua / Qui / Sex / Sab</option>
                    <option value="1111110">Seg / Ter / Qua / Qui / Sex / Sab / Dom</option>
                <?php endif; ?>

            </select>
            <div class="uk-badge uk-text-warning" style="background-color:transparent;">Dias liberado para acesso.</div>
        </label>


        <label> <span>1º horario</span>
            <input name="interval_am" type="text" class="w_100 uk-text-center" id="interval_am" placeholder="00:00 - 00:00" value="<?php
            if (isset($dadosusuario)):
                echo $dadosusuario->interval_am;
            endif;
            ?>">
            <div class="uk-badge uk-text-warning" style="background-color:transparent;">Horario de trabalho do usuario periodo da manhã.</div>
        </label>

        <label> <span>2º Horario</span>
            <input name="interval_pm" type="text" class="w_100 uk-text-center" id="interval_pm" placeholder="00:00 - 00:00" value="<?php
            if (isset($dadosusuario)):
                echo $dadosusuario->interval_pm;
            endif;
            ?>">
            <div class="uk-badge uk-text-warning" style="background-color:transparent;">Horario de trabalho do usuario periodo da tarde.</div>
        </label>
    </fieldset>


    <fieldset style="width:870px;">
        <legend><i class="uk-icon-info-circle "></i> Dados do Cadastro</legend>

        <label> <span>Data de Inclusão</span>
            <div class="uk-form-icon">
                <i class="uk-icon-calendar-o"></i>
                <input name="datacadastro" type="text" class="w_100 uk-text-center" id="datacadastro" value="<?php
                if (isset($dadosusuario)):
                    $now = new ActiveRecord\DateTime($dadosusuario->data_cadastro);
                    echo $now->format('d/m/Y');
                endif;
                ?>" readonly="readonly">
            </div>
        </label>

        <label> <span>ultima alteracao</span>
            <div class="uk-form-icon">
                <i class="uk-icon-calendar-o"></i>
                <input name="ultimaalteracao" type="text" class="w_150 uk-text-center" id="ultimaalteracao" value="<?php
                if (isset($dadosusuario)):
                    $now = new ActiveRecord\DateTime($dadosusuario->ultimo_acesso);
                    echo $now->format('d/m/Y H:i:s');
                endif;
                ?>" readonly="readonly">
            </div>
        </label>


        <label> <span>Alterado por</span>
            <div class="uk-form-icon">
                <i class="uk-icon-user"></i>
                <input name="usuario" type="text" class="w_200 uk-text-left " id="usuario" value="<?php
                if (isset($dadosusuario)) {

                    if ($dadosusuario->id == $dadosusuario->usuarios_id) {
                        echo $dadosusuario->login;
                    } else {
                        // recupera os dados do usuario
                        $ateradopor = users::find($dadosusuario->usuarios_id);
                        echo $ateradopor->login;

                    }

                } else {
                    echo $COB_username;
                }
                ?>" readonly="readonly">
            </div>
        </label>


    </fieldset>

</form>
<script src="framework/uikit-2.24.0/js/components/form-password.min.js"></script>
<script type="text/javascript" src="assets/js/usuario.min.js?<?php echo microtime(); ?>"></script>