<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 22.07.2018
 * Time: 16:04.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Parsedown;

class GetChangelog
{
    public function execute()
    {
        try {
            $response = (new Client())
                ->request('GET', 'https://raw.githubusercontent.com/herpaderpaldent/seat-groups/master/CHANGELOG.md');
            if ($response->getStatusCode() != 200) {
                return 'Error while fetching changelog';
            }
            $parser = new Parsedown();

            return $parser->parse($response->getBody());
        } catch (RequestException $e) {
            return 'Error while fetching changelog';
        }
    }
}
