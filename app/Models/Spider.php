<?php

namespace App\Models;

use App\Jobs\ProcessPost;
use App\Jobs\ProcessSpider;
use Illuminate\Support\Facades\Storage;

use Goutte\Client;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

class Spider 
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
     * Resource website to extract data
     * 
     * @var App\Models\Website
     */
    private $website;
    
    /**
     * Website configuration
     * 
     * @var App\Models\WebsiteConfiguration
     */
    private $websiteConfiguration;


    /**
     * 
     */
    public function __construct(Search $search)
    {
        $this->client = new Client();
        $this->search = $search;
        $this->website = $search->website;
        $this->websiteConfiguration = $this->website->webConfiguration;        
    }


    /**
     * 
     */
    public function run()
    {
        $crawler = $this->client->request('GET', $this->buildUrl($this->search->query));

        $this->parsePosts($crawler);
    }

    /**
     * 
     */
    public function runTest($query)
    {
        $crawler = $this->client->request('GET', $this->buildUrl($query));

        $resourcesTag = $crawler->filter($this->websiteConfiguration->tag_resource_list_posts);

        if($resourcesTag->count() == 0)
            throw new InvalidArgumentException("{$this->websiteConfiguration->tag_resource_list_posts} esta mal configurado");
            
        $resources = $crawler->filter($this->websiteConfiguration->tag_resource_list_posts)->each(function(Crawler $node){
            
            $tagLink = $node->filter($this->websiteConfiguration->tag_resource_link);

            if($tagLink->count() == 0)
                throw new InvalidArgumentException("{$this->websiteConfiguration->tag_resource_link} esta mal configurado");

            $linkA = $tagLink->first()->link()->getUri();

            $crawlerPost = $this->client->request('GET', $linkA);

            return $this->parsePost($crawlerPost, false);
        });

        return $resources;
    }


    /**
     * 
     */
    private function buildUrl($query)
    {
        $queryBuild = $this->websiteConfiguration->query_search_variable.str_replace(" ", $this->websiteConfiguration->query_separator, $query);
        $url = $this->website->url.$queryBuild;

        return $url;
    }

    /**
     * 
     */
    private function parsePosts(Crawler $crawler)
    {   
        $s = $this->search;
        $links = $crawler->filter($this->websiteConfiguration->tag_resource_list_posts)->each(function(Crawler $node){

            $linkA = $node->filter($this->websiteConfiguration->tag_resource_link)->first()->link()->getUri();

            return $linkA;
        });

        foreach($links AS $key => $link){
            dispatch(new ProcessSpider($link, $this->search));
        }
        
        if(
            $this->websiteConfiguration->tag_resource_next_page !== " " &&
            $this->websiteConfiguration->tag_resource_next_page !== null
        ){

            $nextPage = $crawler->filter($this->websiteConfiguration->tag_resource_next_page);
            
            if($nextPage->count()){
    
                $newUri = $nextPage->attr('href');
                $crawler = $this->client->request('GET', $newUri);
    
                $this->parsePosts($crawler);
            }
        }
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