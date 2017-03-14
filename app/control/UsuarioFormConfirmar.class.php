<?php
class UsuarioFormConfirmar extends TPage
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
                             'width: 370px;';
        
        // adiciona tabela ao formulário
        $this->form->add($table);
        
        // cria campos para o formulário
        $celu   = new TEntry('celular');
        $codigo = new TEntry('codigo');

        // limita quantidade de caracteres
        $celu->maxlength = '11';
        $codigo->maxlength = '6';

        // Validação para os campos
        $celu->addValidation(_t('Celular'), new TMinLengthValidator, array(11));
        $celu->addValidation(_t('Celular'), new TMaxLengthValidator, array(11));
        $codigo->addValidation(_t('Código de verificação'), new TMinLengthValidator, array(6));
        $codigo->addValidation(_t('Código de verificação'), new TMaxLengthValidator, array(6));
        
        // add a row for the form title
        $row  = $table->addRow();
        $cell = $row->addCell(new TLabel(_t('Confirmar conta')));
        $cell->colspan = 2;
        $row->class = 'tformtitle';
        
        $table->addRowSet(new TLabel(_t('Celular')), $celu);
        $table->addRowSet(new TLabel(_t('Código de verificação')), $codigo);
        
        // cria botão de confirmação
        $confirm_btn = new TButton('confirm');
        $confirm_btn->setAction(new TAction(array($this, 'onConfirm')), _t('Confirmar'));
        $confirm_btn->setImage('fa:check-circle');
        
        $row = $table->addRowSet($confirm_btn, '');
        $row->class = 'tformaction';
    
        // define the form fields
        $this->form->setFields(array($celu, $codigo, $confirm_btn));
        
        // add the form to the page
        parent::add($this->form);
    }
    
    /**
     * Confirmar cadastro
     */
    function onConfirm()
    {
       try
        {
            $this->form->validate();
            $data = $this->form->getData('StdClass');
      
            TTransaction::open('dadivar');
            
            $repos = new TRepository('Usuario');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('celular', 'LIKE', $data->celular));
            $criteria->add(new TFilter('ativo', '=', 0));
            $usuario = $repos->load($criteria);
            
            if ($usuario)
            {
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
            }
            else
            {
                new TMessage('erro', _t('Usuário não encontrado'));
            }
      
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        } 
    }
}
