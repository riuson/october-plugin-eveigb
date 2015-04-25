# october-plugin-eveigb
Some methods for EVE Online in-game browser

Parser for links in pages for EVE Online in-game browser.

Some [link examples](https://github.com/riuson/october-plugin-eveigb/blob/master/linkstest.htm):

{{ evelink('showinfo:3426', 'CPU Management') }} <br>
{{ evelink('preview:597', 'Show Preview (Punisher)') }}<br>

Link parsed differently depending on the order in which the browser page opens.
  * If this is the in-game browser, used j[avascript functions of in-game browser](https://wiki.eveonline.com/en/wiki/IGB_Javascript_Methods).
  * If it is a normal browser, links, if possible, are replaced by links to external resources, what provides similar information.
