<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-23
 * Time: 8.18.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $fillable = [
        "name",
        "email",
        "company_name",
        "description",
        "sub_domain",
        "status",
        "database_status",
        "created_at",
        "updated_at",
        "cluster_id",
        "image_id"
    ];

}