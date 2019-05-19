<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-05-18
 * Time: 2.54.MD
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $fillable = [
        "name",
        "dockerhub_image",
        "sql_file",
        "created_at",
        "updated_at"
    ];

}