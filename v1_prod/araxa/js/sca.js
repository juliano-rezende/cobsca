/*MENU RAPIDO*/
jQuery('#Sup_Btn_001').click(function(){
	LoadContent('assets/dashboard/dash.php','content');
	});// inicio

jQuery('#Sup_Btn_002').click(function(){
	New_window('search','980','520','Pesquisa Associado','assets/associado/Frm_pesquisa_associado.php',true,false,'Carregando...');
	});// pesquisa associado
jQuery('#Sup_Btn_003').click(function(){
	New_window('list','700','400','Notificações','assets/notificacao/Grid_notificacoes.php',true,false,'Carregando...');
	});// notificações
jQuery('#Sup_Btn_004').click(function(){
	UIkit.offcanvas.hide();
	New_window('gears','675','260','Configurações','assets/usuario/Frm_recovery.php',true,false,'Carregando...');
	});// configurações do usuario
jQuery('#Sup_Btn_005').click(function(){
	LoadContent('assets/medautorizacao/autorizacoes.php','content');
	});// autorizaçoes


/* BOTÕES DO MENU CADASTRO*/
jQuery('#Cad_Btn_001').click(function(){LoadContent('assets/associado/Frm_associado.php','content');});		/*janelas de cadastro area de atendimento*/
jQuery('#Cad_Btn_002').click(function(){LoadContent('assets/cliente/Frm_cliente.php','content');});			/* janelas de cadastro clientes*/
jQuery('#Cad_Btn_003').click(function(){LoadContent('assets/empresa/Frm_empresa.php','content');});			/* janelas de cadastro de empresas*/
jQuery('#Cad_Btn_004').click(function(){LoadContent('endereco/Frm_endereco.php','content');});				/* janelas de cadastro endereços manuais*/
jQuery('#Cad_Btn_005').click(function(){LoadContent('assets/fornecedor/Frm_fornecedor.php','content');});	/* janelas de cadastro fornecedores*/
jQuery('#Cad_Btn_006').click(function(){LoadContent('assets/usuario/Frm_usuario.php','content');});			/* janelas de cadastro usuario*/
jQuery('#Cad_Btn_007').click(function(){LoadContent('assets/vendedor/Frm_vendedor.php','content');});		/* janelas de cadastro vendedores*/
jQuery('#Cad_Btn_008').click(function(){LoadContent('assets/convenio/Frm_convenio.php','content');});		/* janelas de cadastro vendedores*/
jQuery('#Cad_Btn_009').click(function(){LoadContent('assets/parceiros/Frm_parceiro.php','content');});		/* janelas de cadastro vendedores*/

/* BOTÕES DO MENU CONFIGURAÇÕES*/
jQuery('#Conf_Btn_001').click(function(){});																/* COnfigurações*/

/* BOTÕES DO MENU FATURAMENTO*/
jQuery('#Fat_Btn_001').click(function(){UIkit.offcanvas.hide();New_window('list','600','200','Faturamento Geral','assets/faturamento/Frm_fat_geral.php',true,false,'Carregando...');});	/* faturamento geral*/
jQuery('#Fat_Btn_002').click(function(){LoadContent('assets/cobranca/remessa/remessa.php','content');});		/* Remessas bancarias*/
jQuery('#Fat_Btn_003').click(function(){LoadContent('assets/cobranca/retorno/retorno.php','content');});		/* Retornos bancarios*/
jQuery('#Fat_Btn_004').click(function(){UIkit.offcanvas.hide();New_window('list','500','200','Faturamento PJ','assets/faturamento/Frm_fat_convenios.php',true,false,'Carregando...');});/* Faturamento PJ*/
jQuery('#Fat_Btn_005').click(function(){
	UIkit.offcanvas.hide();
	New_window('search','980','520','Baixa de Titulos','assets/cobranca/baixa_titulo/Frm_pesquisa_titulos.php',true,false,'Carregando...');
});/* Baixa titulo bancario*/

/* BOTÕES DO MENU RELATORIOS*/
jQuery('#Rel_Btn_001').click(function(){LoadContent('assets/relatorios/associados/associados.php','content');});
jQuery('#Rel_Btn_002').click(function(){LoadContent('assets/relatorios/titulos/titulos.php','content');});
jQuery('#Rel_Btn_003').click(function(){LoadContent('assets/relatorios/caixa/livro_caixa.php','content');});
jQuery('#Rel_Btn_004').click(function(){LoadContent('assets/relatorios/faturamentos/faturamentos.php','content');});
jQuery('#Rel_Btn_005').click(function(){LoadContent('assets/relatorios/inadimplentes/inadimplentes.php','content');});
jQuery('#Rel_Btn_006').click(function(){LoadContent('assets/relatorios/convenios/convenios.php','content');});
jQuery('#Rel_Btn_007').click(function(){LoadContent('assets/relatorios/vendedores/vendedores.php','content');});
jQuery('#Rel_Btn_008').click(function(){LoadContent('assets/relatorios/recebimentos/recebimentos.php','content');});
jQuery('#Rel_Btn_009').click(function(){LoadContent('assets/relatorios/fatconvenios/fatConvenios.php','content');});
jQuery('#Rel_Btn_010').click(function(){LoadContent('assets/relatorios/recebimentos/recebimentosMes.php','content');});
jQuery('#Rel_Btn_011').click(function(){LoadContent('assets/relatorios/recebimentos/recebimentosPrevisao.php','content');});


/*BOTÕES DO MENU RELATORIOS*/
jQuery('#Tab_Btn_001').click(function(){});
jQuery('#Tab_Btn_002').click(function(){LoadContent('endereco/endereco.php','content'); });					/*tabela endereços*/
jQuery('#Tab_Btn_003').click(function(){});
jQuery('#Tab_Btn_004').click(function(){});
jQuery('#Tab_Btn_005').click(function(){});


/* BOTÕES DO MENU FINANCEIRO*/
jQuery('#Fin_Btn_001').click(function(){LoadContent('assets/financeiro/caixa/Veiw_lancamentos.php','content');});			/*lançamentos*/
jQuery('#Fin_Btn_002').click(function(){LoadContent('assets/financeiro/cpagar/Veiw_c_pagar.php','content');});				/*janelas de contas a pagar*/
jQuery('#Fin_Btn_003').click(function(){LoadContent('assets/financeiro/creceber/Veiw_c_receber.php','content');});			/* janelas de contas a receber*/


/* BOTÕES DO MENU GERENCIAL*/
jQuery('#Ger_Btn_005').click(function(){UIkit.offcanvas.hide();New_window('money','600','320','Reajuste de Planos','assets/planos/Frm_reajusta_planos.php',true,false,'Carregando...');});			/*lançamentos*/


/* utilitarios */
jQuery('#Util_Btn_001').click(function(){
	var ch_ctt= jQuery('#ch_ctt').val();
    UIkit.offcanvas.hide();
    New_window('file-text-o','900','500','Contrato','assets/associado/contratos/'+ch_ctt+'/Contrato_adesao_br.php',true,false,'Carregando...');
    });

jQuery('#Util_Btn_002').click(function(){
UIkit.offcanvas.hide();
    New_window('file-text-o','900','600','Rede Credenciada','assets/redecredenciada/rede1.php',true,false,'Carregando...');
    
    });

jQuery('#Util_Btn_003').click(function(){
UIkit.offcanvas.hide();
    New_window('file-text-o','900','600','Rede Credenciada','assets/redecredenciada/rede2.php',true,false,'Carregando...');
    
    });
jQuery('#Util_Btn_004').click(function(){
UIkit.offcanvas.hide();
    New_window('file-text-o','900','600','Rede Credenciada','assets/redecredenciada/rede3.php',true,false,'Carregando...');
    
    });
/* botão
/* botão de sair do sistema*/
jQuery('#Btn_logout').click(function(){location.href="logout.php";});

/**************************************************************** DEMAIS PARAMETROS DA TELA INICIAL ***********************************************************************/
/* carrega a tela principal*/
//jQuery(document).ready(function(){LoadContent('assets/dashboard/dash.php','content');});

/*indica qual é a janela que deve se modal */
var modal = UIkit.modal(".uk-modal-loading");

/* Show an off-canvas matching the passed CSS selector*/
UIkit.offcanvas.show('#nav_left_offcanvas');
/* Hide any active offcanvas. Set force to true, if you don't want any animation.*/
UIkit.offcanvas.hide([force = true]);

/* funções de impressão e geração de pdf*/

function Print(tag){

	jQuery( "#"+tag+"" ).print();
}

