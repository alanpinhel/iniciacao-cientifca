<?php
class ObjetoList extends TPage
{
    private $html;
    private $pageNavigation;
  
    function __construct()
    {
        parent::__construct();

        TPage::include_css('app/resources/catalogo.css');
        $this->html = new THtmlRenderer('app/resources/catalogo.html');
    
        // Criar o Formulario
        $this->form = new TQuickForm('form_search_objetos');
        $this->form->setFormTitle(_t('O que você quer ?'));
        $this->form->class = 'tform';
        $this->form->style = 'width: 668px';
    
        // Cria os campos  
        $busca = new TEntry('busca');
        $this->form->addQuickField('', $busca, 200);
        $this->form->addQuickAction(_t('Buscar'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('Lista de Pedidos'), new TAction(array($this, 'onPedidos')),'fa:list');
        
        // Mantém o formulário preenchido
        $this->form->setData(TSession::getValue('usuario_filter_data'));
    
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        
        // Filtros
        $tamanho  = new TCombo('tamanho');
        $condicao = new TCombo('condicao');
        $cor      = new TDBCombo('cor_id', 'dadivar', 'Cor', 'id', 'nome');
        
        $vbox = new TVBox;
        $vbox->add($this->form);
        $vbox->add($this->html);
        $vbox->add($this->pageNavigation);
        
        parent::add($vbox);
    }  
  
    /**
     * Recarrega página
     */
    public function onReload($param)
    {
        try
        {
            $limit = 24;
      
            TTransaction::open('dadivar');
      
            $repos = new TRepository('Objeto');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('status', 'LIKE', 'D'));
            $count = Objeto::countObjects($criteria);
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);
            $objetos = $repos->load($criteria);

            TTransaction::close();
            
            $replace = array();
            foreach ($objetos as $objeto)
            {
                if (strlen($objeto->titulo) > 29)
                {
                    $titulo = substr($objeto->titulo, 0, 22) . "...";
                }
                else 
                {
                    $titulo = $objeto->titulo;
                }
                
                $replace[] = 
                    array
                    (
                        'titulo'     => $titulo,
                        'foto'       => $objeto->getFotos()[0]->arquivo,
                        'link'       => 'index.php?class=ObjetoDetail&id='.$objeto->id,
                        'lbDetalhes' => _t('Detalhes')
                    );
            }
            
            $this->html->enableSection('objeto', $replace, TRUE);
      
            $this->pageNavigation->setCount($count);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($limit);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Chamado na construção da página
     */
    public function show()
    {
        $this->onReload(func_get_arg(0));
        parent::show();
    }

    /**
     * Realiza filtragem de objetos
     */
    public function onSearch($param)
    {
        $data = $this->form->getData();
        
        // registra busca no arquivo de log
        $handler = fopen('log/buscas.txt', 'a');
        $text = date('d/m/Y H:i:s')."\nTermo buscado: $data->busca\n\n";
        fwrite($handler, $text);
        fclose($handler);
        
        try
        {
            $limit = 24;
      
            TTransaction::open('dadivar');
      
            $repos = new TRepository('Objeto');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('titulo', 'LIKE', '%'.$data->busca.'%'), TExpression::OR_OPERATOR);
            $criteria->add(new TFilter('status', '=', 'D'));
            $criteria->add(new TFilter('marca', 'LIKE', '%'.$data->busca.'%'), TExpression::OR_OPERATOR);
            $criteria->add(new TFilter('status', '=', 'D'));
            $criteria->add(new TFilter('descricao', 'LIKE', '%'.$data->busca.'%'), TExpression::OR_OPERATOR); 
            $criteria->add(new TFilter('status', '=', 'D'));
            $criteria->add(new TFilter('etiqueta', 'LIKE', '%'.$data->busca.'%'), TExpression::OR_OPERATOR);
            $criteria->add(new TFilter('status', '=', 'D'));
      
            $count = Objeto::countObjects($criteria);
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            $objetos = $repos->load($criteria);

            TTransaction::close();

            $replace = array();
            foreach ($objetos as $objeto)
            {
                $replace[] = 
                    array
                    (
                        'titulo'     => $objeto->titulo,
                        'foto'       => $objeto->getFotos()[0]->arquivo,
                        'link'       => 'index.php?class=ObjetoDetail&id='.$objeto->id,
                        'lbDetalhes' => _t('Detalhes')
                    );
            }
      
            $this->html->enableSection('objeto', $replace, TRUE);
      
            $this->pageNavigation->setCount($count);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($limit);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
  
    /**
     * Redireciona para a página com a lista de objetos
     */
    public function onPedidos()
    {
        TApplication::loadPage('DoacaoForm');
    }
}
?>