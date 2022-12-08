<?php

namespace App\GraphPath\Interfaces;

use App\GraphPath\Node;

interface NodeInterface
{
    public function connect(NodeInterface $node, int $distance = 1): void;

    public function getConnections(): array;

    /**
     * @return mixed
     */
    public function getId();

    public function getPotential(): ?int;

    public function getPotentialFrom(): ?Node;

    public function isPassed(): bool;

    public function markPassed(): void;

    public function setPotential(int $potential, NodeInterface $from): bool;
}