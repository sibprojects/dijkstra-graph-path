<?php

namespace App\GraphPath;

use App\GraphPath\Interfaces\GraphInterface;
use App\GraphPath\Interfaces\NodeInterface;

class Graph implements GraphInterface
{
    protected $nodes = [];

    public function add(NodeInterface $node): Graph
    {
        if (array_key_exists($node->getId(), $this->getNodes())) {
            throw new \Exception('Unable to insert multiple Nodes with the same ID in a Graph');
        }
        $this->nodes[$node->getId()] = $node;
        return $this;
    }

    public function getNode($id): ?Node
    {
        $nodes = $this->getNodes();
        if (!array_key_exists($id, $nodes)) {
            throw new \Exception("Unable to find $id in the Graph");
        }
        return $nodes[$id];
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }
}