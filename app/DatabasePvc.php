<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-23
 * Time: 8.29.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class DatabasePvc extends Model
{

    protected $table = "database_vpc";

    protected $fillable = [
        "client_id",
        "database_id",
        "storage",
        "name",
        "created_at",
        "updated_at",
    ];
}

