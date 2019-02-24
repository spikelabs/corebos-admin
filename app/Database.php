<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-23
 * Time: 8.30.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Database extends Model
{

    protected $fillable = [
        "client_id",
        "name",
        "label",
        "db_database",
        "db_username",
        "db_password",
        "created_at",
        "updated_at",
    ];
}

