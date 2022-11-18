<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

use Goutte\Client;
use Illuminate\Support\Facades\Log;
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

        $query = $this->websiteConfiguration->query_search_variable.str_replace(" ", $this->websiteConfiguration->query_separator, $this->search->query);
        $url = $this->website->url.$query;

        $crawler = $this->client->request('GET', $url);

        
        $this->parsePosts($crawler);
    }

    /**
     * 
     */
    private function parsePosts(Crawler $crawler)
    {
        
        // dd($crawler->filter("#p_lt_ctl05_pageplaceholder_p_lt_ctl02_SmartSearchDialogWithResults_srchResults_pnlSearchResults > div"));

        $crawler->filter($this->websiteConfiguration->tag_resource_list_posts)->each(function($node){
            $this->parsePost($node);
        });

        Storage::append("scrapy.txt", "________________________________\n");
        
        if(
            $this->websiteConfiguration->tag_resource_next_page !== " "
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
    private function parsePost(Crawler $crawler)
    {

        try{
        
            $title = $crawler->filter($this->websiteConfiguration->tag_resource_title)->first()->text();
            $link = $crawler->filter($this->websiteConfiguration->tag_resource_link)->first()->link()->getUri();
            
            $nodeDescription = $crawler->filter($this->websiteConfiguration->tag_resource_description);

            if($nodeDescription->count() > 0){

                $description = $nodeDescription->first()->text();

                $resource = Resource::where('url', '=', $link)->first();
                
                if(is_null($resource)){
                    
                    Resource::create([
                        'title'             =>  $title,
                        'description'       =>  $description,
                        'url'               =>  $link,
                        'resource_type_id'  =>  1,
                        'search_id'         =>  1
                    ]);
                    
                }
                Storage::append("scrapy.txt", json_encode(["title" => $title, "subtitle" => $link, "description" => $description]));
            }
            
        }catch(\InvalidArgumentException $e){
            
            Storage::append("error_scrapy.log", "Error...");
        }
    }
}