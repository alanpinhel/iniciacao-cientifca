<?php
class TipoFormList extends TPage
{
    protected $form;
    protected $datagrid;
    private   $pageNavigation;
    private   $loaded;
  
    /**
     * Constrói página
     */
    public function __construct()
    {
        parent::__construct();
    
        // verifica se está logado
        if (TSession::getValue('logged') !== TRUE)
        {
          throw new Exception(_t('Não logado'));
        }
      
        // verifica se a categoria de login, tem permissão
        TTransaction::open('dadivar');
        if (Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'ADMINISTRADOR')
        {
          throw new Exception(_t('Permissão negada'));
        }
        TTransaction::close();
        
        // cria formulário
        $this->form = new TQuickForm('form_tipo');
        $this->form->class = 'tform';
        $this->form->setFormTitle(_t('Form de tipo'));
        
        // cria campos para formulário
        $id    = new THidden('id');
        $secao = new TDBCombo('secao_id', 'dadivar', 'Secao', 'id', 'nome');
        $pai   = new TDBCombo('pai_id', 'dadivar', 'Tipo', 'pai_id', 'pai_nome');
        $nome  = new TEntry('nome');
        
        // define campos obrigatórios
        $secao->addValidation(_t('Seção'), new TRequiredValidator);
        $pai->addValidation(_t('Pai'), new TRequiredValidator);
        $nome->addValidation(_t('Nome'), new TRequiredValidator);
        
        // inicia pai como não editável
        TCombo::disableField('form_tipo', 'pai_id');
        
        // adiciona campos ao formulário
        $this->form->addQuickField('', $id, 0);
        $this->form->addQuickField(_t('Seção'), $secao, 180);
        $this->form->addQuickField(_t('Pai'), $pai, 180);
        $this->form->addQuickField(_t('Nome'), $nome, 180);
        
        // coloca ação após seleção da seção
        $secao->setChangeAction(new TAction(array($this, 'onSecaoChange')));
        
        // cria botões de ação
        $this->form->addQuickAction(_t('Salvar'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('Novo'),  new TAction(array($this, 'onClear')), 'fa:plus-circle');
        
        // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        // adiciona colunas no datagrid
        $this->datagrid->addQuickColumn(_t('Seção'), 'secao_nome', 'left', 80);
        $this->datagrid->addQuickColumn(_t('Pai'), 'pai_nome', 'left', 80);
        $this->datagrid->addQuickColumn(_t('Tipo'), 'nome', 'left', 170);
        
        // adiciona ações no datagrid
        $this->datagrid->addQuickAction(_t('Editar'),  new TDataGridAction(array($this, 'onEdit')), 'id', 'fa:pencil-square-o');
        $this->datagrid->addQuickAction(_t('Deletar'), new TDataGridAction(array($this, 'onDelete')), 'id', 'fa:times');
        
        // cria o modelo datagrid 
        $this->datagrid->createModel();
        
        // cria página de navegação
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // incorpora elementos em estrutura
        $vbox = new TVBox;
        $vbox->add($this->form);
        $vbox->add($this->datagrid);
        $vbox->add($this->pageNavigation);
        
        // adiciona estrutura à página
        parent::add($vbox);
    }
  
    /**
     * Ação executada na seleção de seção
     * @param $param parâmetros de ação
     */
    static function onSecaoChange($param)
    {
        TTransaction::open('dadivar');
        
        $repos = new TRepository('Tipo');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pai_id', '=', 0));
        $criteria->add(new TFilter('secao_id', '=', $param['secao_id']));
        $pais = $repos->load($criteria);
        
        TTransaction::close();
        
        $combo_itens = array();
    
        // adiciona primeiro item
        $combo_itens[0] = _t('ORFAO');
        
        foreach ($pais as $pai)
        {
          $combo_itens[$pai->id] = $pai->nome;
        }
        
        TCombo::reload('form_tipo', 'pai_id', $combo_itens);
        TCombo::enableField('form_tipo', 'pai_id');
    }
  
    /**
     * Carrega o datagrid com os tipos do banco de dados
     */
    function onReload($param = NULL)
    {
        try
        {
            // recupera tipos do banco do dados
            TTransaction::open('dadivar');
            
            $repos = new TRepository('Tipo');
      
            // limite de registros por página
            $limit = 10;
            
            $criteria = new TCriteria;
            $criteria->setProperty('order', 'secao_id, pai_id, id');
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            $tipos = $repos->load($criteria);
      
            // adiciona tipos recuperados ao datagrid
            $this->datagrid->clear();
            if ($tipos)
            {
                foreach ($tipos as $tipo)
                {
                    $this->datagrid->addItem($tipo);
                }
            }
      
            // reseta filtros para realizar contagem
            $criteria->resetProperties();
            $count = $repos->count($criteria);
      
            // define propriedade no pageNavigation
            $this->pageNavigation->setCount($count);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($limit);
      
            TTransaction::close();
      
            // marca flag carregado
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
  
    /**
     * Executa quando usuário clicar em salvar
     */
    function onSave()
    {
        try
        {
            $this->form->validate();
      
            TTransaction::open('dadivar');
      
            $tipo = $this->form->getData('Tipo');
            $tipo->store();
      
            TTransaction::close();
      
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
     * Executa quando usuário clicar em editar
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $id = $param['key'];
        
                TTransaction::open('dadivar');
                $tipo = new Tipo($id);
                $this->form->setData($tipo);
                TTransaction::close();
                $this->onReload();
                TCombo::enableField('form_tipo', 'pai_id');
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exceptionc $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
  
    /**
     * Executa quando usuário clicar em deletar
     * Pergunta se realmente deseja excluir registro
     */
    function onDelete($param)
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
     * Deleta o registro
     */
    function Delete($param)
    {
        try
        {
            $key = $param['key'];
      
            TTransaction::open('dadivar');
            
            $tipo = new Tipo($key);
            $tipo->delete();
      
            TTransaction::close();
      
            $this->onReload();
      
            new TMessage('info', _t("Registro excluído"));
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
  
    /**
     * Limpa formulário
     */
    public function onClear()
    {
        $this->form->clear();
    }
  
    /**
     * Executado na construção da página
     */
    function show()
    {
        if (!$this->loaded)
        {
            $this->onReload(func_get_arg(0));   
        }
        parent::show();
    }
}
?>