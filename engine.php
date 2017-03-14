<?php
require_once 'init.php';

class TApplication extends AdiantiCoreApplication
{
  static public function run($debug = FALSE)
  {
    new TSession;
    
    $lang = TSession::getValue('language') ? TSession::getValue('language') : 'pt';
    TAdiantiCoreTranslator::setLanguage($lang);
    TApplicationTranslator::setLanguage($lang);
        
    if ($_REQUEST)
    {
      $class = isset($_REQUEST['class']) ? $_REQUEST['class'] : '';
            
      if (!TSession::getValue('logged')     AND 
        $class !== 'LoginForm'              AND 
        $class !== 'CadastroFormStep1'      AND
        $class !== 'CadastroFormStep2'      AND
        $class !== 'ObjetoList'             AND
        $class !== 'ObjetoDetail'           AND
        $class !== 'AdiantiUploaderService' AND
        $class !== 'DoacaoForm'             AND
        $class !== 'UsuarioFormConfirmar')
      {
        echo TPage::getLoadedCSS();
        echo TPage::getLoadedJS();
        new TMessage('error', _t('Não logado'));
        return;
      }
      parent::run($debug);
    }
  }
}

TApplication::run(FALSE);