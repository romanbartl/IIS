<?php

namespace App\Presenters;


use App\Model\AlbumsManager;
use App\Model\ConcertsManager;
use App\Model\FestivalsManager;

class NewsPresenter extends BasePresenter
{

    /**
     * @var AlbumsManager
     */
    private $albumsManager;


    /**
     * @var
     */
    private $concertsManager;


    /**
     * @var FestivalsManager
     */
    private $festivalsManager;


    /**
     * NewsPresenter constructor.
     * @param AlbumsManager $albumsManager
     * @param ConcertsManager $concertsManager
     * @param FestivalsManager $festivalsManager
     */
    public function __construct(AlbumsManager $albumsManager, ConcertsManager $concertsManager,
                                FestivalsManager $festivalsManager)
    {
        parent::__construct();
        $this->albumsManager = $albumsManager;
        $this->concertsManager = $concertsManager;
        $this->festivalsManager = $festivalsManager;
    }


    public function renderDefault()
    {
        $this->template->albumsNews = $this->albumsManager->getAlbumsNews();
        $this->template->concertsNews = $this->concertsManager->getConcertsNews();
        $this->template->festivalsNews = $this->festivalsManager->getFestivalsNews();

        $sliderShow = array();
        $limit = 3;

        $concerts = false;
        $festivals = false;
        $albums = false;


        $slideConcerts = $this->concertsManager->getNewsConcertsSliderPages(3);

        if ($festCnt = count($slideConcerts) != 0) {
            $concerts = true;
            $sliderShow[0] = $slideConcerts->fetchAll()[0];
        }

        $slideFestivals = $this->festivalsManager->getNewsFestivalsSliderPages(3);

        if ($concertCnt = count($slideFestivals) != 0) {
            $festivals = true;
            $sliderShow[1] = $slideFestivals->fetchAll()[0];
        }

        $slideAlbums = $this->albumsManager->getNewsAlbumsSliderPages(3);

        if ($albCnt = count($slideAlbums) != 0) {
            $albums = true;
            $sliderShow[2] = $slideAlbums->fetchAll()[0];
        }

        if ($festCnt + $concertCnt + $albCnt == 0) {
            $sliderShow[0] = $this->concertsManager->getAllConcertsWithTickets()->fetchAll()[0];
            $sliderShow[1] = $this->concertsManager->getAllConcertsWithTickets()->fetchAll()[0];
            $sliderShow[2] = $this->festivalsManager->getAllFestivalsWithTickets()->fetchAll()[0];

        } else {
            //TODO
        }

        $this->template->slideShow = $sliderShow;
    }
}
