<?php
namespace App\Helpers\Indexers;

use Exception;

class IndexWorthOfWeb extends Indexer {
    protected $pingUrl = 'https://www.worthofweb.com/website-value/{url}';
}
?>