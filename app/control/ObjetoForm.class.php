<?php
/**
 * Classe correspondente à cadastro de novos objetos
 */
class ObjetoForm extends TPage
{
    private $form;
  
    /**
     * Método construtor
     */
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
        if ((Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'ADMINISTRADOR') &&
            (Usuario::newFromLogin(TSession::getValue('celular'))->categoria_nome !== 'USUARIO'))
        {
            throw new Exception(_t('Permissão negada'));
        }
        TTransaction::close();
      
        // cria formulário
        $this->form = new TQuickForm('form_objeto');
        $this->form->setFormTitle('Form de objeto');
        $this->form->class = 'tform';
        $this->form->style = 'width:768px';
      
        // cria campos
        $fotos         = new TMultiFile('fotos');
        $id            = new THidden('id');
        $data_registro = new THidden('data_registro');
        $hora_registro = new THidden('hora_registro');
        $titulo        = new TEntry('titulo');
        $marca         = new TEntry('marca');
        $descricao     = new THtmlEditor('descricao');
        $tamanho       = new TEntry('tamanho');
        $condicao      = new TCombo('condicao');
        $etiqueta      = new TEntry('etiqueta');
        $status        = new THidden('status');
        $usuario       = new THidden('usuario_id');
        $cor           = new TDBCombo('cor_id', 'dadivar', 'Cor', 'id', 'nome');
        $secao         = new TDBCombo('secao_id', 'dadivar', 'Secao', 'id', 'nome');
        $pai           = new TCombo('pai_id');
        $tipo          = new TCombo('tipo_id');
    
        // define campos obrigatórios
        $fotos->addValidation(_t('Foto'), new TRequiredValidator);
        $titulo->addValidation(_t('Título'), new TRequiredValidator);
        $descricao->addValidation(_t('Descrição'), new TRequiredValidator);
        $condicao->addValidation(_t('Condição'), new TRequiredValidator);
        $cor->addValidation(_t('Cor'), new TRequiredValidator);
        $secao->addValidation(_t('Seção'), new TRequiredValidator);
        $pai->addValidation(_t('Tipo'), new TRequiredValidator);
    
        // define tamanho máximo de caracteres
        $titulo->maxlength   = '100';
        $marca->maxlength    = '20';
        $tamanho->maxlength  = '3';
        $etiqueta->maxlength = '255';
    
        // define campos não editáveis
        TCombo::disableField('form_objeto', 'pai_id');
        TCombo::disableField('form_objeto', 'tipo_id');
    
        // adiciona itens a condição
        $condicao->addItems( array('U' => _t('USADO'), 'N' => _t('NOVO')) );
    
        // define dica de campo
        $etiqueta->placeholder = _t('Separe as etiquetas por vírgula, exemplo: bicicleta,ciclismo,esporte');
    
        // adiciona campos ao formulário
        $this->form->addQuickField(_t('Fotos'), $fotos, 200);
        $this->form->addQuickField('', $id, 0);
        $this->form->addQuickField('', $data_registro, 0);
        $this->form->addQuickField('', $hora_registro, 0);
        $this->form->addQuickField(_('Título'), $titulo, 675);
        $this->form->addQuickField(_('Etiqueta'), $etiqueta, 675, 20);
        $this->form->addQuickField(_('Marca'), $marca, 200);
        $this->form->addQuickField(_('Tamanho'), $tamanho, 200);
        $this->form->addQuickField(_('Condição'), $condicao, 200);
        $this->form->addQuickField(_('Cor'), $cor, 200);
        $this->form->addQuickField(_('Seção'), $secao, 200);
        $this->form->addQuickField(_('Tipo'), $pai, 200);
        $this->form->addQuickField(_('Subtipo'), $tipo, 200);
        $this->form->addQuickField(_t('Descrição'), $descricao, 675, 200);
    
        // define ação após seleção
        $secao->setChangeAction(new TAction(array($this, 'onSecaoChange')));
        $pai->setChangeAction(new TAction(array($this, 'onPaiChange')));
        
        // cria botão de ação
        $this->form->addQuickAction(_t('Salvar'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
    
        // adiciona elementos a página
        $vbox = new TVBox;
        $vbox->add($this->form);
        parent::add($vbox);
    }
    
    /**
     * Ação executada após a seleção da seção.
     * @param $param parâmetros vindos do formulário.
     * Método estático, não há refresh na página.
     */
    static function onSecaoChange($param)
    {
        //  Abre conexão com banco de dados e adiciona os tipos
        // ao repositório que atendam aos critérios
        TTransaction::open('dadivar');
        $repositorio = new TRepository('Tipo');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pai_id', '=', 0));
        $criteria->add(new TFilter('secao_id', '=', $param['secao_id']));
        $pais = $repositorio->load($criteria);
        TTransaction::close();
        
        //  Adiciona item em branco, forçando usuário a selecionar
        // para que o evento change possa ser acionado
        $combo_itens = array();
        $combo_itens[0] = '';
        
        // Define vetor de itens para alimentar combo
        foreach ($pais as $pai)
        {
            $combo_itens[$pai->id] = $pai->nome;
        }
        
        // Recarrega combo e o habilita para edição
        TCombo::reload('form_objeto', 'pai_id', $combo_itens);
        TCombo::enableField('form_objeto', 'pai_id');
    }
    
    /**
     * Ação executada após a seleção do tipo pai.
     * @param $param parâmetros vindos do formulário.
     * Método estático, não há refresh na página.
     */
    static function onPaiChange($param)
    {
        //  Abre conexão com banco de dados e adiciona os tipos
        // ao repositório que atendam aos critérios
        TTransaction::open('dadivar');
        $repositorio = new TRepository('Tipo');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pai_id', '=', $param['pai_id']));
        $tipos = $repositorio->load($criteria);
        TTransaction::close();
        
        $combo_itens = array();
        
        if (empty($tipos))
        {
            // Desabilita campo, pois o mesmo não tem itens
            TCombo::reload('form_objeto', 'tipo_id', $combo_itens);
            TCombo::disableField('form_objeto', 'tipo_id');
            return;
        }
        
        // Define vetor de itens para alimentar combo
        foreach ($tipos as $tipo)
        {
            $combo_itens[$tipo->id] = $tipo->nome;
        }
        
        // Recarrega combo e o habilita para edição
        TCombo::reload('form_objeto', 'tipo_id', $combo_itens);
        TCombo::enableField('form_objeto', 'tipo_id');
    }
    
    /**
     * Método executado ao pressionar o botão Salvar.
     * Move as fotos para local definitivo e salva,
     * objeto no banco de dados.
     */
    function onSave()
    {
        try
        {
            // realiza validação do formulário
            $this->form->validate();
            
            // recupera formulário p/ classe genérica
            $data = $this->form->getData('StdClass');
            
            // abre conexão com o banco de dados
            TTransaction::open('dadivar');
            
            // define atributos p/ objeto
            $objeto                = new Objeto;
            $objeto->data_registro = date('Y-m-d');
            $objeto->hora_registro = date('H:i:s');
            $objeto->titulo        = $data->titulo;
            $objeto->marca         = $data->marca;
            $objeto->descricao     = $data->descricao;
            $objeto->tamanho       = $data->tamanho;
            $objeto->condicao      = $data->condicao;
            $objeto->etiqueta      = $data->etiqueta;
            $objeto->usuario_id    = Usuario::newFromLogin(TSession::getValue('celular'))->id;
            $objeto->cor_id        = $data->cor_id;
            $objeto->tipo_id       = empty($data->tipo_id) ? $data->pai_id : $data->tipo_id;
            
            // mover fotos para o local definitivo
            foreach ($data->fotos as $foto)
            {
                $source_file = 'tmp/'.$foto;
            
                $target_file = 'uploads/'.rand(1,9999).'_'.$objeto->usuario_celular.'_'.$foto;
                $finfo       = new finfo(FILEINFO_MIME_TYPE);
                
                // verifica se existe arquivo
                if (file_exists($source_file))
                {
                    // verifica se upload é imagem
                    if (($finfo->file($source_file) == 'image/png' OR $finfo->file($source_file) == 'image/jpeg'))
                    {
                        // redimensiona imagem para 600xAuto
                        $image = WideImage::load($source_file);
                        $image = $image->resize(600);
                        $image->saveToFile($source_file);
                        
                        // move a imagem p/ diretório definitivo
                        rename($source_file, $target_file);
                        
                        // adiciona foto ao vetor de fotos do objeto
                        $foto = new Foto;
                        $foto->arquivo = $target_file;
                        $objeto->addFoto($foto);
                    }
                }
            }
            
            // salva objeto no banco de dados e suas respectivas fotos
            $objeto->store();
            
            // fecha conexão com o banco de dados
            TTransaction::close();
            
            // informa usuário que registro foi salvo e limpa formulário
            new TMessage('info', _t('Registro salvo'));
            $this->form->clear();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
?>