<?php
class DoacaoForm extends TPage
{
    private $datagrid;
    private $loaded;
  
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
        if ((Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'ADMINISTRADOR') &&
            (Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'USUARIO'))
        {
            throw new Exception(_t('Permissão negada'));
        }
        TTransaction::close();
    
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
    
        // adiciona colunas no datagrid
        $this->datagrid->addQuickColumn(_t('Título'), 'titulo', 'left', 600);
    
        // adiciona ações no datagrid
        $this->datagrid->addQuickAction(_t('Deletar'), new TDataGridAction(array($this, 'onDelete')), 'id', 'fa:times');
    
        // cria o modelo datagrid
        $this->datagrid->createModel();
    
        // botão voltar
        $form_back = new TQuickForm('form_back');
        $form_back->class = 'tform';
        $form_back->addQuickAction(_t('Voltar'), new TAction(array($this, 'onVoltarCatalogo')), 'fa:backward');
        $form_back->addQuickAction(_t('Finalizar Pedido'), new TAction(array($this, 'onFinalizarPedido')),'fa:check');
    
        // O conteúdo da página utilizando caixa vertical
        $vbox = new TVBox;
        $vbox->add($this->datagrid);
        $vbox->add($form_back);

        parent::add($vbox);
    }
  
    /**
     * Carrega o datagrid com os tipos do banco de dados
     */
    function onReload($param = NULL)
    {
        // recupera lista de objetos da sessão
        $objetos = TSession::getValue('objetos');
        
        if (empty($objetos))
        {
            return;
        }
        
        // limpa datagrid
        $this->datagrid->clear();
    
        try
        {
            // abre conexão
            TTransaction::open('dadivar');
          
            foreach ($objetos as $id => $adicionado)
            {
                $objeto = new Objeto($id);
                $this->datagrid->addItem($objeto);
            }
      
            // fecha conexão
            TTransaction::close();
        }
        catch (Exception $e)
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
        // recupera lista de objetos da sessão
        $objetos = TSession::getValue('objetos');
    
        // exclui objeto da lista
        unset($objetos[$param['id']]);
    
        // substitui lista atualizada na sessão
        TSession::setValue('objetos', $objetos);
    
        // recarrega página  
        $this->onReload();
    }
  
    /**
     * Voltar ao Catálogo
     */
    public function onVoltarCatalogo()
    {
        TApplication::loadPage('ObjetoList');
    }
  
    /**
     * Finaliza Pedido
     */
    public function onFinalizarPedido()
    {
        try
        { 
            // recupera lista de objetos da sessão
            $objetos = TSession::getValue('objetos');  
      
            if (empty($objetos))
            {
                return;
            }
            
            TTransaction::open('dadivar'); 
            
            // cria um novo objeto do tipo doação
            $doacao = new Doacao; 
            $doacao->data_registro  = date('Y-m-d');
            $doacao->hora_registro  = date('H:i:s');
            $doacao->usuario_id     = Usuario::newFromLogin(TSession::getValue('celular'))->id;
      
            // Ínicio ajuste - Alan - 02.02.2016
            // preenche objetos da doação
            foreach ($objetos as $id => $adicionado)
            {
                $objeto = new Objeto($id);
                $doacao->addObjeto($objeto);
                $objeto->mudarStatus('N');
                $objeto->store();
            }
            // Fim ajuste - Alan - 02.02.2016
      
            $doacao->store(); // store doação
              
            $objetos = TSession::getValue('objetos');
            unset($objetos);
            TSession::setValue('objetos',$objetos);
              
            $posAction = new TAction( array('ObjetoList', 'onReload') );
            new TMessage('info',  _t('Pedido Finalizado'), $posAction);
    
            TTransaction::close();
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