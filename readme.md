# Open Calais Tags #

Open Calais Tags is a PHP class for extracting entities and tags from text using [Open Calais](http://www.opencalais.com). Calais performs semantic analysis of the text, using natural language processing to identify concepts like people, companies and technologies discussed in the text. These are especially useful for suggesting tags for your content such as website articles or blog posts. You could even automatically tag archived content that would take days to go through manually.

Calais is free for both personal and commercial use, and usage of this class requires a Calais API key. Getting an API key is an easy, automated process. Just click the "Request API Key" link at the top of their site.

## Usage ##

Basic usage is simple. Create an instance of the class with your API key, and call the getEntities method using your content string.

    require('opencalais.php');
    $oc = new OpenCalais('your-api-key');
    $entities = $oc->getEntities($content);

### Example input ###

> April 7 (Bloomberg) . Yahoo! Inc., the Internet company that snubbed a $44.6 billion takeover bid from Microsoft Corp., may drop in Nasdaq trading after the software maker threatened to cut its bid if directors fail to give in soon.
> If Yahoo.s directors refuse to negotiate a deal within three weeks, Microsoft plans to nominate a board slate and take its case to investors, Chief Executive Officer Steve Ballmer said April 5 in a statement. He suggested the deal.s value might decline if Microsoft has to take those steps.
> The ultimatum may send Yahoo Chief Executive Officer Jerry Yang scrambling to find an appealing alternative for investors to avoid succumbing to Microsoft, whose bid was a 62 percent premium to Yahoo.s stock price at the time. The deadline shows Microsoft is in a hurry to take on Google Inc., which dominates in Internet search, said analysts including Canaccord Adams.s Colin Gillis.

### Example output ###

    Array
    (
        [IndustryTerm] => Array
            (
                [0] => Internet
                [1] => software maker
                [2] => Internet search
            )
        [Person] => Array
            (
                [0] => Steve Ballmer
                [1] => Jerry Yang
                [2] => Colin Gillis
            )
        [Company] => Array
            (
                [0] => Google Inc.
                [1] => Canaccord Adams
                [2] => Yahoo!
                [3] => Microsoft Corp.
            )
        [Currency] => Array
            (
                [0] => USD
            )
        [SocialTag] => Array
            (
                [0] => New encyclopedism
                [1] => Microsoft
                [2] => Jerry Yang
                [3] => Steve Ballmer
                [4] => Bing
                [5] => Yahoo!
                [6] => Internet search engines
                [7] => Hypertext
            )
    )


## Optional Settings ##

A number of settings exist which can be changed through public properties of the OpenCalais object: `contentType` (default: text/html), `outputFormat` (default: application/json). Refer to the OpenCalais documentation for more information.

This code is distributed under the MIT license. See http://www.opensource.org/licenses/mit-license.php
