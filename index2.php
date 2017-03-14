<?php
require_once 'init.php';

// atualiza variável contadora de visitas únicas 
TTransaction::open('dadivar');
$visitas = new Visitas();
$visitas->ip_visitante = $visitas->get_ip();
$visitas->data_acesso  = date('Y-m-d');
$visitas->hora_acesso  = date('H:i:s');
$visitas->store();
$qtd_visitas = Visitas::countObjects();
TTransaction::close();

// renderiza index.html
$content = file_get_contents("index.html");
$content = str_replace('{visitas}', $qtd_visitas, $content);
echo $content;