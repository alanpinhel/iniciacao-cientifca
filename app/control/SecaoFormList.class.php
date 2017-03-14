<?php
class SecaoFormList extends TStandardFormList
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;
 
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
    
        parent::setDatabase('dadivar');// define banco de dados
        parent::setActiveRecord('Secao');// define categoria
        parent::setDefaultOrder('nome', 'asc');// define ordernação padrão
        
        // cria o formulário 
        $this->form = new TQuickForm('form_secao');
        $this->form->class = 'tform';
        $this->form->setFormTitle(_t('Form de seção'));
    
        // cria campos para formulário
        $id   = new THidden('id');
        $nome = new TEntry('nome');
        
        // define campos obrigatórios
        $nome->addValidation(_t('Nome'), new TRequiredValidator);
    
        // adiciona campos ao formulário
        $this->form->addQuickField('', $id, 0);
        $this->form->addQuickField(_t('Nome'), $nome, 150);
    
        // cria botões de ação
        $this->form->addQuickAction(_t('Salvar'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('Novo'),  new TAction(array($this, 'onClear')), 'fa:plus-circle');
        
         // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
    
        // adiciona coluna da datagrid
        $this->datagrid->addQuickColumn(_t('Nome'), 'nome','left',  150, new TAction(array($this, 'onReload')), array('order', 'nome'));
        
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