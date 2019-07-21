<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-07-20
 * Time: 2.18.MD
 */

namespace App;

use Illuminate\Database\Eloquent\Model;


class PendingApproval extends Model
{

    protected $fillable = [
        "name",
        "email",
        "company_name",
        "description",
        "image_id",
        "created_at",
        "updated_at",
    ];

}
