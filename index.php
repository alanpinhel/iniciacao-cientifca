<?php
require_once 'init.php';
$theme = 'theme1';
new TSession;

$lang = TSession::getValue('language') ? TSession::getValue('language') : 'pt';
TAdiantiCoreTranslator::setLanguage($lang);
TApplicationTranslator::setLanguage($lang);

// atualiza variável contadora de visitas únicas 
TTransaction::open('dadivar');
$visitas = new Visitas();
$visitas->ip_visitante = $visitas->get_ip();
$visitas->data_acesso  = date('Y-m-d');
$visitas->hora_acesso  = date('H:i:s');
$visitas->store();
$qtd_visitas = Visitas::countObjects();
TTransaction::close();

if (TSession::getValue('logged'))
{
  TTransaction::open('dadivar');
  $usuario = Usuario::newFromLogin(TSession::getValue('celular'));
  if ($usuario->categoria_nome == 'ADMINISTRADOR')
  {
    $content = file_get_contents("app/templates/{$theme}/admin.html");
  }
  else if ($usuario->categoria_nome == 'USUARIO')
  {
    $content = file_get_contents("app/templates/{$theme}/usuario.html");
  }
  TTransaction::close();
}
else
{
  $content = file_get_contents("app/templates/{$theme}/visitante.html");
}

$content = TApplicationTranslator::translateTemplate($content);
$content = str_replace('{LIBRARIES}', file_get_contents("app/templates/{$theme}/libraries.html"), $content);
$content = str_replace('{ICON}', "<link rel='icon' href='app/templates/{$theme}/images/favicon.png' />", $content);
$content = str_replace('{class}', isset($_REQUEST['class']) ? $_REQUEST['class'] : '', $content);
$content = str_replace('{template}', $theme, $content);
$content = str_replace('{foto}', $usuario->foto, $content);
$content = str_replace('{nome}', $usuario->nome, $content);
$content = str_replace('{visitas}', $qtd_visitas, $content);
$css     = TPage::getLoadedCSS();
$js      = TPage::getLoadedJS();
$content = str_replace('{HEAD}', $css.$js, $content);

echo $content;

if (TSession::getValue('logged'))
{
  if (isset($_REQUEST['class']))
  {
    $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : NULL;
    AdiantiCoreApplication::loadPage($_REQUEST['class'], $method, $_REQUEST);
  }
}
else if ($_REQUEST['class'] == 'CadastroFormStep1')
{
  AdiantiCoreApplication::loadPage('CadastroFormStep1', '', $_REQUEST);
}
else
{
  AdiantiCoreApplication::loadPage('LoginForm', '', $_REQUEST);
}