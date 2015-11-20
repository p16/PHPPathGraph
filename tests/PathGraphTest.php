<?php

use Sort\DTO\Edge;
use Sort\PathGraph;

class PathGraphTest extends PHPUnit_Framework_TestCase
{
    public function testOrderOneEdgesWillReturnTheSameEdge()
    {
        $pathGraph = new PathGraph();
        $edge1 = new Edge('Dubai', 'abu dhabi', 'description');

        $edges = [$edge1];

        $sortedEdges = $pathGraph->sort($edges);

        $this->assertCount(1, $sortedEdges);
        $this->assertInstanceOf('Sort\DTO\Edge', $sortedEdges[0]);

        $this->assertEquals('Dubai', $sortedEdges[0]->getFrom());
        $this->assertEquals('abu dhabi', $sortedEdges[0]->getTo());
    }

    public function testOrderTwoEdgesWillReturnThemInOrderWhenAPathIsFound()
    {
        $pathGraph = new PathGraph();
        $edge1 = new Edge('Dubai', 'abu dhabi', 'description');
        $edge2 = new Edge('doha', 'Dubai', 'description');

        $edges = [$edge1, $edge2];

        $sortedEdges = $pathGraph->sort($edges);

        $this->assertInstanceOf('Sort\DTO\Edge', $sortedEdges[0]);
        $this->assertInstanceOf('Sort\DTO\Edge', $sortedEdges[1]);

        $this->assertEquals('doha', $sortedEdges[0]->getFrom());
        $this->assertEquals('Dubai', $sortedEdges[0]->getTo());
        $this->assertEquals('Dubai', $sortedEdges[1]->getFrom());
        $this->assertEquals('abu dhabi', $sortedEdges[1]->getTo());
    }

    public function testOrderSevenEdgesWillReturnThemInOrderWhenAPathIsFound()
    {
        $pathGraph = new PathGraph();
        $edge1 = new Edge('abu dhabi', 'rome', 'description');
        $edge2 = new Edge('london', 'new york', 'description');
        $edge3 = new Edge('doha', 'abu dhabi', 'description');
        $edge4 = new Edge('Dubai', 'doha', 'description');
        $edge5 = new Edge('Rome', 'paris', 'description');
        $edge6 = new Edge('new york', 'dallas', 'description');
        $edge7 = new Edge('paris', 'London', 'description');

        $edges = [$edge1, $edge2, $edge3, $edge4, $edge5, $edge6, $edge7];

        $sortedEdges = $pathGraph->sort($edges);

        $this->assertEquals('Dubai', $sortedEdges[0]->getFrom());
        $this->assertEquals('doha', $sortedEdges[0]->getTo());
        $this->assertEquals('doha', $sortedEdges[1]->getFrom());
        $this->assertEquals('abu dhabi', $sortedEdges[1]->getTo());
        $this->assertEquals('abu dhabi', $sortedEdges[2]->getFrom());
        $this->assertEquals('rome', $sortedEdges[2]->getTo());
        $this->assertEquals('Rome', $sortedEdges[3]->getFrom());
        $this->assertEquals('paris', $sortedEdges[3]->getTo());
        $this->assertEquals('paris', $sortedEdges[4]->getFrom());
        $this->assertEquals('London', $sortedEdges[4]->getTo());
        $this->assertEquals('london', $sortedEdges[5]->getFrom());
        $this->assertEquals('new york', $sortedEdges[5]->getTo());
        $this->assertEquals('new york', $sortedEdges[6]->getFrom());
        $this->assertEquals('dallas', $sortedEdges[6]->getTo());
    }

    public function testOrder200EdgesWillReturnThemInOrderWhenAPathIsFound()
    {
        $numberOfEdges = 200;
        $edges = [];
        for ($index = 1; $index <= $numberOfEdges; $index++) {
            $edges[] = new Edge('node' . $index, 'node' . ($index + 1), 'description');
        }
        shuffle($edges);

        $pathGraph = new PathGraph();

        $sortedEdges = $pathGraph->sort($edges);

        $this->assertCount($numberOfEdges, $sortedEdges);
        foreach($sortedEdges as $key => $edge) {
            $this->assertEquals('node' . ($key + 1), $edge->getFrom());
            $this->assertEquals('node' . ($key + 2), $edge->getTo());
        }
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Found duplicated edge that starts from: dubai
     */
    public function testOrderTwoEdgesStartingFromTheSamePointWillThrowAnException()
    {
        $pathGraph = new PathGraph();
        $edge1 = new Edge('dubai', 'abu dhabi', 'description');
        $edge2 = new Edge('dubai', 'doha', 'description');

        $edges = [$edge1, $edge2];

        $sortedEdges = $pathGraph->sort($edges);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage There are more than one possible starting points
     */
    public function testOrderThreeEdgesWhereTwoOfThemRepresentAStartingPointWillThrowAnException()
    {
        $pathGraph = new PathGraph();
        $edge1 = new Edge('dubai', 'abu dhabi', 'description');
        $edge2 = new Edge('doha', 'dubai', 'description');
        $edge3 = new Edge('rome', 'dubai', 'description');

        $edges = [$edge1, $edge2, $edge3];

        $sortedEdges = $pathGraph->sort($edges);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage The given edges form a cycle, no starting point can be automatically selected
     */
    public function testOrderThreeEdgesThatFormACycleWillThrowAnException()
    {
        $pathGraph = new PathGraph();
        $edge1 = new Edge('dubai', 'abu dhabi', 'description');
        $edge2 = new Edge('doha', 'dubai', 'description');
        $edge3 = new Edge('abu dhabi', 'doha', 'description');

        $edges = [$edge1, $edge2, $edge3];

        $sortedEdges = $pathGraph->sort($edges);
    }
}
