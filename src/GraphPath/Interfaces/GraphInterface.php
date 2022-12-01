<?php

namespace App\GraphPath\Interfaces;

use App\GraphPath\Graph;
use App\GraphPath\Node;

interface GraphInterface
{
    public function add(NodeInterface $node): Graph;

    public function getNode($id): ?Node;

    public function getNodes(): array;
}