<?php
namespace Webcosmonauts\Alder;

use Illuminate\Http\Request;
use Webcosmonauts\Alder\Http\Controllers\BaseController;

use Webcosmonauts\Alder\Models\AdminMenuItem;
use Webcosmonauts\Alder\Models\Capability;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafStatus;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\Role;
use Webcosmonauts\Alder\Models\Setting;
use Webcosmonauts\Alder\Models\SettingTab;
use Webcosmonauts\Alder\Models\User;

class Alder
{
    protected $branchTypes = [
        'AdminMenuItem' => AdminMenuItem::class,
        'Capability'    => Capability::class,
        'Leaf'          => Leaf::class,
        'LeafStatus'    => LeafStatus::class,
        'LeafType'      => LeafType::class,
        'Role'          => Role::class,
        'Setting'       => Setting::class,
        'SettingTab'    => SettingTab::class,
        'User'          => User::class,
    ];
    
    /**
     * Get array of branch types
     *
     * @param bool $only_names
     *
     * @return array
     */
    public function getBranchTypes(bool $only_names = false) {
        if ($only_names) {
            return array_keys($this->branchTypes);
        }
        
        return $this->branchTypes;
    }
    
    /**
     * Get branch model
     *
     * @param mixed $obj
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getBranchModel($obj, $mode = 'name') {
        switch ($mode) {
            case 'name':
                $model = app($this->branchTypes[$obj]);
                break;
            default:
                $model = null;
        }
        return $model;
    }
}
