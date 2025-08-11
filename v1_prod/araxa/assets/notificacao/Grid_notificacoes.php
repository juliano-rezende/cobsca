<div class="tabs-spacer" style="display:none;">
<?php
header ('Content-type: text/html; charset=UTF-8');
require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

/* query das notificações */
$Q_notificacoes=notificacoes::find_by_sql("SELECT SQL_CACHE * FROM notificacoes WHERE status='0' AND operador_id='".$COB_Usuario_Id."' ORDER BY id DESC limit 20");
$list_notif= new ArrayIterator($Q_notificacoes);

?>
</div>
<link rel="stylesheet" href="framework/uikit-2.24.0/css/components/accordion.min.css">
<script type="text/javascript" src="framework/uikit-2.24.0/js/components/accordion.min.js"></script>

<div class="uk-panel uk-text-left" style="height: 385px; overflow-y: auto; width:688px; padding: 5px; ">
	<div class="uk-accordion" data-uk-accordion="{showfirst:false,toggle:'.uk-icon-toggle'}" >
		<?php
		if(count($Q_notificacoes) > 0){

			while($list_notif->valid()):

				$dtp = new ActiveRecord\DateTime($list_notif->current()->data_hora);
				?>
				<h3 class="uk-accordion-title uk-text-small" style="background-color: #666; color: #fff; border-radius: 0;padding:10px;">

					<i class="uk-icon-calendar" style="margin-right: 5px;"></i> <?php echo $dtp->format('d/m/Y h:m:s')." - ".($list_notif->current()->msg); ?>

					<i class="uk-icon-plus uk-text-muted uk-icon-toggle" style="float:right; margin-top: 2px;" uk-data-st="<?php echo $list_notif->current()->status; ?>" uk-data-id="<?php echo $list_notif->current()->id; ?>"></i>
				</h3>
				<div data-wrapper="true" style="height: 0px; position: relative; overflow: hidden;" aria-expanded="false">
					<div class="uk-accordion-content" style="text-transform: capitalize; font-size: 10px; border:1px solid #ccc; padding: 5px;">
						<p><?php echo $list_notif->current()->obs; ?></p>
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

<script type="text/javascript" >

//libera a autorização
jQuery(function() {

	jQuery(".uk-btn-notify").click(function(event) {

		var data_id   = jQuery(this).attr("uk-data-id");
		var data_not_id   = jQuery(this).attr("uk-data-not-id");

		// mensagen de carregamento
		jQuery("#msg_loading").html(" Aguarde ... ");

		//abre a tela de preload
		modal.show();

		//desabilita o envento padrao do formulario
		event.preventDefault();

		 	jQuery.ajax({
		 	async: true,
		 	url: "assets/medautorizacao/Controller_autorizacoes.php",
		 	type: "post",
		 	data:"action=3&autId="+data_id+"&notId="+data_not_id+"",
		 	success: function(resultado) {
		 		if(jQuery.isNumeric(resultado)){
					
					modal.hide();
              		UIkit.notify("Autorização liberada com sucesso", {status:'success',timeout: 2500});

					}else{
					//abre a tela de preload
					modal.hide();
					UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500});
					}
					},
			error:function (){
					UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
					modal.hide();
					}
			});
	});
});


//nega a autorização
jQuery(function() {

	jQuery(".uk-btn-negative").click(function(event) {

		var data_id   = jQuery(this).attr("uk-data-id");
		var data_not_id   = jQuery(this).attr("uk-data-not-id");

		// mensagen de carregamento
		jQuery("#msg_loading").html(" Aguarde ... ");

		//abre a tela de preload
		modal.show();

		//desabilita o envento padrao do formulario
		event.preventDefault();

		 	jQuery.ajax({
		 	async: true,
		 	url: "assets/medautorizacao/Controller_autorizacoes.php",
		 	type: "post",
		 	data:"action=4&autId="+data_id+"&notId="+data_not_id+"",
		 	success: function(resultado) {
		 		if(jQuery.isNumeric(resultado)){
					
					modal.hide();
              		UIkit.notify("Autorização negada.", {status:'warning',timeout: 2500});

					}else{
					//abre a tela de preload
					modal.hide();
					UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500});
					}
					},
			error:function (){
					UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
					modal.hide();
					}
			});
	});
});
</script>