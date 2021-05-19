<?php

namespace App\Service;

interface PostSearcherInterface
{
    public function search(?string $keyword): array;
}