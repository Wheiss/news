<?php
/**
 * Created by https://github.com/Wheiss
 * Date: 05.11.2018
 * Time: 15:43
 */

namespace AppBundle\Services\News;


use AppBundle\Entity\NewsItem;
use AppBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Manager
{
    use ContainerAwareTrait;

    private $tags = [];
    private $query = null;
    private $doctrine = null;
    private $paginator = null;
    private $repo = null;
    private $router = null;

    public function __construct($doctrine, $paginator, $router)
    {
        $this->doctrine = $doctrine;
        $this->paginator = $paginator;
        $this->tags = new ArrayCollection();
        $newsRepo = $doctrine->getRepository(NewsItem::class);
        $this->query = $newsRepo->createQueryBuilder('news');
        $this->repo = $newsRepo;
        $this->router = $router;
    }

    /**
     * Adds filter for tags
     * @param array $tags
     * @return $this
     */
    public function addTagsFilter(array $tags)
    {
        $this->tags = $tags;

        $tagsRepo = $this->doctrine->getRepository(Tag::class);
        $tagsCollection = $tagsRepo->findBy(['name' => $tags]);
        $tagsIds = array_map(function($tag) {
            return $tag->getId();
        }, $tagsCollection);

        $this->query->innerJoin('news.tags', 'tags');
        $this->query->where('tags.id in (:tags)');
        $this->query->setParameter('tags', $tagsIds);
        return $this;
    }

    /**
     * Gets paginator for news
     * @param int $page
     * @return mixed
     */
    public function getPaginator($page = 1){
        $paginator = $this->paginator;

        $newsPaginator = $paginator->paginate($this->query, $page, 10);
        return $newsPaginator;
    }

    public function getItemPublicData($newsItem, $forShow = false)
    {
        $newsData = [
            'name' => $newsItem->getName(),
            'date' => $newsItem->getDate()->format('j.M.y'),
            'link' => $this->router->generate('news-show', [
                'id' => $newsItem->getId(),
            ])
        ];

        if($forShow) {
            $imageInstance = $newsItem->getImage();
            if($imageInstance) {
                $newsData['image'] = $imageInstance->getUrl();
            }
            $tags = $newsItem->getTags();

            $newsData['tags'] = [];
            foreach ($tags as $tag) {
                $newsData['tags'][] = $tag->getName();
            }
            $newsData['text'] = $newsItem->getText();
        }

        return $newsData;
    }
}