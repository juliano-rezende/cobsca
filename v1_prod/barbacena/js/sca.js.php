<script type="text/javascript">
// fazer algo aqui para quando a tecla F5 for premida
window.addEventListener('keydown', function (e) { var code = e.which || e.keyCode; if (code == 116) e.preventDefault(); else return true;});
/* desabilita o botão direito do mouse*/
jQuery(document).ready(function(){jQuery(document).bind("contextmenu",function(e){e.preventDefault();return false;});});

/* Contador de tempo da sessão*/
var tempo = new Number();

/* Tempo em segundos*/
time  = <?php echo ($_SESSION[''.$Prefixo_SYS.'created']+$_SESSION[''.$Prefixo_SYS.'duraction'])-time(); ?>;

/*
var str=tempo+'-'+temp_ini;
jQuery(".uk-navbar-content").html(''+str+'');

*/

/*Função para controle do relogio de tempo da sessão do usuario*/
function startCountdown(){


        /* Se o tempo não for zerado*/
        if((time - 1) >= 0){
            /* Pega a parte inteira dos minutos*/
            var min = parseInt(time/60);
            /* Calcula os segundos restantes*/
            var seg = time%60;
            /* Formata o número menor que dez, ex: 08, 07, .../*/
            if(min < 10){
                min = "0"+min;
                min = min.substr(0, 2);
            }

            if(seg <=9){
                seg = "0"+seg;
            }
            /* Cria a variável para formatar no estilo hora/cronômetro*/
            horaImprimivel = min + ' minutos e ' + seg+' segundos';
            /*JQuery pra setar o valor*/
            Jquery("#Coutdow").html(horaImprimivel);
            /* Define que a função será executada novamente em 1000ms = 1 segundo*/
            setTimeout('startCountdown()',1000);
            /* diminui o tempo*/
            time--;
        /* Quando o contador chegar a zero faz esta ação*/
        } else {alert("Conexão encerrada!");
            window.open('logout.php', '_self');}
};
/* Chama a função ao carregar a tela*/

startCountdown();



</script>