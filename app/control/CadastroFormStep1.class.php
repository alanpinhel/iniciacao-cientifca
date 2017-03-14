<?php
class CadastroFormStep1 extends TPage
{
    protected $form;
    protected $notebook;
    private   $frame;
  
    /**
     * Constrói página
     */
    function __construct()
    {
        parent::__construct();
    
        // cria o notebook
        $this->notebook = new TNotebook;
        $this->notebook->setSize(400, 140);
        
        // cria formulário
        $this->form = new TQuickForm('form_account');
        $this->notebook->appendPage(_t('Informações de acesso'), $this->form);
        
        // cria os campos do formulário
        $celular   = new TEntry('celular');
        $senha     = new TPassword('senha');
        $nome      = new TEntry('nome');
        $sobrenome = new TEntry('sobrenome');
        $foto      = new TFile('foto');
        $sexo      = new TRadioGroup('sexo');
        
        // avisa campos obrigatórios
        $celular->placeholder   = _t('DDD+Número');
        $senha->placeholder     = _t('Obrigatório');
        $nome->placeholder      = _t('Obrigatório');
        $sobrenome->placeholder = _t('Obrigatório');
        
        // limite de caracteres
        $celular->maxlength   = '11';
        $nome->maxlength      = '15';
        $sobrenome->maxlength = '15';
        
        $celular->setTip('Ex.: 9999999999');
        
        // ação após upload completo
        $foto->setCompleteAction(new TAction(array($this, 'onComplete')));
        
        // opções para radio group sexo
        $itemSexo = array();
        $itemSexo['M'] = _t('Masculino');
        $itemSexo['F'] = _t('Feminino');
        $sexo->addItems($itemSexo);
        $sexo->setLayout('horizontal');
        
        // adiciona os campos ao form
        $this->form->addQuickField(_t('Celular'), $celular, 200);
        $this->form->addQuickField(_t('Senha'), $senha, 200);
        $this->form->addQuickField(_t('Nome'), $nome, 200);
        $this->form->addQuickField(_t('Sobrenome'), $sobrenome, 200);
        $this->form->addQuickField(_t('Foto'), $foto, 180);
        $this->form->addQuickField(_t('Sexo'), $sexo, 200);
        
        $this->frame = new TElement('div');
        $this->frame->id = 'foto';
        $this->frame->style = 'width:64px;min-height:0;';
        $row = $this->form->addRow();
        $row->addCell('');
        $row->addCell($this->frame);
        
        // acrescenta validações aos campos
        $celular->addValidation(_t('Celular'), new TMinLengthValidator, array(11));
        $celular->addValidation(_t('Celular'), new TMaxLengthValidator, array(11));
        $senha->addValidation(_t('Senha'), new TMinLengthValidator, array(4));
        $nome->addValidation(_t('Nome'), new TRequiredValidator);
        $sobrenome->addValidation(_t('Sobrenome'), new TRequiredValidator);
    
        // cria a ação de avançar
        $this->form->addQuickAction(_t('Próximo'), new TAction(array($this, 'onSave')), 'fa:arrow-circle-right');
        
        // adiciona o formulário à página
        parent::add($this->notebook);
    }
  
    /**
     * Exibe foto que acabará de ser enviada
     */
    public static function onComplete($param)
    {
        // recarrega foto
        TScript::create("$('#foto').html('')");
        TScript::create("$('#foto').append(\"<img style='width:100%' src='tmp/{$param['foto']}'>\");");
    }
  
    /**
     * Redireciona para confirmação de cadastro
     */
    public function onNextForm()
    {
        // carrega outra página
        TApplication::loadPage('CadastroFormStep2');
    }
  
    /**
     * Salva usuário
     */
    public function onSave()
    {
        try
        {
            $this->foto = $this->form->foto;
      
            $this->form->validate();
      
            $data = $this->form->getData('Usuario');
      
            //$data->celular = preg_replace("/[^0-9]/", "", $data->celular);
            $data->criptografar();
            $data->gerarCodigoVerificacao();
            $data->data_cadastro = date('Y-m-d');
      
            $source_file = 'tmp/'.$data->foto;
            
            $target_file = 'uploads/'.rand(1,9999).'_'.$data->celular.'_'.$data->foto;
            $finfo = new finfo(FILEINFO_MIME_TYPE);
      
            // verifica se o usuario fez o upload de uma imagem
            if (file_exists($source_file) AND ($finfo->file($source_file) == 'image/png' OR 
                $finfo->file($source_file) == 'image/jpeg'))
            {
                // redimensiona imagem para 200xAuto
                $image = WideImage::load($source_file);
                $image = $image->resize(200);
                $image->saveToFile($source_file);
                
                // move a imagem ao diretorio definitivo
                rename($source_file, $target_file);
        
                // coloca caminho definitivo da foto no atributo
                $data->foto = $target_file;
            }
      
            // armazena os dados na sessão
            TSession::setValue('form_step1_data', $data);
      
            TTransaction::open('dadivar');
      
            // salva usuário
            $data->store();
        
            // se o código de verificação foi enviado com sucesso
            if ($data->enviarCodigoVerificacao())
            {
                // vai para confirmação do cadastro
                $this->onNextForm();
            }
        
            TTransaction::close();
        } 
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
?>