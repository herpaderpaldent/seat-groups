<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 06.02.2018
 * Time: 23:22
 */

use Seat\Web\Models\User;

$userList = User::all();

foreach ($userList as $user){
    $keys = $user->getAllCharactersWithAffiliations(true);

}
