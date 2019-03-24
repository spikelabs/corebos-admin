<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-03-24
 * Time: 2.18.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class DatabaseService extends Model
{

    protected $fillable = [
        "client_id",
        "database_id",
        "name",
        "label",
        "created_at",
        "updated_at",
    ];

}