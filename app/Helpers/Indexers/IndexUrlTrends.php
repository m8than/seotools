<?php
namespace App\Helpers\Indexers;

use Exception;

class IndexUrlTrends extends Indexer {
    protected $pingUrl = 'https://www.urltrends.com/rank/{url}';
}
?>