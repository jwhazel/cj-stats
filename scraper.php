<?php

/*******************
 *** BASIC USAGE ***
 *******************/

//Include the class
require_once('classes/CJstats.php');


//Load up some random articles
$article_list = [
    "https://www.courier-journal.com/story/news/2020/04/20/kentucky-schools-closed-remainder-school-year-beshear-says/5114849002/",
    "https://www.courier-journal.com/story/news/local/2020/04/20/kentucky-coronavirus-news-businesses-try-survive-gas-prices-fall/5164374002/",
    "https://www.courier-journal.com/story/entertainment/2020/04/09/watch-louisivlle-social-distancing-quartets-online-music-coronavirus-pandemic/2942238001/"
];


//Loop the article list
foreach ($article_list as $url) {

    //Create a stats instance for this article
    $stats = new CJstats($url);

    //Print all subheads
    print_r($stats->getSubHeads());

    //Print all video sources
    print_r($stats->getInlineVideos());

    //Print a count of subheads, images, and videos
    print_r($stats->getCounts());
}







/**********************
 *** ADVANCED USAGE ***
 **********************/

//What happens if we want to add custom one time functionality to the CJstats package?
//Option #1 - open CJstats.php and muddle with the code
//Option #2 - leave CJstats.php as is and extend the class like so:
class CustomStats extends CJstats
{
    public function getWordCount()
    {
        $article = $this->domParser->getElementsByTagName('article');
        $p = $article->item(0)->getElementsByTagName('p');
        $wordCount = 0;
        for ($i = 0; $i < $p->length; $i++) {
            $innerText = $p->item($i)->textContent;
            $wordCount += count(explode(" ", $innerText));
        }
        return $wordCount;
    }
}

//Now create a new instance of our custom class
$stats = new CustomStats($article_list[1]);

//The methods of the original class still work:
print_r($stats->getCounts());

//But now we have access to our new methods as well
echo "Word Count is: " . $stats->getWordCount();
