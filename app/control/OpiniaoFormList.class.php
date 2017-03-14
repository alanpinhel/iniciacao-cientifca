<?php
class OpiniaoFormList extends TPage
{
    protected $form;
    protected $datagrid;
    private $loaded;
 
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
        if ((Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'ADMINISTRADOR') &&
            (Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'USUARIO'))
        {
            throw new Exception(_t('Permissão negada'));
        }
        TTransaction::close();
  
        // cria o formulário 
        $this->form = new TQuickForm('form_opniao');
        $this->form->class = 'tform';
        $this->form->setFormTitle(_t('Form de opinião'));
    
        // cria campos para formulário
        $id          = new THidden('id');
        $data_envio  = new TEntry('data_envio');
        $hora_envio  = new TEntry('hora_envio');
        $texto       = new TText('texto');
        $visualizada = new THidden('visualizada');
        
        // cria campos obrigatorio
        $texto->addValidation(_t('Texto'), new TRequiredValidator);
        
        // adiciona campos ao formulário
        $this->form->addQuickField('', $id, 0);
        $this->form->addQuickField(_t('Texto'), $texto, 250, 75);
        
        // cria botões de ação
        $this->form->addQuickAction(_t('Salvar'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
    
        // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        // adiciona colunas no datagrid
        $this->datagrid->addQuickColumn(_t('Data de envio'), 'data_envio', 'left', 100, new TAction(array($this, 'onReload')), array('order', 'data_envio'));
        $this->datagrid->addQuickColumn(_t('Hora de envio'), 'hora_envio', 'left', 100, new TAction(array($this, 'onReload')), array('order', 'hora_envio'));
        $this->datagrid->addQuickColumn(_t('Visualizada'), 'visualizada', 'left', 100, new TAction(array($this, 'onReload')), array('order', 'visualizada'));
        
        // cria o modelo datagrid
        $this->datagrid->createModel();
        
         // cria estrutura da página
        $vbox = new TVBox;
        $vbox->add($this->form);
        $vbox->add($this->datagrid);
        
        // adiciona datagrid e form na página
        parent::add($vbox);
    }
  
    /**
     * Carrega o datagrid com os tipos do banco de dados
     */
    function onReload($param = NULL)
    {
        try
        {
            // recupera opiniões do banco do dados
            TTransaction::open('dadivar');
            $usuario_id = Usuario::newFromLogin(TSession::getValue('celular'))->id;
            $repos = new TRepository('Opiniao');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('usuario_id', '=', $usuario_id));
            $opinioes = $repos->load($criteria);
      
            // adiciona opiniões recuperadas ao datagrid
            $this->datagrid->clear();
            if ($opinioes)
            {
                foreach ($opinioes as $opiniao)
                {
                    // substitui 0 ou 1 p/ Não ou Sim
                    $opiniao->visualizada = $opiniao->visualizada == 0 ? _t('Não') : _t('Sim');
                    $this->datagrid->addItem($opiniao);
                }
            }
            
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
            
            $usuario_id = Usuario::newFromLogin(TSession::getValue('celular'))->id;
            $opiniao = $this->form->getData('Opiniao');
            $opiniao->usuario_id = $usuario_id;
            $opiniao->data_envio = date('Y-m-d');
            $opiniao->hora_envio = date('H:i:s');
            $opiniao->store();
            
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