<?php
namespace App\Helpers\Indexers;

class IndexMoz extends Indexer {
    protected $pingUrl = 'https://moz.com/domain-analysis?site={url}';
}
?>