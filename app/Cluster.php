<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-05-18
 * Time: 2.52.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;


class Cluster extends Model
{

    protected $fillable = [
        "name",
        "ip_address",
        "cluster_id",
        "created_at",
        "updated_at"
    ];

}