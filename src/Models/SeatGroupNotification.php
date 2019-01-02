<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 13.02.2018
 * Time: 21:43.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SeatGroupNotification extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'herpaderp_seat_group_notifications';

    protected $primaryKey = 'channel_id';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['channel_id', 'via'];
}
