<?php

namespace App\Helpers;

use webd\language\StringDistance;

class JJKData
{
    public function groupSimilarData(array $list, float $threshold): array
    {
        // Initialize arrays to store the groups and visited names
        $groups = [];
        $visited = [];
        foreach ($list as $i => $item) {
            if (isset($visited[$i])) continue;
            $visited[$i] = true;
            $neighbors = $this->getNeighbors($list, $item, $threshold);
            $group = [$item];
            if (count($neighbors) < 2) {
                $groups[] = $group;
                continue;
            }
            $this->expandCluster($list, $neighbors, $group, $visited, $threshold);
            $groups[] = $group;
        }
        return $groups;
    }

    protected function getNeighbors(array $list, $item, float $threshold): array
    {
        $neighbors = [];
        foreach ($list as $i => $candidate) {
            if (gettype($item) == "array") {
                $currentName = $item['surcharge'];
                $candidateName = $candidate['surcharge'];
            } else {
                $currentName = $item->name;
                $candidateName = $candidate->name;
            }
            $similarity = StringDistance::jaroWinkler($currentName, $candidateName);
            if ($similarity >= $threshold) {
                $neighbors[] = $i;
            }
        }
        return $neighbors;
    }

    protected function expandCluster(array $list, array $neighbors, array &$group, array &$visited, float $threshold): void
    {
        foreach ($neighbors as $i) {
            if (!isset($visited[$i])) {
                $visited[$i] = true;
                $currentItem = $list[$i];
                $group[] = $currentItem;

                $currentNeighbors = $this->getNeighbors($list, $currentItem, $threshold);

                if (count($currentNeighbors) >= 2) {
                    $this->expandCluster($list, $currentNeighbors, $group, $visited, $threshold);
                }
            }
        }
    }
}
