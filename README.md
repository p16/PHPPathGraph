# Sorting edges into a path graph without cycles

The idea is to have a method that will accept a set of unordered edges, and will return them ordered so that each edge is traversed.

## Input

The input of the function is an array of Edge instances (see src/DTO/Edge.php);


## Output

The has the same format as the input, but sorted :)


## Running the tests

You need to have [composer installed](https://getcomposer.org/download/).

Once you have composer you need to run

```
cd /path/to/source && composer install
```

and then

```
./vendor/bin/phpunit -c .
```

## Performances

The algorithm to order the edges is of linear complexity O(n).

The unordered edges array is scanned 4 times:

- to add metadata to each edge
- calculate the In-degree of the nodes
- select the starting point
- pick the next ordered edge

