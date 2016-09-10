<?php

namespace AppBundle\Manager;

/**
 * User Survey Manager
 */
class NewsManager
{
    protected $httpManager;
    
    /**
     * Constructor
     *
     * @param Doctrine $doctrine - Doctrine
     *
     */
    public function __construct($httpManager)
    {
        $this->httpManager = $httpManager;
    }

    /**
    * news fetcher and randomly extractor
    */
    public function getNews()
    {
        $newsResponse = $this->httpManager->get('https://newsapi.org/v1/articles?source=the-hindu&sortBy=top&apiKey=6c49f421cea94f32b019038f489ca845');
        $articles = isset($newsResponse['content']['articles']) ? $newsResponse['content']['articles'] : array();
        $article = array_rand($articles);
        
        return isset($articles[$article]['description']) ? $articles[$article]['description'] : "Information Not Available.";
    }
}
