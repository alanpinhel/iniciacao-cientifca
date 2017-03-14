<?php
class UsuarioFormDefAdm extends TStandardForm
{
    protected $form;

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
    
        // cria formulário
        $this->form = new TQuickForm('form_usuario');
        $this->form->class = 'tform';
        $this->form->style = 'width: 500px';
        
        // define o título
        $this->form->setFormTitle(_t('Form de usuário'));
        
        // cria os campos para formulário
        $id        = new THidden('id');
        $nome      = new TEntry('nome');
        $sobrenome = new TEntry('sobrenome');
        $categoria = new TDBCombo('categoria_id', 'dadivar', 'Categoria', 'id', 'nome');
        
        // define campos não editáveis
        $nome->setEditable(FALSE);
        $sobrenome->setEditable(FALSE);
            
        // adiciona campos ao formulário
        $this->form->addQuickField('', $id,  0);
        $this->form->addQuickField(_t('Nome'), $nome,  100);
        $this->form->addQuickField(_t('Sobrenome'), $sobrenome,  100);
        $this->form->addQuickField(_t('Categoria'), $categoria,  150);
    
        // define as ações do formulário (botões)
        $this->form->addQuickAction(_t('Salvar'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('Listar'),  new TAction(array('UsuarioListDefAdm', 'onReload')), 'fa:list');
        
        // cria estrutura da página
        $vbox = new TVBox;
        $vbox->add($this->form);
    
        // adiciona estrutura na página
        parent::add($vbox);
    }
}
?>