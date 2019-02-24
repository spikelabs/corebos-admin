<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-23
 * Time: 8.31.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class DeploymentPvc extends Model
{

    protected $table = "deployment_vpc";

    protected $fillable = [
        "client_id",
        "deployment_id",
        "name",
        "storage",
        "created_at",
        "updated_at",
    ];

}

