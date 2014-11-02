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

abstract class Node
{
    /**
     * @var Node|bool
     */
    protected $parent = false;

    /**
     * @param Node $parent
     */
    public function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Node|bool
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return int|float
     */
    public function getGCost()
    {
        if (!$this->parent) {
            return $this->getOwnCost();
        }

        return $this->parent->getGCost() + $this->getOwnCost();
    }

    /**
     * @param Node $target
     * @return float|int
     */
    public function getFCost(Node $target)
    {
        return $this->getGCost() + $this->getHCost($target);
    }

    /**
     * @return int|float
     */
    abstract public function getOwnCost();

    /**
     * @param Node $target
     * @return int|float
     */
    abstract public function getHCost(Node $target);

    /**
     * @return Node[]
     */
    abstract public function getAdjacentNodes();

    /**
     * @param Node $compareTo
     * @return bool
     */
    abstract public function equals(Node $compareTo);

    /**
     * should return a unique string for this
     *
     * @return string
     */
    abstract public function __toString();

    /**
     * this method should allow a node
     * to get the data from the target node getHostCost requires for its heuristic (if needed)
     *
     * @return array
     */
    public function getDataForH()
    {
        return [];
    }
}
