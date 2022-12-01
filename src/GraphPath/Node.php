<?php

namespace App\GraphPath;

use App\GraphPath\Interfaces\NodeInterface;

class Node implements NodeInterface
{
    protected $id;
    protected $potential;
    protected $potentialFrom;
    protected $connections = [];
    protected $passed = false;

    /**
     * @param mixed $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param Node $node
     * @param integer $distance
     */
    public function connect(NodeInterface $node, $distance = 1): void
    {
        $this->connections[$node->getId()] = $distance;
    }

    public function getDistance(NodeInterface $node): array
    {
        return $this->connections[$node->getId()];
    }

    public function getConnections(): array
    {
        return $this->connections;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPotential(): ?int
    {
        return $this->potential;
    }

    public function getPotentialFrom(): ?Node
    {
        return $this->potentialFrom;
    }

    public function isPassed(): bool
    {
        return $this->passed;
    }

    public function markPassed(): void
    {
        $this->passed = true;
    }

    /**
     * @param integer $potential
     * @param Node $from
     */
    public function setPotential(int $potential, NodeInterface $from): bool
    {
        $potential = ( int )$potential;
        if (!$this->getPotential() || $potential < $this->getPotential()) {
            $this->potential = $potential;
            $this->potentialFrom = $from;
            return true;
        }
        return false;
    }
}