<?php
class ObjetoAnaliseFormList extends TPage
{
    protected $form;
    protected $datagrid;
    private   $loaded;
 
    public function __construct($param)
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
    
        if (!empty($param['id']))
        {
            // cria tabela
            $table = new TTable;
      
            // cria div para imagens
            $galleria = new TElement('div');
            $galleria->id    = 'images';
            $galleria->style = "width:600px;height:460px";
      
            TTransaction::open('dadivar');
            $objeto = new Objeto($param['id']);
            $fotos  = $objeto->getFotos();
            TTransaction::close();
            foreach ($fotos as $foto)
            {
                $img  = new TElement('img');
                $img->src = $foto->arquivo;
                $galleria->add($img);
            }
            // adiciona div na tabela
            $table->addRow()->addCell($galleria);
      
            // cria script
            $script = new TElement('script');
            $script->type = 'text/javascript';
            $script->add("Galleria.loadTheme('app/lib/jquery/galleria/themes/classic/galleria.classic.min.js'); Galleria.run('#images');");
        
            // adiciona script na tabela
            $table->addRow()->addCell($script);
        }
    
        // cria o formulário 
        $this->form = new TQuickForm('form_objeto');
        $this->form->class = 'tform';
        $this->form->setFormTitle(_t('Form de objeto'));
    
        // cria campos para formulário
        $id        = new THidden('id');
        $titulo    = new TEntry('titulo');
        $tipo      = new TDBCombo('tipo_id', 'dadivar', 'Tipo', 'id', 'nome');
        $descricao = new TText('descricao');
        $status    = new TCombo('status');
    
        // itens para status
        $status_itens = array();
        $status_itens['A'] = _t('ANALISANDO');
        $status_itens['B'] = _t('BLOQUEADO');
        $status_itens['D'] = _t('DISPONIVEL');
        $status->addItems($status_itens);
    
        // define campos obrigatórios
        $titulo->addValidation(_t('Título'), new TRequiredValidator);
        $tipo->addValidation(_t('Tipo'), new TRequiredValidator);
        $descricao->addValidation(_t('Descrição'), new TRequiredValidator);
        $status->addValidation(_t('Status'), new TRequiredValidator);
    
        // define campos não editáveis
        TEntry::disableField('form_objeto', 'titulo');
        TCombo::disableField('form_objeto', 'tipo_id');
        TText::disableField('form_objeto', 'descricao');
    
        // adiciona campos ao formulário
        $this->form->addQuickField('', $id, 0);
        $this->form->addQuickField(_t('Título'), $titulo, 490);
        $this->form->addQuickField(_t('Tipo'), $tipo, 200);
        $this->form->addQuickField(_t('Descrição'), $descricao, 490, 200);
        $this->form->addQuickField(_t('Status'), $status, 150);
    
        // cria botões de ação
        $this->form->addQuickAction(_t('Salvar'), new TAction(array($this, 'onSave')), 'fa:floppy-o');

        // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        // adiciona colunas no datagrid
        $this->datagrid->addQuickColumn(_t('Data de registro'), 'data_registro', 'left', 120);
        $this->datagrid->addQuickColumn(_t('Hora de registro'), 'hora_registro', 'left', 120);
        $this->datagrid->addQuickColumn(_t('Título'), 'titulo', 'left', 200);
        $this->datagrid->addQuickColumn(_t('Doador'), 'usuario_nome', 'left', 100);
    
        // adiciona ações no datagrid
        $this->datagrid->addQuickAction(_t('Editar'),  new TDataGridAction(array($this, 'onEdit')), 'id', 'fa:pencil-square-o');
        
        // cria o modelo datagrid
        $this->datagrid->createModel();
        
        // cria estrutura da página
        $vbox = new TVBox;
        $vbox->add($this->form);
        $vbox->add($table);
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
            // recupera obejeto do banco de dados
            TTransaction::open('dadivar');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('status', 'LIKE', 'A'));
            $repos = new TRepository('Objeto');
            $objetos = $repos->load($criteria);
      
            // adiciona objetos recuperados ao datagrid
            $this->datagrid->clear();
            if ($objetos)
            {
                foreach ($objetos as $objeto)
                {
                    $this->datagrid->addItem($objeto);
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
            $form = $this->form->getData('StdClass');
            $objeto = new Objeto($form->id);
            $objeto->status = $form->status;
            $objeto->store();
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
                $objeto = new Objeto($key);
                $this->form->setData($objeto);
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