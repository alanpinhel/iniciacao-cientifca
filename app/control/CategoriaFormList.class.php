<?php
class CategoriaFormList extends TStandardFormList
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;
  
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
    
        // define banco de dados
        parent::setDatabase('dadivar');
    
        // define classe
        parent::setActiveRecord('Categoria');
    
        // define ordernação padrão
        parent::setDefaultOrder('nome', 'asc');
    
        // cria o formulário 
        $this->form = new TQuickForm('form_categoria');
        $this->form->class = 'tform';
        $this->form->setFormTitle(_t('Form de categoria'));
    
        // cria campos para formulário
        $id   = new THidden('id');
        $nome = new TEntry('nome');
    
        // define campos obrigatórios
        $nome->addValidation(_t('Nome'), new TRequiredValidator);
    
        // adiciona campo ao formulário
        $this->form->addQuickField('', $id, 0);
        $this->form->addQuickField(_t('Nome'), $nome, 200);
    
        // cria botões de ação
        $this->form->addQuickAction(_t('Salvar'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('Novo'),  new TAction(array($this, 'onClear')), 'fa:plus-circle');
    
        // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
    
        // adiciona coluna da datagrid
        $this->datagrid->addQuickColumn(_t('Nome'), 'nome','left',  400, new TAction(array($this, 'onReload')), array('order', 'nome'));
        
        // adicona ações no datagrid
        $this->datagrid->addQuickAction(_t('Editar'),  new TDataGridAction(array($this, 'onEdit')),   'id', 'fa:pencil-square-o');
        $this->datagrid->addQuickAction(_t('Deletar'), new TDataGridAction(array($this, 'onDelete')), 'id', 'fa:times');
        
        // cria modelo de datagrid
        $this->datagrid->createModel();
        
        // cria estrutura da página
        $vbox = new TVBox;
        $vbox->add($this->form);
        $vbox->add($this->datagrid);
    
        // adiciona datagrid e form na página
        parent::add($vbox);
    }
}
?>