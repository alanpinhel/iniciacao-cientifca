<?php
/**
 * Classe para exibição de conversas
 */
class ConversaList extends TPage
{
    private $form;
    private $datagrid;
    private $loaded;
    
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
        
        // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        
        // adiciona colunas
        $this->datagrid->addQuickColumn('ID', 'id', 'left', 50);
        $this->datagrid->addQuickColumn(_t('Data de criação'), 'data_criacao', 'left', 120);
        $this->datagrid->addQuickColumn(_t('Hora de criação'), 'hora_criacao', 'left', 120);
        
        // cria ações para datagrid
        $act_integrante = new TDataGridAction(array($this, 'onShowIntegrantes'));
        $act_integrante->setLabel(_t('Integrantes'));
        $act_integrante->setImage('fa:eye');
        $act_integrante->setField('id');
        
        $act_conversa_form = new TDataGridAction(array('ConversaForm', 'onGerenciar'));
        $act_conversa_form->setLabel(_t('Gerenciar'));
        $act_conversa_form->setImage('fa:external-link-square');
        $act_conversa_form->setField('id');
        
        $act_delete = new TDataGridAction(array($this, 'onDelete'));
        $act_delete->setLabel(_t('Deletar'));
        $act_delete->setImage('fa:times');
        $act_delete->setField('id');
        
        // cria grupo de ações
        $act_group = new TDataGridActionGroup(_t('Ações'), 'bs:th');
        $act_group->addAction($act_integrante);
        $act_group->addAction($act_conversa_form);
        $act_group->addAction($act_delete);
        
        // adicona ações ao datagrid
        $this->datagrid->addActionGroup($act_group);
    
        // cria modelo datagrid
        $this->datagrid->createModel();
        
        // cria formulário
        $this->form = new TQuickForm;
        $this->form->class = 'tform';
        $this->form->addQuickAction(_t('Novo'), new TAction(array($this, 'onSave')), 'fa:plus-circle');
        
        // incorpora elementos à estrutura
        $vbox = new TVBox;
        $vbox->add($this->datagrid);
        $vbox->add($this->form);

        // adiciona estrutura à página
        parent::add($vbox);
    }
    
    /**
     * Recarrega datagrid
     */
    function onReload()
    {
        try
        {
            // abre conexão
            TTransaction::open('dadivar');
        
            // instancia repositório para integrante
            $repos = new TRepository('Integrante');
            
            // define filtro
            $criteria = new TCriteria;
            $criteria->add(new TFilter('usuario_id', '=', Usuario::newFromLogin(TSession::getValue('celular'))->id));
            
            // recupera integrantes, cujo usuário seja igual ao logado
            $integrantes = $repos->load($criteria);
      
            // adiciona tipos recuperados ao datagrid
            $this->datagrid->clear();
            if ($integrantes)
            {
                foreach ($integrantes as $integrante)
                {
                    // instancia conversa e adiciona no datagrid
                    $conversa = new Conversa($integrante->conversa_id);
                    $this->datagrid->addItem($conversa);
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
     * Mostra integrantes
     */
    public function onShowIntegrantes($param)
    {
        // pega posição da linha
        $pos = $this->datagrid->getRowIndex('id', $param['key']);
        
        // pega linha pela posição
        $current_row = $this->datagrid->getRow($pos);
        $current_row->style = "background-color: #8D8BC8; color:white; text-shadow:none";
        
        try
        {
            // abre conexão
            TTransaction::open('dadivar');
        
            // instancia repositório p/ integrante
            $repos = new TRepository('Integrante');
            
            // define filtro
            $criteria = new TCriteria;
            $criteria->add(new TFilter('conversa_id', '=', $param['id']));
            
            // recupera integrantes, que fazem parte da conversa
            $integrantes = $repos->load($criteria);
            
            if ($integrantes)
            {
                foreach ($integrantes as $integrante)
                {
                    // instancia usuário
                    $usuario = new Usuario($integrante->usuario_id);
                    
                    // cria nova linha
                    $row = new TTableRow;
                    $row->style = "background-color: #E0DEF8";
                    $row->addCell('');
                    $cell = $row->addCell($usuario->nome.' '.$usuario->sobrenome);
                    $cell->colspan = 4;
                    
                    // insere nova linha
                    $this->datagrid->insert($pos +1, $row);
                }
            }
            
            // fecha conexão
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Insere novo registro
     */
    public function onSave()
    {
        try
        {
            // abre conexão
            TTransaction::open('dadivar');
            
            // instancia nova conversa
            $conversa = new Conversa;
            $conversa->data_criacao = date('Y-m-d');
            $conversa->hora_criacao = date('H:i:s');
            
            // adiciona usuário criador como integrante
            $conversa->addUsuario(Usuario::newFromLogin(TSession::getValue('celular')));
            
            // salva conversa e integrantes
            $conversa->store();
            
            // fecha conexão
            TTransaction::close();
            
            // mensagem registro salvo
            new TMessage('info', _t('Registro salvo'));
            
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
     * Questiona se deseja deletar
     */
    public function onDelete($param)
    {
        try
        {
            // define a ação do delete
            $action = new TAction(array($this, 'Delete'));
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
    function Delete($param)
    {
        try
        {
            // resgata id da conversa
            $id = $param['id'];
      
            // abre conexão
            TTransaction::open('dadivar');
            
            // instancia e deleta conversa junto de sua composição
            $conversa = new Conversa($id);
            $conversa->delete();
            
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
     * Executado na construção da página
     */
    function show()
    {
        if (!$this->loaded)
            $this->onReload(func_get_arg(0));
    
        parent::show();
    }
}
?>