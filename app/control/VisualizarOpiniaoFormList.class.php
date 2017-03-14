<?php
class VisualizarOpiniaoFormList extends TPage
{
    protected $form;
    protected $datagrid;
    private   $loaded;
  
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
  
        // cria o formulário 
        $this->form = new TQuickForm('form_opiniao');
        $this->form->class = 'tform';
        $this->form->setFormTitle(_t('Form de opinião'));

        // cria campos para formulário
        $id          = new THidden('id');
        $data_envio  = new TEntry('data_envio');
        $hora_envio  = new TEntry('hora_envio');
        $texto       = new TText('texto');
        $visualizada = new TRadioGroup('visualizada');
    
        $item = array();
        $item[0] = _t('Não');
        $item[1] = _t('Sim');
        $visualizada->addItems($item);
        $visualizada->setLayout('horizontal');
        
        // define campos não editáveis
        $data_envio->setEditable(FALSE);
        $hora_envio->setEditable(FALSE);
        TText::disableField('form_opiniao', 'texto');
       
        // adiciona campos ao formulário
        $this->form->addQuickField('', $id, 0);
        $this->form->addQuickField(_t('Data de envio'), $data_envio, 100);
        $this->form->addQuickField(_t('Hora de envio'), $hora_envio, 100);
        $this->form->addQuickField(_t('Texto'), $texto, 350, 100);
        $this->form->addQuickField(_t('Visualizada'), $visualizada, 150);
        
        // cria botões de ação
        $this->form->addQuickAction(_t('Salvar'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        
        // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        // adiciona colunas no datagrid
        $this->datagrid->addQuickColumn(_t('Usuário'), 'usuario_nome', 'left', 100, new TAction(array($this, 'onReload')), array('order', 'usuario_nome'));
        $this->datagrid->addQuickColumn(_t('Data de envio'), 'data_envio', 'left', 150, new TAction(array($this, 'onReload')), array('order', 'data_envio'));
        $this->datagrid->addQuickColumn(_t('Hora de envio'), 'hora_envio', 'left', 150, new TAction(array($this, 'onReload')), array('order', 'hora_envio'));
        
        // adiciona ações no datagrid
        $this->datagrid->addQuickAction(_t('Editar'), new TDataGridAction(array($this, 'onEdit')), 'id', 'fa:pencil-square-o');
        
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
            $criteria = new TCriteria;
            $criteria->add(new TFilter('visualizada', '=', 0));
          
            $repos = new TRepository('Opiniao');
            $opinioes = $repos->load($criteria);
          
            // adiciona  recuperados ao datagrid
            $this->datagrid->clear();
            if ($opinioes)
            {
                foreach ($opinioes as $opiniao)
                {
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
            
            $opiniao = $this->form->getData('Opiniao');
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
     * Executa quando usuário clicar em editar
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];
        
                TTransaction::open('dadivar');
            
                $opiniao = new Opiniao($key);
                $this->form->setData($opiniao);
        
                TTransaction::close();
        
                $this->onReload();
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