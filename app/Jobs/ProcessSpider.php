<?php

namespace App\Jobs;

use App\Models\ProcessPage;
use App\Models\Resource;
use App\Models\Search;
use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

class ProcessSpider implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     /**
     * Client to make request
     * 
     * @var App\Models\ProcessPage
     */
    private $processPage;

    /**
     * 
     */
    public $url;

    /**
     * Website configuration
     * 
     * @var App\Models\WebsiteConfiguration
     */
    public $websiteConfiguration;

    /**
     * Website configuration
     * 
     * @var App\Models\Search
     */
    public $search;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, Search $search)
    {
        $this->url = $url;
        $this->search = $search;
        $this->websiteConfiguration = $this->search->website->webConfiguration;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $resource = Resource::where('url', '=', $this->url)->first();

        if(!is_null($resource)){
            return null;
        }
        
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        
        $link = $crawler->getUri();
        $tagTitle = $crawler->filter($this->websiteConfiguration->tag_resource_title);
        
        if($tagTitle->count() == 0)
            throw new InvalidArgumentException("{$this->websiteConfiguration->tag_resource_title} esta mal configurado");

        $title = $tagTitle->text();
        $description = '';

        $descriptionResult = $crawler->filter($this->websiteConfiguration->tag_resource_description)->each(function(Crawler $node){
            return $node->text();
        });

        $shortDescription = '';
        foreach($descriptionResult as $key => $value) {
            
            if(trim($value) != ''){
                
                if($shortDescription === ''){
                    $shortDescription = substr($value, 0, 164);
                }
                
                $description .= "<p>{$value}</p>";
            }

        }
                
        $resource = [
            'title'             =>  $title,
            'short_description' =>  $shortDescription,
            'description'       =>  $description,
            'url'               =>  $link,
            'resource_type_id'  =>  1,
            'search_id'         =>  $this->search->id
        ]; 

        Resource::create($resource);
    }
}
