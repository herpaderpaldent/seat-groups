<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 11:32
 */

namespace Herpaderpaldent\Seat\SeatGroups\Models;


use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Corporation\CorporationTitle;

class CorporationTitleSeatgroups extends Model
{
    protected $fillable = ['corporation_id','title_id','seatgroup_id'];



}