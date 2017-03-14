<?php
/**
 * Active Record para Categoria
 */
class Categoria extends TRecord
{
    const TABLENAME  = 'categoria';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
}
?>