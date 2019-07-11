<?php
namespace Webcosmonauts\Alder\Structure;

use InvalidArgumentException;

class FieldState
{
    public const DIFF_TYPE     = 'TYPE';
    public const DIFF_NULLABLE = 'NULLABLE';
    public const DIFF_DEFAULT  = 'DEFAULT';
    public const DIFF_INDEXED  = 'INDEXED';
    public const DIFF_UNIQUE   = 'UNIQUE';

    private const safeTypeUpgrades = [
        'string'  => ['text'],
        'integer' => ['string', 'text'],
        'date'    => ['string', 'text'],
    ];

    static private function canUpgradeTypeSafe($a, $b) {
        $stu = self::safeTypeUpgrades;
        return $a == $b || isset($stu[$a]) && in_array($b, $stu[$a]);
    }

    public $type;
    public $nullable;
    public $default;
    public $indexed;
    public $unique;

    public function __construct($column) {
        $column = (object)$column;
        if(!isset($column->type) || !$column->type) throw new InvalidArgumentException();
        $this->type     = $column->type;
        $this->nullable = $column->nullable ?? true;
        $this->default  = $column->default  ?? null;
        $this->indexed  = $column->indexed  ?? false;
        $this->unique   = $column->unique   ?? false;
    }

    public function canUpgradeSafe(FieldState $up) {
        if(!self::canUpgradeTypeSafe($this->type, $up->type)) return false;
        if($this->nullable && !$up->nullable) return false;
        if(!$this->unique && $up->unique) return false;
        return true;
    }
}
