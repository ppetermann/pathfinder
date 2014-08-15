<?php
namespace PathFinder;

class AStar
{
    /**
     * @var Node[]
     */
    protected $open = [];

    /**
     * @var string[]
     */
    protected $closed = [];

    /**
     * @param Node $node
     */
    protected function addToOpen(Node $node)
    {
        if (
            isset($this->open[(string) $node])
            && $this->open[(string) $node]->getGCost() < $node->getGCost())
        {
            return;
        }
        $this->open[(string) $node] = $node;
    }

    /**
     * @param Node $node
     */
    protected function addToClosed(Node $node)
    {
        $this->closed[] = (string) $node;
    }


    public function findPath(Node $start, Node $end)
    {
        // no path if there is no need for moving
        if ($start->equals($end)) {
            return [];
        }

        $this->addToOpen($start);

        $foundTarget = false;

        while(!$foundTarget && count($this->open) > 0) {
            uasort(
                $this->open,
                function(Node $a, Node $b) use($end) {
                    if ($a->getFCost($end) == $b->getFCost($end)) return 0;
                    if ($a->getFCost($end) < $b->getFCost($end)) return -1;
                    return 1;
                }
            );

            /** @var Node $current */
            $current = array_shift($this->open);

            if ((string)$current == (string) $end) {
                $foundTarget = $current;
                // we don't need to look at its adjacents anymore,
                // as we do have our route
                continue;
            }

            $adjacent = $current->getAdjacentNodes();

            foreach ($adjacent as $node) {
                if (in_array((string) $node, $this->closed)) {
                    continue;
                }
                $node->setParent($current);
                $this->addToOpen($node);
            }

            $this->addToClosed($current);

        }

        // we failed to find a route
        if(!$foundTarget && count($this->open) == 0) {
            return [];
        }

        /** @var Node $foundTarget */
        return $this->createRouteList($foundTarget);

    }

    /**
     * @param Node $foundTarget
     * @return Node[]
     */
    protected function createRouteList(Node $foundTarget)
    {
        $route = [];
        $route[] = $foundTarget;

        while ($foundTarget = $foundTarget->getParent())
        {
            $route[] = $foundTarget;
        }
        $route = array_reverse($route);
        return $route;
    }
}
