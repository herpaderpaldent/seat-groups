<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 04.09.2018
 * Time: 10:29.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Models;

use Illuminate\Database\Eloquent\Model;

class SeatgroupLog extends Model
{
    protected $fillable = [
        'event', 'message',
    ];

    protected $primaryKey = 'id';

}
