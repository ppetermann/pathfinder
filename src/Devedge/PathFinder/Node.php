<?php
namespace Devedge\PathFinder;

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
     * @todo replace with an own method
     * @return string
     */
    abstract public function __toString();
}
