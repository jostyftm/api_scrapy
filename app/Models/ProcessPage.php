<?php

namespace App\Models;

use Goutte\Client;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

class ProcessPage
{
    /**
     * Client to make request
     * 
     * @var Goutte\Client|null
     */
    private $client;

    /**
     * Search
     * 
     * @var App\Models\Search
     */
    private $search;

    /**
     * Website configuration
     * 
     * @var App\Models\WebsiteConfiguration
     */
    private $websiteConfiguration;

    /**
     * 
     */
    protected $url;

    public function __construct($url, Search $search)
    {
        $this->client = new Client();
        $this->search = $search;
        $this->websiteConfiguration = $this->search->website->webConfiguration;
        $this->url = $url;
    }

    public function run()
    {
        $crawlerPost = $this->client->request('GET', $this->url);
        
        return $this->parsePost($crawlerPost);
    }

    /**
     * 
     */
    private function parsePost(Crawler $crawler, $save = true)
    {
        $link = $crawler->getUri();
        $tagTitle = $crawler->filter($this->websiteConfiguration->tag_resource_title);
        
        if($tagTitle->count() == 0)
            throw new InvalidArgumentException("{$this->websiteConfiguration->tag_resource_title} esta mal configurado");

        $title = $tagTitle->text();
        $description = '';

        $descriptionResult = $crawler->filter($this->websiteConfiguration->tag_resource_description)->each(function(Crawler $node){
            return $node->text();
        });

        foreach($descriptionResult as $value) {
            $description .= "<p>{$value}</p>";
        }
                
        $resource = [
            'title'             =>  $title,
            'description'       =>  $description,
            'url'               =>  $link,
            'resource_type_id'  =>  1,
            'search_id'         =>  $this->search->id
        ]; 
        
        return $resource;
    }
}
