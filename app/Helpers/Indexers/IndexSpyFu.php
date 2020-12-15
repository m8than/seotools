<?php
namespace App\Helpers\Indexers;

class IndexSpyFu extends Indexer {
    protected $pingUrl = 'https://www.spyfu.com/overview/domain?query={url}';
}
?>