<?php

namespace AjaxBundle\Controller;

use AppBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TagsController extends Controller
{
    /**
     * @Route("/tags-list", name="ajax-tags-list")
     */
    public function listAction(Request $request)
    {

        $name = $request->query->get('name', '');
        if($name) {
            $doctrine = $this->getDoctrine();
            $tagsRepo = $doctrine->getRepository(Tag::class);
            $queryBuilder = $tagsRepo->createQueryBuilder('tags');
            $queryBuilder = $queryBuilder->where('tags.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');

            $tags = $queryBuilder->getQuery()->getResult();
            $tagsData = [];

            foreach ($tags as $tag) {
                $tagsData[] = ['name' => $tag->getName()];
            }
        }

        return $this->json($tagsData ?? []);
    }

}
