<?php
namespace App\Helpers\Indexers;

use Exception;

abstract class Indexer {
    protected $pingUrl;
    protected $userAgent;
    protected $options;
        
    public function __construct()
    {
        $this->options = [
            'http'=>[
                'method'=>"GET",
                'timeout' => 20,
                'header'=>"Accept-language: en\r\n" .
                            "User-Agent: " . $this->userAgent . "\r\n"
            ]
        ];
        $this->userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36 OPR/68.0.3618.104';
    }

    public function index($url): bool
    {
        $url = $this->transformUrl($url);
          
        $context = stream_context_create($this->options);
        try {
            file_get_contents(str_replace('{url}', $url, $this->pingUrl), false, $context);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

    public function transformUrl($url): string
    {
        return strtr($url, [
            'https://' => '',
            'http://' => ''
        ]);
    }
}
?>