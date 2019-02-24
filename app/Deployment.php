<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-23
 * Time: 8.32.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{

    protected $fillable = [
        "client_id",
        "replicas",
        "name",
        "label",
        "created_at",
        "updated_at",
    ];

}