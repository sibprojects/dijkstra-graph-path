<?php

namespace App\GraphPath;

class Dijkstra
{
    protected $startingNode = false;
    protected $endingNode = false;
    protected $graph;
    protected $paths = [];
    protected $solution = false;
    protected $direction = 'straight';

    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    public function getDistance(): int
    {
        if (!$this->isSolved()) {
            throw new \Exception("Cannot calculate the distance of a non-solved algorithm:\nDid you forget to call ->solve()?");
        }
        return $this->getEndingNode()->getPotential();
    }

    public function getEndingNode(): Node
    {
        return $this->endingNode;
    }

    private function getShortestPath(): array
    {
        $path = [];
        $node = $this->getEndingNode();
        while ($node instanceof Node && $node->getId() != $this->getStartingNode()->getId()) {
            $path[] = $node;
            $node = $node->getPotentialFrom();
        }
        $path[] = $this->getStartingNode();
        return $this->getDirection() == 'straight' ? array_reverse($path) : $path;
    }

    public function getStartingNode(): Node
    {
        return $this->startingNode;
    }

    public function setEndingNode(Node $node): void
    {
        $this->endingNode = $node;
    }

    public function setStartingNode(Node $node): void
    {
        $this->paths[] = [$node];
        $this->startingNode = $node;
    }

    public function solve(): array
    {
        if (!$this->getStartingNode() instanceof Node) {
            throw new \Exception("Cannot solve the algorithm without starting node");
        }
        if (!$this->getEndingNode() instanceof Node) {
            throw new \Exception("Cannot solve the algorithm without ending node");
        }

        $this->calculatePotentials($this->getStartingNode());
        $this->solution = $this->getShortestPath();

        if (count($this->solution) == 2 && !isset($this->solution[0]->getConnections()[$this->solution[1]->getId()])) {
            $startingNode = $this->getStartingNode();
            $this->setStartingNode($this->getEndingNode());
            $this->setEndingNode($startingNode);
            $this->calculatePotentials($this->getStartingNode());
            $this->setDirection('reverse');
            $this->solution = $this->getShortestPath();
        }

        return $this->solution;
    }

    protected function calculatePotentials(Node $node)
    {
        $connections = $node->getConnections();
        $sorted = array_keys($connections);
        krsort($sorted);
        foreach ($connections as $id => $distance) {
            $v = $this->getGraph()->getNode($id);
            $v->setPotential($node->getPotential() + $distance, $node);
            foreach ($this->getPaths() as $path) {
                $count = count($path);
                if ($path[$count - 1]->getId() === $node->getId()) {
                    $this->paths[] = array_merge($path, [$v]);
                }
            }
        }
        $node->markPassed();

        foreach ($sorted as $id) {
            $node = $this->getGraph()->getNode($id);
            if (!$node->isPassed()) {
                $this->calculatePotentials($node);
            }
        }
    }

    protected function getGraph(): Graph
    {
        return $this->graph;
    }

    protected function getPaths(): array
    {
        return $this->paths;
    }

    protected function isSolved(): bool
    {
        return ( bool )$this->solution;
    }

    public function setDirection(string $direction): void
    {
        $this->direction = $direction;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}