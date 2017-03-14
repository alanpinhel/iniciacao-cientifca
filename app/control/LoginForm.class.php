<?php
class LoginForm extends TPage
{
    protected $form; // formulário
    
    /**
     * Cria a página e o formulário de cadastro
     */
    function __construct()
    {
        parent::__construct();
        
        $table = new TTable;
        $table->width = '100%';
        
        // cria formulário
        $this->form = new TForm('form_login');
        $this->form->class = 'tform';
        $this->form->style = 'margin: auto;'.
                             'width: 350px;';
        
        // adiciona tabela ao formulário
        $this->form->add($table);
        
        $langs = array();
        $langs['pt'] = _t('Português');
        $langs['en'] = _t('Inglês');
        
        // cria campos para o formulário
        $celu = new TEntry('celular');
        $pass = new TPassword('senha');
        $lang = new TCombo('language');

        // limita quantidade de caracteres
        $celu->maxlength = '11';

        // Validação para os campos
        $celu->addValidation(_t('Celular'), new TMinLengthValidator, array(11));
        $celu->addValidation(_t('Celular'), new TMaxLengthValidator, array(11));
        $pass->addValidation(_t('Senha'), new TMinLengthValidator, array(4));
        $pass->addValidation(_t('Senha'), new TMaxLengthValidator, array(32));
        
        $lang->addItems($langs);
        $lang->setValue(TSession::getValue('language'));
        
        // add a row for the form title
        $row  = $table->addRow();
        $cell = $row->addCell(new TLabel(_t('Entrar')));
        $cell->colspan = 2;
        $row->class = 'tformtitle';
        
        $table->addRowSet(new TLabel(_t('Celular')), $celu);
        $table->addRowSet(new TLabel(_t('Senha')), $pass);
        $table->addRowSet(new TLabel(_t('Idioma')), $lang);
        
        // cria botão de login
        $save_button=new TButton('login');
        $save_button->setAction(new TAction(array($this, 'onLogin')), _t('Entrar'));
        $save_button->setImage('fa:check-circle');
    
        // cria botão para novo usuário
        $new_user=new TButton('cadastro');
        $new_user->setAction(new TAction(array ($this,'onNewUser')), _t('Cadastrar-se'));
        $new_user->setImage('fa:user-plus');
        
        $row = $table->addRowSet($save_button,$new_user);
        $row->class = 'tformaction';
    
        // define the form fields
        $this->form->setFields(array($celu, $pass, $lang, $save_button, $new_user));
        
        // add the form to the page
        parent::add($this->form);
    }
    
    /**
     * Valida o login
     */
    function onLogin()
    {
        try
        {
            // validate form data
            $this->form->validate();
            
            TTransaction::open('dadivar');
            
            $data = $this->form->getData('Usuario');
            $data->criptografar();
            
            $language = ($data->language) ? $data->language : 'pt';
            TAdiantiCoreTranslator::setLanguage($language);
            TApplicationTranslator::setLanguage($language);
      
            // retira caracteres especiais
            //$data->celular = preg_replace("/[^0-9]/", "", $data->celular);
      
            $auth = Usuario::autenticate($data->celular, $data->senha);
            if ($auth)
            {
                TSession::setValue('logged', TRUE);
                TSession::setValue('celular', $data->celular);
                TSession::setValue('language', $data->language);

                // vai para página de menu
                TApplication::gotoPage('SetupPage', 'onSetup');
            }
            
            // finaliza a transação
            TTransaction::close();
        }
        catch (Exception $e)
        {
            TSession::setValue('logged', FALSE);
            
            // exibe a mensagem gerada pela exceção
            new TMessage('error', $e->getMessage());
      
            // desfaz todas alterações no banco de dados
            TTransaction::rollback();
        }
    }
    
    /**
     * Executado quando o usuário clicar no botão logout
     */
    function onLogout()
    {
        TSession::setValue('logged', FALSE);
        TScript::create('window.location="index2.php"');
    }
  
    /**
     * Redireciona para tela de cadastro
     */
    function onNewUser()
    {
        TApplication::loadPage('CadastroFormStep1');
    }
}
