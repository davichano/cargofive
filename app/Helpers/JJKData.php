<?php

namespace App\Helpers;

use webd\language\StringDistance;

class JJKData
{
    /**
     * Groups similar data using a Jaro Winkler algorithm.
     *
     * @param array $list An array of data items.
     * @param float $threshold The minimum similarity score required to group two items together.
     * @return array An array of groups, where each group is an array of data items.
     */
    public function groupSimilarData(array $list, float $threshold): array
    {
        // Initialize arrays to store the groups and visited names
        $groups = [];
        $visited = [];
        // Iterate over each data item
        foreach ($list as $i => $item) {
            // If the item has already been visited, skip it
            if (isset($visited[$i])) continue;
            // Mark the item as visited
            $visited[$i] = true;
            // Get the neighbors of the item, I call neighbors to every item in the list with similar name
            $neighbors = $this->getNeighbors($list, $item, $threshold);
            // Create a new group with the current item
            $group = [$item];
            if (count($neighbors) < 2) {
                //if the item doesn't have similar items, insert the group to the list and continue with the next item
                $groups[] = $group;
                continue;
            }
            // Expand the cluster of the item by adding its neighbors, it's something like "get the neighbors of the neighbors"
            $this->expandCluster($list, $neighbors, $group, $visited, $threshold);
            //insert the expanded group
            $groups[] = $group;
        }
        // Return the groups
        return $groups;
    }

    /**
     *
     * Gets the neighbors of a data item.
     *
     * @param array $list
     * @param mixed $item
     * @param float $threshold
     * @return array
     */
    protected function getNeighbors(array $list, $item, float $threshold): array
    {
        // Initialize an array to store the neighbors
        $neighbors = [];
        // Iterate over each data item in the list
        foreach ($list as $i => $candidate) {
            // Check if the item is an array or a Surcharge
            if (gettype($item) == "array") {
                $currentName = $item['surcharge'];
                $candidateName = $candidate['surcharge'];
            } else {
                $currentName = $item->name;
                $candidateName = $candidate->name;
            }
            // Calculate the similarity
            $similarity = StringDistance::jaroWinkler($currentName, $candidateName);

            // If the similarity is greater than or equal to the threshold, add the item to the neighbors array
            if ($similarity >= $threshold) {
                $neighbors[] = $i;
            }
        }
        // Return the neighbors
        return $neighbors;
    }

    /**
     *
     * Expands a cluster of data items by adding its neighbors. Group and Visited are references
     *
     * @param array $list
     * @param array $neighbors
     * @param array $group
     * @param array $visited
     * @param float $threshold
     * @return void
     */
    protected function expandCluster(array $list, array $neighbors, array &$group, array &$visited, float $threshold): void
    {
        // Iterate over each neighbor
        foreach ($neighbors as $i) {
            // If the neighbor has already been visited, skip it
            if (!isset($visited[$i])) {
                // Mark the neighbor as visited
                $visited[$i] = true;
                // Get the item list by the neighbor key
                $currentItem = $list[$i];
                // Create a group
                $group[] = $currentItem;
                // Get the neighbors of the neighbor
                $currentNeighbors = $this->getNeighbors($list, $currentItem, $threshold);
                // If the neighbor has any neighbors, expand the cluster by adding them
                if (count($currentNeighbors) >= 2) {
                    $this->expandCluster($list, $currentNeighbors, $group, $visited, $threshold);
                }
            }
        }
    }
}
