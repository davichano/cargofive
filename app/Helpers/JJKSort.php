<?php

namespace App\Helpers;

use App\Models\Surcharge;
use Webd\Language\StringDistance;

class JJKSort
{
    public function groupSimilarSurcharges(array $surcharges, float $threshold): array
    {
        // Initialize arrays to store the groups and visited names
        $groups = [];
        $visited = [];

        foreach ($surcharges as $i => $surcharge) {
            if (isset($visited[$i])) {
                continue;
            }

            $visited[$i] = true;
            $neighbors = $this->getNeighbors($surcharges, $surcharge, $threshold);

            if (count($neighbors) < 2) {
                continue;
            }

            $group = [$surcharge];
            $this->expandCluster($surcharges, $neighbors, $group, $visited, $threshold);
            $groups[] = $group;
        }

        return $groups;
    }

    protected function getNeighbors(array $surcharges, Surcharge $surcharge, float $threshold): array
    {
        $neighbors = [];

        foreach ($surcharges as $i => $candidate) {
            $similarity = StringDistance::jaroWinkler($surcharge->name, $candidate->name);
            if ($similarity >= $threshold) {
                $neighbors[] = $i;
            }
        }
        return $neighbors;
    }

    protected function expandCluster(array $surcharges, array $neighbors, array &$group, array &$visited, float $threshold)
    {
        foreach ($neighbors as $i) {
            if (!isset($visited[$i])) {
                $visited[$i] = true;
                $currentSurcharge = $surcharges[$i];
                $group[] = $currentSurcharge;

                $currentNeighbors = $this->getNeighbors($surcharges, $currentSurcharge, $threshold);

                if (count($currentNeighbors) >= 2) {
                    $this->expandCluster($surcharges, $currentNeighbors, $group, $visited, $threshold);
                }
            }
        }
    }
}
