<?php
class UsuarioListDefAdm extends TStandardList
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;

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
        if (Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'ADMINISTRADOR')
        {
          throw new Exception(_t('Permissão negada'));
        }
        TTransaction::close();
        
        // define banco de dados
        parent::setDatabase('dadivar');
        
        // define classe de manipulação
        parent::setActiveRecord('Usuario');
        
        // define campo para pesquisa
        parent::addFilterField('nome', 'LIKE');
        
        // define ordenação dos registros
        parent::setDefaultOrder('id', 'ASC');
        
        // cria o formulário
        $this->form = new TQuickForm('form_search_usuario');
        $this->form->setFormTitle(_t('Lista de usuários'));
        $this->form->class = 'tform';
    
        // cria campo e botão
        $nome = new TEntry('nome');
        $this->form->addQuickField(_t('Nome'), $nome, 400);
        $this->form->addQuickAction(_t('Buscar'), new TAction(array($this, 'onSearch')), 'fa:search');
    
        // mantém o formulário preenchido
        $this->form->setData(TSession::getValue('usuario_filter_data'));
        
        // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = "width: 100%";
        $this->datagrid->setHeight(230);
    
        // cria colunas do datagrid
        $this->datagrid->addQuickColumn(_t('Nome'), 'nome', 'left', 150, new TAction(array($this, 'onReload')), array('order', 'nome'));
        $this->datagrid->addQuickColumn(_t('Sobrenome'), 'sobrenome', 'left', 150, new TAction(array($this, 'onReload')), array('order', 'sobrenome'));
        $this->datagrid->addQuickColumn(_t('Categoria'), 'categoria_nome', 'left', 120);
        $this->datagrid->addQuickColumn(_t('Celular'), 'celular', 'left', 100);
        $this->datagrid->addQuickColumn(_t('Ativo'), 'ativo', 'left', 10);
    
        // cria ação de edição
        $this->datagrid->addQuickAction(_t('Editar'), new TDataGridAction(array('UsuarioFormDefAdm', 'onEdit')), 'id', 'fa:pencil-square-o');
    
        // cria modelo do datagrid
        $this->datagrid->createModel();
        
        // cria páginação
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // cria estrutura da página
        $vbox = new TVBox;
        $vbox->add($this->form);
        $vbox->add($this->datagrid);
        $vbox->add($this->pageNavigation);
            
        // adiciona estrutura na página
        parent::add($vbox);
    }
}
?>