<?php
class CadastroFormStep2 extends TPage
{
    protected $form;
    protected $notebook;
   
    /**
     * Constrói página
     */
    function __construct()
    {
        parent::__construct();
      
        // cria notebook
        $this->notebook = new TNotebook;
        $this->notebook->setSize(400, 160);
      
        // cria o form
        $this->form = new TQuickForm('form_account');
        $this->notebook->appendPage(_t('Confirmação'), $this->form);
      
        // cria os campos do form
        $codigo = new TEntry('codigo');
      
        // adiciona os campos ao form
        $this->form->addQuickField(_t('Código de verificação'), $codigo, 200);
      
        // acrescenta validações
        $codigo->addValidation(_t('Código de verificação'), new TMinLengthValidator, array(6));
        $codigo->addValidation(_t('Código de verificação'), new TMaxLengthValidator, array(6));
      
        // cria ação de confirmar
        $this->form->addQuickAction(_t('Confirmar'), new TAction(array($this, 'onConfirm')), 'fa:check-circle');
      
        // adiciona alert a página
        $alert = new TAlert('info', _t('Foi enviado o código de verificação por SMS, espere alguns minutos.'));
        $alert->style = 'margin:0 0 10px 0;';
        parent::add($alert);
      
        // adiciona o formulário à página
        parent::add($this->notebook);
    }
   
    /**
     * Confirma cadastro
     */
    public function onConfirm()
    {
        try
        {
            $this->form->validate();
            $data = $this->form->getData('StdClass');
      
            $data_session = TSession::getValue('form_step1_data');
      
            TTransaction::open('dadivar');
            
            $repos = new TRepository('Usuario');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('celular', 'LIKE', $data_session->celular));
            $criteria->add(new TFilter('ativo', '=', 0));
            $usuario = $repos->load($criteria);
            
            if ($usuario[0]->ativar($data->codigo)) 
            {
                // salva modificação
                $usuario[0]->store();
                
                // carrega outra página
                TApplication::loadPage('LoginForm');
            }
            else 
            {
                new TMessage('erro', _t('Tente novamente'));
            }
      
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}