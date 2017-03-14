<?php
/**
 * Classe para alterar status dos objetos publicados
 */
class ObjetoAlteraStatus extends TPage
{
    private $datagrid;
   
    /**
     * Constrói a página
     */
    function __construct()
    {
        parent::__construct();
        
        // cria datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        
        // adiciona colunas
        $this->datagrid->addQuickColumn('ID', 'id', 'left', 50);
        $this->datagrid->addQuickColumn(_t('Título'), 'titulo', 'left', 200);
        $this->datagrid->addQuickColumn(_t('Status'), 'status_descricao', 'left', 100);
        
        // adiciona botão de ação
        $this->datagrid->addQuickAction(_t('Alterar status'), new TDataGridAction(array($this, 'onEditStatus')), 'id', 'fa:external-link');
        
        // cria modelo datagrid
        $this->datagrid->createModel();
        
        // cria estrutura vertical para página
        $vbox = new TVBox;
        $vbox->add($this->datagrid);
        
        // adiciona estrutura a página
        parent::add($vbox);
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
            
            // instancia repositório para objeto
            $repos = new TRepository('Objeto');
                
            // define filtro
            $criteria = new TCriteria;
            $criteria->add(new TFilter('usuario_id', '=', Usuario::newFromLogin(TSession::getValue('celular'))->id));
                
            // recupera objetos, cujo usuário seja igual ao logado
            $objetos = $repos->load($criteria);
            
            if ($objetos)
            {
                // limpa datagrid
                $this->datagrid->clear();
                
                // preenche datagrid
                foreach ($objetos as $objeto)
                {
                    $this->datagrid->addItem($objeto);
                }
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Exibe diálogo para entrada do status
     */
    public function onEditStatus($param)
    {
        $objeto_id = new THidden('objeto_id');
        $status = new TCombo('status');
        
        // adiciona itens ao status
        $status->addItems(array('T' => _t('TRANSPORTANDO'), 'I' => _t('INDISPONIVEL')));
        
        $form = new TForm('input_form');
        $form->style = 'padding:20px';
        
        $table = new TTable;
        $table->addRowSet(new TLabel(_t('Status')), $status);
        $table->addRowSet(new TLabel(''), $objeto_id);
        
        $objeto_id->setValue($param['id']);
        
        $form->setFields(array($objeto_id, $status));
        $form->add($table);
            
        // mostra diálogo de entrada
        $action = new TAction(array($this, 'editStatus'));
        $action->setParameter('stay-open', 1);
        new TInputDialog(_t('Alterar status'), $form, $action, _t('Confirmar'));
    }
    
    /**
     * Salva objeto com alteração de status
     */
    public function editStatus($param)
    {
        if (empty($param['status']))
        {
            new TMessage('error', _t('Status é obrigatório'));
            return;
        }
        
        try
        {
            // abre conexão
            TTransaction::open('dadivar');
            
            $objeto = new Objeto($param['objeto_id']);
            if ($objeto->mudarStatus($param['status']))
            {
                // salva objeto
                $objeto->store();
                new TMessage('info', _t('Registro salvo'));
            }
            else
            {
                new TMessage('error', _t('Mudança de status não faz sentido'));
            }
            
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
    
    /**
     * Executado na construção da página
     */
    function show()
    {
        $this->onReload(func_get_arg(0));
        parent::show();
    }
}
?>