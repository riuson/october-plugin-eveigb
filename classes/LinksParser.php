<?php
namespace Riuson\EveIGB\Classes;

use Str;

/**
 * EVE links parser
 *
 * @author vladimir
 *
 */
class LinksParser
{

    /**
     *
     * @param string $url
     *            Source url
     * @param string $title
     *            Title for url
     * @return string Output parsed string
     */
    public static function parse($url, $title = null)
    {
        $headers = IngameBrowser::getIgbHeaders();

        if ($headers['IGB'] === true) {
            $matches = array();

            foreach (LinksParser::getInstance()->mIngameTags as $tag => $format) {
                if (preg_match($tag, $url, $matches)) {
                    $matches[0] = $title;
                    $result = vsprintf($format, $matches);
                    // throw new \Exception(print_r($matches, true));
                    // throw new \Exception(print_r($result, true));
                    return $result;
                }
            }
            return "<strong>$title</strong>";
        } else {
            $matches = array();

            foreach (LinksParser::getInstance()->mOutgameTags as $tag => $format) {
                if (preg_match($tag, $url, $matches)) {
                    $matches[0] = $title;
                    $result = vsprintf($format, $matches);
                    // throw new \Exception(print_r($matches, true));
                    // throw new \Exception(print_r($result, true));
                    return $result;
                }
            }
            return "<strong>$title</strong>";
        }
    }

    public static function blogpostParseLinks($message)
    {
        $matches = array();

        if (preg_match_all("/evelink\(\'(.+?)\'\, \'(.+?)\'\)/i", $message, $matches) > 0) {
            $links = $matches[0];
            $values = $matches[1];
            $titles = $matches[2];

            for ($i = 0; $i < count($links); $i++) {
                $parsed = LinksParser::parse($values[$i], $titles[$i]);
                $message = str_replace($links[$i], $parsed, $message);
            }
        }

        return $message;
    }

    private static $mInstance;

    private $mIngameTags;

    private $mOutgameTags;

    private function __construct()
    {
        $this->mIngameTags = [
            '/^(showinfo)\:(\d+)\/\/(\d+)$/i' => '<a href="" onclick="CCPEVE.showInfo(%3$d, %4$d); event.preventDefault();">%1$s</a>',
            '/^(showinfo)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.showInfo(%3$d); event.preventDefault();">%1$s</a>',
            '/^(fitting)\:([\d\:\;]+)$/i' => '<a href="" onclick="CCPEVE.showFitting(\'%3$s\'); event.preventDefault();">%1$s</a>',
            '/^(preview)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.showPreview(%3$d); event.preventDefault();">%1$s</a>',
            '/^(routeTo)\:(\d+)\D+(\d+)$/i' => '<a href="" onclick="CCPEVE.showRouteTo(%3$d, %4$d); event.preventDefault();">%1$s</a>',
            '/^(routeTo)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.showRouteTo(%3$d); event.preventDefault();">%1$s</a>',
            '/^(map)$/i' => '<a href="" onclick="CCPEVE.showMap(); event.preventDefault();">%1$s</a>',
            '/^(map)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.showMap(%3$d); event.preventDefault();">%1$s</a>',
            '/^(marketDetails)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.showMarketDetails(%3$d); event.preventDefault();">%1$s</a>',
            '/^(requestTrust)\:(.*)$/i' => '<a href="" onclick="CCPEVE.requestTrust(\'%3$s\'); event.preventDefault();">%1$s</a>',
            '/^(setDestination)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.setDestination(%3$d); event.preventDefault();">%1$s</a>',
            '/^(addWaypoint)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.addWaypoint(%3$d); event.preventDefault();">%1$s</a>',
            '/^(joinChannel)\:(.*)$/i' => '<a href="" onclick="CCPEVE.joinChannel(\'%3$s\'); event.preventDefault();">%1$s</a>',
            '/^(joinMailingList)\:(.*)$/i' => '<a href="" onclick="CCPEVE.joinMailingList(\'%3$s\'); event.preventDefault();">%1$s</a>',
            '/^(findInContracts)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.findInContracts(%3$d); event.preventDefault();">%1$s</a>',
            '/^(addContact)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.addContact(%3$d); event.preventDefault();">%1$s</a>',
            '/^(addCorpContact)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.addCorpContact(%3$d); event.preventDefault();">%1$s</a>',
            '/^(startConversation)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.startConversation(%3$d); event.preventDefault();">%1$s</a>',
            '/^(showOnMap)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.showOnMap(%3$d); event.preventDefault();">%1$s</a>',
            '/^(sendMail)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.sendMail(%3$d, \' \', \' \'); event.preventDefault();">%1$s</a>', // body and subj not implemented
            '/^(bookmark)\:(\d+)$/i' => '<a href="" onclick="CCPEVE.bookmark(%3$d); event.preventDefault();">%1$s</a>'
        ];

        $this->mOutgameTags = [
            '/^(showinfo\:1377)\/\/(\d+)$/i' => '<a href="https://zkillboard.com/character/%3$d" target="_blank">%1$s <span class="fa fa-external-link"></span></a>', // character
            '/^(showinfo\:3867)\/\/(\d+)$/i' => '<a href="http://evemaps.dotlan.net/search?q=%3$d" target="_blank">%1$s <span class="fa fa-external-link"></span></a>', // station
            '/^(showinfo\:2)\/\/(\d+)$/i' => '<a href="https://zkillboard.com/corporation/%3$d" target="_blank">%1$s <span class="fa fa-external-link"></span></a>', // corp
            '/^(showinfo\:16159)\/\/(\d+)$/i' => '<a href="https://zkillboard.com/alliance/%3$d" target="_blank">%1$s</a>', // alliance
            '/^(showinfo)\:(\d+)\/\/(\d+)$/i' => '<a href="" onclick="event.preventDefault();"  title="Ingame only">%1$s</a>',
            '/^(showinfo)\:(\d+)$/i' => '<a href="" onclick="event.preventDefault();"  title="Ingame only">%1$s</a>',

            '/^(fitting)\:([\d\:\;]+)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>',
            '/^(preview)\:(\d+)$/i' => '<a href="" onclick="event.preventDefault();" target="_blank">%1$s</a>',
            '/^(routeTo)\:(\d+)\D+(\d+)$/i' => '<a href="" onclick="event.preventDefault();" target="_blank">%1$s</a>',
            '/^(routeTo)\:(\d+)$/i' => '<a href="http://evemaps.dotlan.net/search?q=%3$d" target="_blank">%1$s <span class="fa fa-external-link"></span></a>',
            '/^(map)$/i' => '<a href="http://evemaps.dotlan.net" target="_blank">%1$s <span class="fa fa-external-link"></span></a>',
            '/^(map)\:(\d+)$/i' => '<a href="http://evemaps.dotlan.net/search?q=%3$d" target="_blank">%1$s <span class="fa fa-external-link"></span></a>',
            '/^(marketDetails)\:(\d+)$/i' => '<a href="https://eve-central.com/home/quicklook.html?typeid=%3$d" target="_blank">%1$s <span class="fa fa-external-link"></span></a>',
            '/^(requestTrust)\:(.*)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>',
            '/^(setDestination)\:(\d+)$/i' => '<a href="http://evemaps.dotlan.net/search?q=%3$d" target="_blank">%1$s <span class="fa fa-external-link"></span></a>',
            '/^(addWaypoint)\:(\d+)$/i' => '<a href="http://evemaps.dotlan.net/search?q=%3$d" target="_blank">%1$s <span class="fa fa-external-link"></span></a>',
            '/^(joinChannel)\:(.*)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>',
            '/^(joinMailingList)\:(.*)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>',
            '/^(findInContracts)\:(\d+)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>',
            '/^(addContact)\:(\d+)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>',
            '/^(addCorpContact)\:(\d+)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>',
            '/^(startConversation)\:(\d+)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>',
            '/^(showOnMap)\:(\d+)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>',
            '/^(sendMail)\:(\d+)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>', // body and subj not implemented
            '/^(bookmark)\:(\d+)$/i' => '<a href="" onclick="event.preventDefault();" title="Ingame only">%1$s</a>'
        ];
    }

    private function __clone()
    {}

    private static function getInstance()
    {
        if (self::$mInstance == null) {
            self::$mInstance = new self();
        }

        return self::$mInstance;
    }
}
