<?php

namespace App\Entity\Classes\GraphPath\Interfaces;

use App\Entity\Classes\GraphPath\Graph;
use App\Entity\Classes\GraphPath\Node;

interface GraphInterface
{
    public function add(NodeInterface $node): Graph;

    public function getNode($id): ?Node;

    public function getNodes(): array;
}