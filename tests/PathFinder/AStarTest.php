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

use PHPUnit_Framework_TestCase;

/**
 * Class AStarTest
 *
 * @todo add a test where there is no middle field
 * @package PathFinder
 */
class AStarTest extends PHPUnit_Framework_TestCase
{

    protected $field1x1;
    protected $field2x1;
    protected $field3x1;
    protected $field1x2;
    protected $field2x2;
    protected $field3x2;
    protected $field1x3;
    protected $field2x3;
    protected $field3x3;

    protected function setUp()
    {
        // create 3x3 fields
        $this->field1x1 = new TestNode('field1x1', 1, 1);
        $this->field2x1 = new TestNode('field2x1', 2, 1);
        $this->field3x1 = new TestNode('field3x1', 3, 1);

        $this->field1x2 = new TestNode('field1x2', 1, 2);
        $this->field2x2 = new TestNode('field2x2', 2, 2);
        $this->field3x2 = new TestNode('field3x2', 3, 2);

        $this->field1x3 = new TestNode('field1x3', 1, 3);
        $this->field2x3 = new TestNode('field2x3', 2, 3);
        $this->field3x3 = new TestNode('field3x3', 3, 3);

        // set adjacent for each field
        // row 1
        $this->field1x1->adjacentNodes = [
            $this->field1x2,
            $this->field2x1
        ];

        $this->field1x2->adjacentNodes = [
            $this->field1x1,
            $this->field1x3,
            $this->field2x2
        ];

        $this->field1x3->adjacentNodes = [
            $this->field1x2,
            $this->field2x3
        ];

        // row 2
        $this->field2x1->adjacentNodes = [
            $this->field1x1,
            $this->field3x1,
            $this->field2x2
        ];

        $this->field2x2->adjacentNodes = [
            $this->field1x2,
            $this->field3x2,
            $this->field2x1,
            $this->field2x3
        ];

        $this->field2x3->adjacentNodes = [
            $this->field2x2,
            $this->field1x3,
            $this->field3x3
        ];

        // row 3
        $this->field3x1->adjacentNodes = [
            $this->field2x1,
            $this->field3x2,
        ];

        $this->field3x2->adjacentNodes = [
            $this->field2x2,
            $this->field3x1,
            $this->field3x3
        ];

        $this->field3x3->adjacentNodes = [
            $this->field2x3,
            $this->field2x2,
        ];

    }

    public function test1x1To3x3()
    {
        $pathfinder = new AStar();
        $path = $pathfinder->findPath($this->field1x1, $this->field3x3);
        $this->assertCount(5, $path);

        $this->assertInstanceOf('\PathFinder\TestNode', $path[0]);
        $this->assertInstanceOf('\PathFinder\TestNode', $path[1]);
        $this->assertInstanceOf('\PathFinder\TestNode', $path[2]);
        $this->assertInstanceOf('\PathFinder\TestNode', $path[3]);
        $this->assertInstanceOf('\PathFinder\TestNode', $path[4]);

        $this->assertEquals('field1x1', (string)$path[0]);
        $this->assertEquals('field1x2', (string)$path[1]);
        $this->assertEquals('field2x2', (string)$path[2]);
        $this->assertEquals('field2x3', (string)$path[3]);
        $this->assertEquals('field3x3', (string)$path[4]);

    }

    public function test1x3To1x1()
    {
        $pathfinder = new AStar();
        $path = $pathfinder->findPath($this->field1x3, $this->field1x1);
        $this->assertCount(3, $path);

        $this->assertInstanceOf('\PathFinder\TestNode', $path[0]);
        $this->assertInstanceOf('\PathFinder\TestNode', $path[1]);
        $this->assertInstanceOf('\PathFinder\TestNode', $path[2]);

        $this->assertEquals('field1x3', (string)$path[0]);
        $this->assertEquals('field1x2', (string)$path[1]);
        $this->assertEquals('field1x1', (string)$path[2]);

    }
}

class TestNode extends Node
{
    public $id;
    public $adjacentNodes = [];
    public $x;
    public $y;

    public function __construct($id, $x, $y)
    {
        $this->id = $id;
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int|float
     */
    public function getOwnCost()
    {
        return 1;
    }

    public function getHCost(Node $target)
    {
        $data = $target->getDataForH();

        return sqrt(pow(($data['x'] - $this->x), 2) + pow($data['y'] - $this->y, 2));
    }

    public function getAdjacentNodes()
    {
        return $this->adjacentNodes;
    }

    public function equals(Node $compareTo)
    {
        return (string)$this == (string)$compareTo;
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    public function getDataForH()
    {
        return ['x' => $this->x, 'y' => $this->y];
    }
}
