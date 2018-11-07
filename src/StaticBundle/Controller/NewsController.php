<?php

namespace StaticBundle\Controller;

use AppBundle\Entity\NewsItem;
use AppBundle\Repository\NewsItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends Controller
{
    /**
     * @Route("/")
     */
    public function listAction(Request $request)
    {
        $newsData = $this->forward('AjaxBundle\Controller\NewsController::listAction', [], $request->query->all())->getContent();
        $newsData = trim($newsData, '"');
        return $this->render('StaticBundle:News:list.html.twig', array(
            'newsData' => $newsData,
        ));
    }

    /**
     * @Route("/show/{id}", requirements={"id"="\d+"}, name="news-show")
     */
    public function showAction($id)
    {
        $newsRepo = $this->getDoctrine()->getRepository(NewsItem::class);
        $newsItem = $newsRepo->findOneBy(['id' => $id]);
        $newsManager = $this->get('news_manager');
        $newsItemJson = json_encode($newsManager->getItemPublicData($newsItem, true));

        return $this->render('StaticBundle:News:show.html.twig', array(
            'newsItem' => $newsItemJson,
        ));
    }

}
