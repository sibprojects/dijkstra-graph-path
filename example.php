<?php

use App\GraphPath\Dijkstra;

class GraphPathCalcBasedOnWorkflow
{
    public function getPath(string $fromState, string $toState, StateMachine $workflow): array
    {
        return $this->shortestPath = $this->getShortestPath($fromState, $toState, $workflow);
    }

    private function getWorkflowRoutes($workflow): array
    {
        $routes = [];

        $transitions = $workflow->getDefinition()->getTransitions();
        foreach ($transitions as $transition) {
            $routes[] = [
                'from'  => $transition->getFroms()[0],
                'to'    => $transition->getTos()[0],
                'price' => 1,
            ];
        }

        return $routes;
    }

    public function getShortestPath($fromStatus, $toStatus, $workflow): array
    {
        $routes = $this->getWorkflowRoutes($workflow);
        $graph = new Graph();
        foreach ($routes as $route) {
            $from = $route['from'];
            $to = $route['to'];
            $price = $route['price'];
            if (!array_key_exists($from, $graph->getNodes())) {
                $from_node = new Node($from);
                $graph->add($from_node);
            } else {
                $from_node = $graph->getNode($from);
            }
            if (!array_key_exists($to, $graph->getNodes())) {
                $to_node = new Node($to);
                $graph->add($to_node);
            } else {
                $to_node = $graph->getNode($to);
            }
            $from_node->connect($to_node, $price);
        }

        $dijkstra = new Dijkstra($graph);
        $dijkstra->setStartingNode($graph->getNode($fromStatus));
        $dijkstra->setEndingNode($graph->getNode($toStatus));

        $solution = $dijkstra->solve();

        return [
            'path'      => $solution,
            'direction' => $dijkstra->getDirection(),
        ];
    }
}
