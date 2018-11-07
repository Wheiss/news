<?php

namespace AjaxBundle\Controller;

use AppBundle\Entity\NewsItem;
use AppBundle\Services\News\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewsController
 * @package AjaxBundle\Controller
 */
class NewsController extends Controller
{
    /**
     * @Route("/list", name="ajax-news-list")
     */
    public function listAction(Request $request)
    {

        $newsManager = $this->get('news_manager');
        $tags = $request->query->get('tags');
        if (!empty($tags) && is_array($tags)) {
            $newsManager->addTagsFilter($tags);
        }

        $page = $request->query->get('page', 1);
        $newsPaginator = $newsManager->getPaginator($page);
        $newsData = [];

        foreach ($newsPaginator as $newsItem) {
            $newsData[] = $newsManager->getItemPublicData($newsItem);
        }

        return $this->json(
            [
                'news' => $newsData,
                'paginatorData' => [
                    'totalPages' => $newsPaginator->getPageCount(),
                    'currentPage' => $page,
                    'url' => $this->generateUrl("ajax-news-list"),
                ],
                'tagsData' => [
                    'url' => $this->generateUrl("ajax-tags-list"),
                ],
            ]
        );
    }

}
