<?php

namespace Webcosmonauts\Alder\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Webcosmonauts\Alder\Facades\AlderScheme;

/**
 * Class BaseModel
 *
 * @property string table_name
 */
abstract class BaseModel extends Model
{
    use HasRoles;

    protected $guard_name = 'AlderGuard';

    public static function getTableName() {
        $class = get_called_class();
        return (new $class)->getTable();
    }
}
