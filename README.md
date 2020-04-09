
Sherlock
================================================================================

```php
<?php

use Sherlock\Result;
use Sherlock\ResultSet;
use Sherlock\Searcher\Structure;
use Sherlock\SearchFilter;
use Sherlock\Sherlock;

$content = $this->getArticle();

$searchFilter = SearchFilter::create()->handleRequest();
if ($searchFilter->getCurrent()) {
    
    $content = '';
    if (strlen(trim($searchFilter->getCurrent())) >= 3) {
        // mind. drei Zeichen
        $content = Sherlock::create()
            ->addSearch(new Structure())
            ->search();
    }

    if ($content == '') {
        // Nichts gefunden
        $resultSet = new ResultSet();
        $resultSet->setHeading('Watson: "Eindrucksvoll. Wirklich eindrucksvoll."');

        $result = new Result();
        $result->setText('
            Sherlock: "Das bekomme ich selten zu hören."
            Watson: "Was bekommen Sie sonst zu hören?"
            Sherlock: "Verpiss dich."
        ');
        $resultSet->addResult($result);
        $content = $resultSet->render();
    }
}

echo $content;
```


```php
<?php

use Sherlock\SearchFilter;

$searchFilter = SearchFilter::create()->handleRequest();

echo sprintf(
    '<form action="%s" method="get">
        <input name="q" value="%s" type="text" placeholder="Sherlock: Stagnation macht meinen Geist rebellisch! Geben Sie mir Probleme, geben Sie mir Arbeit!" />
    </form>', 
    rex_getUrl(), 
    rex_escape($searchFilter->getCurrent())
);
