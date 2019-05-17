<?php
    
namespace Webcosmonauts\Alder\Models\Traits;

trait TableColumnsTrait
{
    public function getTableColumnsAttribute() {
        return $this->table_columns;
    }
}