<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-23
 * Time: 8.34.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Ingress extends Model
{

    protected $fillable = [
        "client_id",
        "service_id",
        "name",
        "created_at",
        "updated_at",
    ];

}