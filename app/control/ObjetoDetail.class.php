<?php
class ObjetoDetail extends TWindow
{
    private $html;
    private $form;
    private $loaded;
  
    /**
     * Constrói a página
     */
    function __construct($param)
    {
        parent::__construct();
        parent::setSize(800, 800);
    
        if (TSession::getValue('logged') == TRUE)
        {
            $this->html = new THtmlRenderer('app/resources/detalhes.html');
        }
        else
        {
            $this->html = new THtmlRenderer('app/resources/detalhesVisitante.html');
        }
    
        TPage::include_css('app/resources/detalhes.css');
    
        // formulário
        $this->form = new TQuickForm('form_comentario');

        // campos do formulario
        $texto = new TText('texto');
    
        // campo obrigatório
        $texto->addValidation(_t('Texto'), new TRequiredValidator);
        $texto->setTip(_t('Deixe um comentário...'));
    
        // adicionar no formulario
        $this->form->addQuickField('', $texto, 520, 83);
        $this->form->addQuickAction('Enviar', new TAction(array($this, 'onComentar')),'');
    
        // cria a div
        $galleria = new TElement('div');
        $galleria->id    = 'images';
        $galleria->style = "width:294px;min-height:249px";
      
        // cria o script
        $script = new TElement('script');
        $script->type = 'text/javascript';
        $script->add("Galleria.loadTheme('app/lib/jquery/galleria/themes/classic/galleria.classic.min.js'); Galleria.run('#images');");
    
        if (!empty($param['id']))
        {
            TSession::setValue('objeto_id', $param['id']);
        }
    
        try
        {
            TTransaction::open('dadivar');
            $objeto = new Objeto(TSession::getValue('objeto_id'));
            $addlist = 'index.php?class=ObjetoDetail&method=onObjetoAddList&id='.$objeto->id;
            $lbAddLista = _t('Adicionar à lista');
            $fotos = $objeto->getFotos();
      
            foreach ($fotos as $foto)
            {
                $img  = new TElement('img');
                $img->src = $foto->arquivo;
                $galleria->add($img);
            }

            $replace = 
                array
                (
                    'galeria'    => $galleria.$script,
                    'titulo'     => $objeto->titulo,
                    'descricao'  => $objeto->descricao,
                    'marca'      => $objeto->marca,
                    'cor'        => $objeto->cor_nome,
                    'tamanho'    => $objeto->tamanho,
                    'condicao'   => $objeto->condicao,
                    'addlist'    => $addlist,
                    'lbAddLista' => $lbAddLista,
                    'doador'     => $objeto->usuario_nome,
                    'dFoto'      => $objeto->usuario_foto,
                    'uFoto'      => Usuario::newFromLogin(TSession::getValue('celular'))->foto,
                    'comentario' => $this->form
                );

            TTransaction::close();  
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
        parent::setTitle($objeto->titulo);

        // substitui sigla por descrição
        $replace['condicao'] = $replace['condicao'] == 'N' ? _t('NOVO') : _t('USADO');
    
        // substitui vazio por em branco ou sigla por descrição
        if (empty($replace['tamanho']))
        {
            $replace['tamanho'] = '';   
        }
        elseif ($replace['tamanho'] == 'U')
        {
            $replace['tamanho'] = _t('ÚNICO');   
        }
      
        // substitui marca vazia por em branco
        $replace['marca'] = empty($replace['marca']) ? '' : $replace['marca'];
      
        $this->html->enableSection('objeto', $replace);
    
        $vbox = new TVBox;
        $vbox->add($this->html);
        parent::add($vbox);
    }
  
    /**
     * Insere comentário no banco de dados
     */
    public function onComentar()
    {
        try
        {
            $this->form->validate();
      
            TTransaction::open('dadivar');
            $data = $this->form->getData('Comentario');
            $data->data_envio = date('Y-m-d');
            $data->hora_envio = date('H:i:s');
            $data->usuario_id = Usuario::newFromLogin(TSession::getValue('celular'))->id;
            $data->objeto_id  = TSession::getValue('objeto_id');
            $data->store();
            TTransaction::close();
      
            $this->form->clear();
      
            $this->onReload();
      
            new TMessage('info', _t('Comentário Enviado'));
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
  
    /**
     * Recarrega página
     */
    public function onReload()
    {
        try
        {
            $objeto_id = TSession::getValue('objeto_id');
            
            TTransaction::open('dadivar');
            
            $repos = new TRepository('Comentario');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('objeto_id', '=', $objeto_id));
            $comentarios = $repos->load($criteria);
      
            foreach ($comentarios as $comentario)
            {
                $replace[] = 
                    array
                    (
                        'allComentario' => $comentario->texto,
                        'cFoto'         => $comentario->get_usuario_foto()
                    );
            }
      
            TTransaction::close();
     
            $this->html->enableSection('comentario', $replace, TRUE);
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
  
    /**
     * Excetuado na construção da página
     */
    public function show()
    {
        $this->onReload(func_get_arg(0));
        parent::show();
    }
  
    /**
     * Adiciona objeto a lista de solicitação
     */
    public function onObjetoAddList($param)
    {
        // recupera lista da sessão
        $objetos = TSession::getValue('objetos');
    
        if (isset($objetos[$param['id']]))
        {
            new TMessage('info', _t('Objeto já foi adicionado'));
            return;
        }
    
        // adiciona objeto a lista
        $objetos[$param['id']] = true;
      
        // substitui lista atualizada na sessão
        TSession::setValue('objetos', $objetos);
      
        // vai para página de doação
        TApplication::loadPage('DoacaoForm', 'onReload');
    }
}
?>