<?php

namespace Sort;

use Sort\DTO\Edge;

class PathGraph
{
    private $processedEdges = [];

    /**
     * This sort function will accept an array of Edge instances
     * and will return them sorted so that they form
     * a Path Graph https://en.wikipedia.org/wiki/Path_graph
     *
     * This function will:
     *
     * 1. construct an array such as the follwing:
     *     [
     *         [
     *             'edge'          => // Edge instance
     *             'to'            => // string that indicates the end of the edge
     *             'incomingEdges' => // 0 by default
     *         ],
     *         ...
     *     ]
     *
     * 2. for each of the elements of the new array,
     * it will look if there are incoming edges and set
     * the `incomingEdges` field to the right value
     *
     * 3. check that there is one and only one edge with no incoming edges
     *
     * 4. order the edges starting from the edge with no incoming edges
     *
     * @param  array  $edges array of Edge instances
     * @return array         array of Edge instances
     * @throws \Exception
     */
    public function sort(array $edges)
    {
        $this->processedEdges = [];
        foreach ($edges as $edge) {
            $this->addMetaInformation($edge);
        }

        foreach ($this->processedEdges as $processedEdge) {
            $to = strtolower($processedEdge['edge']->getTo());
            if (isset($this->processedEdges[$to])) {
                $this->processedEdges[$to]['incomingEdges'] += 1;
            }
        }

        $start = array_filter($this->processedEdges, function($edge) {
            return $edge['incomingEdges'] === 0;
        });

        if (count($start) === 0) {
            throw new \Exception("The given edges form a cycle, no starting point can be automatically selected");
        }

        if (count($start) > 1) {
            throw new \Exception("There are more than one possible starting points");
        }

        $start = array_pop($start);

        return $this->orderEdges($start['edge']);
    }

    /**
     * Given an Edge instance, this function will look for its next edge
     * and will add it to the ordered edges array.
     *
     * It will keep going until it finds a `next` edge.
     *
     * @param  Edge   $edge
     * @return null
     */
    public function orderEdges(Edge $edge)
    {
        $found = true;
        $orderedEdges[] = $edge;

        while ($found) {
            if (array_key_exists(strtolower($edge->getTo()), $this->processedEdges)) {
                $nextEdge       = $this->processedEdges[strtolower($edge->getTo())];
                $orderedEdges[] = $nextEdge['edge'];
                $edge           = $nextEdge['edge'];

                continue;
            }

            $found = false;
        }

        return $orderedEdges;
    }

    /**
     * This function will add an array of the following format to the `processedEdges` array:
     *
     * '{$edge->getFrom}' => [
     *     'edge'          => $edge, // Edge instance
     *     'to'            => $edge->getTo() // string that indicates the end of the edge
     *     'incomingEdges' => 0
     * ]
     *
     * The `incomingEdges` and `to` field will be used by the ordering algorithm.
     *
     * @param Edge $edge [description]
     */
    protected function addMetaInformation(Edge $edge)
    {
        if (isset($this->processedEdges[strtolower($edge->getFrom())])) {
            throw new \Exception("Found duplicated edge that starts from: " . $edge->getFrom());
        }

        $this->processedEdges[strtolower($edge->getFrom())]['edge'] = $edge;
        $this->processedEdges[strtolower($edge->getFrom())]['to'] = strtolower($edge->getTo());
        $this->processedEdges[strtolower($edge->getFrom())]['incomingEdges'] = 0;
    }
}
