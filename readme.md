# CJ Stats

A proof of concept PHP framework for creating a stats scraper library for legacy Gannett articles.

Instantiation:
* `new CJstats($url)` - $url is the full url of the article

Current available methods:
* `getHeadline()`
* `getSubHeads()`
* `getInlineImages()`
* `getInlineVideos()`
* `getStats()` - an assosiative array of headline, subhead text, image sources, and video sources
* `getCounts()` - an associative array with the counts of subheads, images, and videos

Note the plurality of the method determines whether it returns a singleton or an array. The class can easily be extended to provide more functionality by either adding more methods to primary class or by extending the class in your code.

Check `scraper.php` for examples.