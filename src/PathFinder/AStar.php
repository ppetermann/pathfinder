<?php
/*
 MIT License
 Copyright (c) 2014 Peter Petermann

 Permission is hereby granted, free of charge, to any person
 obtaining a copy of this software and associated documentation
 files (the "Software"), to deal in the Software without
 restriction, including without limitation the rights to use,
 copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the
 Software is furnished to do so, subject to the following
 conditions:

 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 OTHER DEALINGS IN THE SOFTWARE.
*/
namespace PathFinder;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class AStar
 *
 * @package PathFinder
 */
class AStar implements LoggerAwareInterface
{
    /**
     * we use the trait here, which will provide a setLogger method, and a logger member
     */
    use LoggerAwareTrait;

    /**
     * @var Node[]
     */
    protected $open = [];

    /**
     * @var string[]
     */
    protected $closed = [];

    /**
     * find a path from Node $start to Node $end
     * @param Node $start
     * @param Node $end
     * @return array|Node[]
     */
    public function findPath(Node $start, Node $end)
    {
        // no path if there is no need for moving
        if ($start->equals($end)) {
            $this->getLogger()->debug("$start equals $end, route is empty");
            return [];
        }

        $this->addToOpen($start);

        $foundTarget = false;

        while (!$foundTarget && count($this->open) > 0) {
            $this->getLogger()->debug("sorting open nodes by cost. Node count:" . count($this->open));
            uasort(
                $this->open,
                function (Node $a, Node $b) use ($end) {
                    if ($a->getFCost($end) == $b->getFCost($end)) {
                        return 0;
                    }
                    if ($a->getFCost($end) < $b->getFCost($end)) {
                        return -1;
                    }

                    return 1;
                }
            );

            /** @var Node $current */
            $current = array_shift($this->open);

            $this->getLogger()->debug("current node selected: " . $current);

            if ((string)$current == (string)$end) {
                $foundTarget = $current;
                // we don't need to look at its adjacents anymore,
                // as we do have our route
                $this->getLogger()->debug("current node is target node, exciting loop");
                continue;
            }

            $adjacent = $current->getAdjacentNodes();

            foreach ($adjacent as $node) {
                if (in_array((string)$node, $this->closed)) {
                    $this->getLogger()->debug("skipping adjacent: $node as it was already processed");
                    continue;
                }
                $node->setParent($current);
                $this->addToOpen($node);
            }
            $this->addToClosed($current);

        }

        // we failed to find a route
        if (!$foundTarget && count($this->open) == 0) {
            $this->getLogger()->debug("no open nodes left, and no target found.");
            return [];
        }

        $this->getLogger()->debug("found route!");
        /** @var Node $foundTarget */
        return $this->createRouteList($foundTarget);

    }

    /**
     * @param Node $node
     */
    protected function addToOpen(Node $node)
    {
        if (
            isset($this->open[(string)$node])
            && $this->open[(string)$node]->getGCost() < $node->getGCost()
        ) {
            $this->getLogger()->debug("skipping add, $node is already known and gcost <= new path");
            return;
        }
        $this->getLogger()->debug("adding new open node: $node");
        $this->open[(string)$node] = $node;
    }

    /**
     * @param Node $node
     */
    protected function addToClosed(Node $node)
    {
        $this->getLogger()->debug("adding $node to closed");
        $this->closed[] = (string)$node;
    }

    /**
     * @param Node $foundTarget
     * @return Node[]
     */
    protected function createRouteList(Node $foundTarget)
    {
        $route = [];
        $route[] = $foundTarget;

        while ($foundTarget = $foundTarget->getParent()) {
            $route[] = $foundTarget;
        }
        $route = array_reverse($route);

        return $route;
    }

    /**
     * return a logger
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLogger()
    {
        // as no one has set a logger, we use Psr's null logger here
        // as a default behaviour
        if (is_null($this->logger)) {
            $this->setLogger(new \Psr\Log\NullLogger());
        }
        return $this->logger;
    }
}
