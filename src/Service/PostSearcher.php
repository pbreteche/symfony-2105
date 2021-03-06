<?php

namespace App\Service;

use App\Repository\PostRepository;

class PostSearcher implements PostSearcherInterface
{
    /**
     * @var \App\Repository\PostRepository
     */
    private $postRepository;

    public function __construct(
        PostRepository $postRepository,
        string $adminEmail
    ) {
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