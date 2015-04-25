<?php
namespace Riuson\EveIGB\Classes;

use Str;

class IngameBrowser
{

    /**
     * Returns http headers from Ingame browser
     *
     * @return array
     */
    public static function getIgbHeaders()
    {
        $all = getallheaders();
        $result = array();

        if (strpos($all['User-Agent'], 'EVE-IGB') !== false) {
            $result['IGB'] = true;

            foreach ($all as $key => $value) {
                if (Str::startsWith($key, 'EVE')) {
                    $result[$key] = $value;
                }
            }
        } else {
            $result['IGB'] = false;
        }

        /*
         * https://wiki.eveonline.com/en/wiki/IGB_Headers
         *
         * Array (
         * [IGB] => 1
         * [EVE_SHIPNAME] => ??????
         * [EVE_STATIONID] => 60007723
         * [EVE_SOLARSYSTEMID] => 30005050
         * [EVE_CONSTELLATIONID] => 20000739
         * [EVE_SHIPID] => 206910xxxx
         * [EVE_CONSTELLATIONNAME] => Panoumid
         * [EVE_CORPNAME] => Center for Advanced Studies
         * [EVE_CHARID] => 33848xxxx
         * [EVE_SHIPTYPENAME] => Gallente Shuttle
         * [EVE_SHIPTYPEID] => 11129
         * [EVE_CORPID] => 1000169
         * [EVE_SOLARSYSTEMNAME] => Kulu
         * [EVE_REGIONNAME] => Kor-Azor
         * [EVE_REGIONID] => 10000065
         * [EVE_TRUSTED] => Yes
         * [EVE_SERVERIP] => 87.237.38.200:26000
         * [EVE_CHARNAME] => XXXXX XXXXX
         * [EVE_STATIONNAME] => Kulu X - Moon 16 - Royal Amarr Institute School
         * )
         */

        return $result;
    }
}