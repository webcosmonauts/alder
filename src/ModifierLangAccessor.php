<?php


namespace Webcosmonauts\Alder;

use Webcosmonauts\Alder\Models\Modifiers\BaseModifier;

class ModifierLangAccessor
{
    /** @var BaseModifier */
    public $modifier;
    public $lang;

    /**
     * ModifierLangAccessor constructor.
     * @param BaseModifier $modifier
     * @param string $lang
     */
    public function __construct(BaseModifier $modifier, $lang) {
        $this->modifier = $modifier;
        $this->lang     = $lang;
    }

    public function __get($key) {
        return $this->modifier->getAttributeValue($this->lang."__".$key) ?? $this->modifier->getAttributeValue($key);
    }
}
