<?php
require_once 'init.php';
new TSession;

try
{
    TTransaction::open('dadivar');
    
    $repos = new TRepository('Mensagem');
    $criteria = new TCriteria;
    $criteria->add(new TFilter('conversa_id', '=', TSession::getValue('conversa_id')));
    $mensagens = $repos->load($criteria);

    $json = '[';
    foreach ($mensagens as $mensagem)
    { 
        $remetente = new Usuario($mensagem->remetente_id);
        $mensagem->classeCSS = $mensagem->remetente_id == Usuario::newFromLogin(TSession::getValue('celular'))->id ? 'mensagem-enviada' : 'mensagem-recebida';
        $mensagem->remetente = $remetente->nome;
        $json .= $mensagem->toJson().',';
    }
    $json .= ']';
    
    TTransaction::close();

    echo substr_replace($json, '', -2, 1);
}
catch (Exception $e)
{
    new TMessage('error', $e->getMessage());
}