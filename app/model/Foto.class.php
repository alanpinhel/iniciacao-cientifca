<?php
/**
 * Active Record para Foto
 */
class Foto extends TRecord
{
    const TABLENAME  = 'foto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
}
?>