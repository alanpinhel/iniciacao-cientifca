<?php
class DoacaoList extends TPage
{
    private $datagrid;
    private $loaded;
  
    function __construct()
    {
        parent::__construct();
     
        // label para doações solicitadas
        $lb_solicitadas = new TLabel(_t('Doações solicitadas'));
        $lb_solicitadas->setFontSize('12');
        $lb_solicitadas->setFontStyle('b');
     
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
     
        // define datagrid por grupo
        $this->datagrid->setGroupColumn('id', '<b>'._t('Doação').'</b>: {id}');
     
        // adiciona colunas no datagrid Solicitadas
        $this->datagrid->addQuickColumn(_t('Item'), 'objeto_titulo', 'left', 200);
        $this->datagrid->addQuickColumn(_t('Doador'), 'doador', 'left', 200);
        $this->datagrid->addQuickColumn(_t('Status'), 'status_descricao', 'left', 100);
     
        // cria o modelo datagrid
        $this->datagrid->createModel();  
     
        // pula linha
        $br = new TElement('br');
     
        // label para doações publicadas
        $lb_publicadas = new TLabel(_t('Itens solicitados a você'));
        $lb_publicadas->setFontSize('12');
        $lb_publicadas->setFontStyle('b');
     
        $this->datagrid1 = new TQuickGrid;
        $this->datagrid1->setHeight(320);
     
        // adiciona colunas no datagrid Publicadas
        $this->datagrid1->addQuickColumn('ID', 'id', 'left', 50);
        $this->datagrid1->addQuickColumn(_t('Objeto'), 'objeto_titulo', 'left', 200);
        $this->datagrid1->addQuickColumn(_t('Donatário'), 'donatario', 'left', 200);
        $this->datagrid1->addQuickColumn(_t('Status'), 'status_descricao', 'left', 100);
        
        // adiciona botão de ação
        $this->datagrid1->addQuickAction(_t('Alterar status'), new TDataGridAction(array($this, 'onEditStatus')), 'id', 'fa:external-link');
     
        // cria o modelo datagrid
        $this->datagrid1->createModel();  
     
        // incorpora elementos à estrutura
        $vbox = new TVBox;
        $vbox->add($lb_solicitadas);
        $vbox->add($this->datagrid);
        $vbox->add($br);
        $vbox->add($lb_publicadas);
        $vbox->add($this->datagrid1);

        // adiciona estrutura à página
        parent::add($vbox);
    }
   
    /**
     * Método onReload()
     * Carrega o datagrid com os tipos do banco de dados
     */
    public function onReload()
    {  
        try
        {
            // abre conexão
            TTransaction::open('dadivar');
            
            // instancia repositório p/ doação
            $repos = new TRepository('Doacao');
                
            // define filtro
            $criteria = new TCriteria;
            $criteria->add(new TFilter('usuario_id', '=', Usuario::newFromLogin(TSession::getValue('celular'))->id));
                
            // recupera doação, cujo usuário seja igual ao logado
            $doacoes = $repos->load($criteria);
          
            // limpa datagrid
            $this->datagrid->clear();
          
            // datagrid de doações solicitadas
            if ($doacoes)
            {
                foreach ($doacoes as $doacao)
                {
                    foreach ($doacao->getItens() as $item)
                    {
                        $objeto = new Objeto($item->objeto_id);
                        $stdclass = new StdClass;
                        $stdclass->id = $doacao->id;
                        $stdclass->objeto_titulo = $objeto->titulo;
                        $stdclass->doador = $objeto->usuario_nome.' '.$objeto->usuario_sobrenome.' / '.$objeto->usuario_celular;
                        $stdclass->status_descricao = $item->status_descricao;

                        // adiciona item no datagrid
                        $this->datagrid->addItem($stdclass);
                    }
                }
            }
            
            // instancia repositório para objeto
            $repos = new TRepository('Objeto');
                
            // define filtro
            $criteria = new TCriteria;
            $criteria->add(new TFilter('usuario_id', '=', Usuario::newFromLogin(TSession::getValue('celular'))->id));
                
            // recupera objetos, cujo usuário seja igual ao logado
            $objetos = $repos->load($criteria);
            
            // usuário não publicou nenhum objeto, sai do método
            if (empty($objetos))
            {
                return;
            }
            
            // preenche array de objetos, para filtrar itens
            $arrayObjetos = array();
            foreach ($objetos as $objeto)
            {
                $arrayObjetos[] = $objeto->id;
            }
            
            // instancia repositório p/ objeto
            $repos = new TRepository('Item');
        
            // define filtro
            $criteria = new TCriteria;
            $criteria->add(new TFilter('objeto_id', 'IN', $arrayObjetos));
                
            // recupera itens dos objetos correspondentes
            $itens = $repos->load($criteria);
            
            // limpa datagrid
            $this->datagrid1->clear();
            
            foreach ($itens as $item)
            {
                $objeto = new Objeto($item->objeto_id);
                $doacao = $item->getDoacao();
                
                $stdclass = new StdClass;
                $stdclass->id = $item->id;
                $stdclass->objeto_titulo = $objeto->titulo;
                $stdclass->donatario = $doacao->usuario_nome.' '.$doacao->usuario_sobrenome.' / '.$objeto->usuario_celular;
                $stdclass->status_descricao = $item->status_descricao;
                
                // adiciona item no datagrid
                $this->datagrid1->addItem($stdclass);
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
    
    /**
     * Modifica status
     */
    public function onEditStatus($param)
    {
        $item_id = new THidden('item_id');
        $status = new TCombo('status');
        
        // adiciona itens ao status
        $status->addItems(array('A' => _t('ACEITO'), 'R' => _t('RECUSADO')));
        
        $form = new TForm('input_form');
        $form->style = 'padding:20px';
        
        $table = new TTable;
        $table->addRowSet(new TLabel(_t('Status')), $status);
        $table->addRowSet(new TLabel(''), $item_id);
        
        $item_id->setValue($param['id']);
        
        $form->setFields(array($item_id, $status));
        $form->add($table);
            
        // mostra diálogo de entrada
        $action = new TAction(array($this, 'onConfirm'));
        $action->setParameter('stay-open', 1);
        new TInputDialog(_t('Alterar status'), $form, $action, _t('Confirmar'));
    }
    
    /**
     * Salva item com status alterado
     */
    public function onConfirm($param)
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
            
            $item = new Item($param['item_id']);
            if ($item->mudarStatus($param['status']))
            {
                // salva item
                $item->store();
                
                // se usuário recusar doação, objeto volta pra disponível        
                if ($item->status == 'R')
                {
                    $objeto = new Objeto($item->objeto_id);
                    if ($objeto->mudarStatus('D'))
                    {
                        $objeto->store();   
                    }
                }
                
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
}
?>