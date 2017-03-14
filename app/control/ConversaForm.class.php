<?php
/**
 * Classe para cadastro de conversas, integrantes e mensagens
 */
class ConversaForm extends TPage
{
    private $form;
    private $datagrid;
    private $loaded;
    private $html;
    private $formMsg;
   
    /**
     * Constrói página
     */
    function __construct()
    {
        parent::__construct();
        
        // verifica se está logado
        if (TSession::getValue('logged') !== TRUE)
        {
            throw new Exception(_t('Não logado'));
        }
        // verifica se a categoria de login, tem permissão
        TTransaction::open('dadivar');
        if ((Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'ADMINISTRADOR') &&
            (Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'USUARIO'))
        {
            throw new Exception(_t('Permissão negada'));
        }
        TTransaction::close();
        
        // cria formulário
        $this->form = new TQuickForm('form_integrante');
        $this->form->setFormTitle('Form de integrantes');
        $this->form->class = 'tform';
        
        // cria campo
        $celular = new TEntry('celular');
        
        // acrescenta mascára ao campo
        $celular->setMask('(00)00000-0000');
        
        // adiciona campo ao formulário
        $this->form->addQuickField(_t('Celular'), $celular, 150);
        
        // cria botão de ação
        $this->form->addQuickAction(_t('Voltar'), new TAction(array('ConversaList', 'onReload')), 'fa:backward');
        $this->form->addQuickAction(_t('Adicionar'), new TAction(array($this, 'onAddIntegrante')), 'fa:plus-circle');
        
        // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        
        // adiciona colunas
        $this->datagrid->addQuickColumn(_t('Celular'), 'celular', 'left', 100);
        $this->datagrid->addQuickColumn(_t('Nome'), 'nome', 'left', 120);
        $this->datagrid->addQuickColumn(_t('Sobrenome'), 'sobrenome', 'left', 120);
        
        // cria ação de deletar
        $this->datagrid->addQuickAction(_t('Deletar'), new TDataGridAction(array($this, 'onDeleteIntegrante')), 'id', 'fa:times');
        
        // cria modelo datagrid
        $this->datagrid->createModel();
        
        // exibe estrutura para mensagens
        TPage::include_css('app/resources/styles.css');
        $this->html = new THtmlRenderer('app/resources/mensagens.html');
        $this->html->enableSection('mensagens');
        
        // cria formulário para envio de mensagens
        $this->formMsg = new TQuickForm('form_msg');
        $this->formMsg->class = 'tform';
        
        // cria campo
        $texto = new TEntry('texto');
        
        // insere dica de campo
        $texto->placeholder = _t('Mensagem');
        
        // adiciona campo ao formulário
        $this->formMsg->addQuickField('', $texto, 370);
        
        // adiciona botão enviar
        $this->formMsg->addQuickAction(_t('Enviar'), new TAction(array($this, 'onAddMensagem')), '');
        
        // coloca elementos na estrutura
        $vbox = new TVBox;
        $vbox->add($this->form);
        $vbox->add($this->datagrid);
        $vbox->add($this->html);
        $vbox->add($this->formMsg);
        
        // adiciona estrutura a página
        parent::add($vbox);
    }
   
    /**
     * Limpa formulário
     */
    function onGerenciar($param)
    {
        // limpa formulário
        $this->form->clear();
        
        // armazena id da conversa em sessão
        TSession::setValue('conversa_id', $param['id']);
        
        // recarrega página
        $this->onReload();
    }
   
    /**
     * Adiciona usuário a lista de integrantes da conversa
     */
    function onAddIntegrante($param)
    {
        try
        {
            // abre conexão
            TTransaction::open('dadivar');

            // instancia conversa selecionada no ConversaList
            $conversa = new Conversa(TSession::getValue('conversa_id'));
            
            // retira caracteres especiais
            $celular = preg_replace("/[^0-9]/", "", $param['celular']);
            
            // recupera id do usuário atráves do celular
            $usuario_id = Usuario::newFromLogin($celular)->id;
            
            foreach ($conversa->getUsuarios() as $usuario)
            {
                if ($usuario->id == $usuario_id)
                {
                    new TMessage('info', _t('Usuário já pertence à conversa'));
                    return;
                }
            }
            
            if (empty($usuario_id))
            {
                new TMessage('error', _t('Usuário não encontrado'));
                return;
            }
            
            // instancia usuário
            $usuario = new Usuario($usuario_id);
            
            // adiciona usuário a conversa
            $conversa->addUsuario($usuario);
            
            // salva conversa e integrantes
            $conversa->store();
            
            // fecha conexão
            TTransaction::close();
            
            // recarrega página
            $this->onReload();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Recarrega página
     */
    function onReload()
    {
        try
        {
            // abre conexão
            TTransaction::open('dadivar');
            
            // instancia repositório p/ integrante
            $repos = new TRepository('Integrante');
            
            // define filtro
            $criteria = new TCriteria;
            $criteria->add(new TFilter('conversa_id', '=', TSession::getValue('conversa_id')));
            
            // recupera integrantes, que fazem parte da conversa
            $integrantes = $repos->load($criteria);
            
            if ($integrantes)
            {
                // limpa datagrid
                $this->datagrid->clear();
                
                foreach ($integrantes as $integrante)
                {
                    // instancia usuário
                    $usuario = new Usuario($integrante->usuario_id);
                    
                    // insere nova linha
                    $this->datagrid->addItem($usuario);
                }
            }
            
            // fecha conexão
            TTransaction::close();
            
            // marca flag carregado
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Executado na construção da página
     */
    function show()
    {
        if (!$this->loaded)
            $this->onReload(func_get_arg(0));
    
        parent::show();
    }
    
    /**
     * Questiona se deseja deletar
     */
     public function onDeleteIntegrante($param)
    {
        try
        {
            // define a ação do delete
            $action = new TAction(array($this, 'DeleteIntegrante'));
            $action->setParameters($param);
        
            // mostra diálogo para usuário
            new TQuestion(_t('Deseja realmente excluir ?'), $action);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Deleta registro
     */
    function DeleteIntegrante($param)
    {
        try
        {
            // resgata id do integrante
            $usuario_id = $param['id'];
      
            // abre conexão
            TTransaction::open('dadivar');
            
            // instancia repositório p/ integrante
            $repos = new TRepository('Integrante');
            
            // define filtro
            $criteria = new TCriteria;
            $criteria->add(new TFilter('conversa_id', '=', TSession::getValue('conversa_id')));
            $criteria->add(new TFilter('usuario_id', '=', $usuario_id));
            
            // recupera integrante
            $integrantes = $repos->load($criteria);
            
            // deleta integrante
            $integrantes[0]->delete();
            
            // fecha conexão
            TTransaction::close();
      
            // mensagem registro excluído
            new TMessage('info', _t("Registro excluído"));
            
            // recarrega datagrid
            $this->onReload();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Adiciona mensagem ao banco de dados
     */
    public function onAddMensagem()
    {
        try
        {
            // abre conexão
            TTransaction::open('dadivar');

            // instancia conversa selecionada no ConversaList
            $conversa = new Conversa(TSession::getValue('conversa_id'));
            
            // recupera texto digitado no formulário
            $dados = $this->formMsg->getData('StdClass');
            $texto = $dados->texto;
            
            // monta mensagem a ser adicionada
            $mensagem = new Mensagem;
            $mensagem->data_envio = date('Y-m-d');
            $mensagem->hora_envio = date('H:i:s');
            $mensagem->texto = $texto;
            $mensagem->remetente_id = Usuario::newFromLogin(TSession::getValue('celular'))->id;
            $conversa->addMensagem($mensagem);
            
            // salva conversa e mensagem
            $conversa->store();
            
            // fecha conexão
            TTransaction::close();
            
            // recarrega página
            $this->onReload();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
?>