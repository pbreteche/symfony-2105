<?php

namespace App\Service;

use App\Repository\PostRepository;

class PostSearcher
{
    /**
     * @var \App\Repository\PostRepository
     */
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }


    /**
     * @return \App\Entity\Post[]
     */
    public function search(?string $keyword): array
    {
        $posts = [];

        if ($keyword) {
            $posts = $this->postRepository->findByKeywordName($keyword);
        }

        return $posts;
    }
}