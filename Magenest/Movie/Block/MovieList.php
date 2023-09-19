<?php

namespace Magenest\Movie\Block;

use Magento\Framework\View\Element\Template;
use Magenest\Movie\Model\MovieFactory;

class MovieList extends Template
{
    private $movieFactory;

    public function __construct(
        Template\Context $context,
        MovieFactory     $movieFactory,
        array            $data = [])
    {
        parent::__construct($context, $data);
        $this->movieFactory = $movieFactory;
    }

    public function getMovies()
    {
        $movies = $this->movieFactory->create()->getCollection();
        $sql = $movies->addFieldToSelect('name')
            ->addFieldToSelect('description')
            ->addFieldToSelect('rating')
            ->join(
                'magenest_director',
                'main_table.director_id = magenest_director.director_id',
                ['director' => 'name']
            )->join(
                'magenest_movie_actor',
                'main_table.movie_id = magenest_movie_actor.movie_id',
                null
            )
            ->join(
                'magenest_actor',
                'magenest_movie_actor.actor_id = magenest_actor.actor_id',
                ['actor' => 'name']
            )
            ->setOrder('name','asc')->getData();

        $debug = '';
        return $movies;
    }

}
