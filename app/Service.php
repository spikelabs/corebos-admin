<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-23
 * Time: 8.35.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = [
        "client_id",
        "deployment_id",
        "name",
        "label",
        "created_at",
        "updated_at",
    ];

}