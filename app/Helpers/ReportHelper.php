<?php

namespace App\Helpers;

class ReportHelper
{
    /**
     * Convert numeric score to predicate based on standard intervals.
     * Assuming KKM is 75.
     * 
     * @param float|null $score
     * @return string
     */
    public static function calculatePredicate($score)
    {
        if ($score === null || $score === '') return '-';
        
        $score = (float) $score;
        
        if ($score >= 93) return 'A';
        if ($score >= 84) return 'B';
        if ($score >= 75) return 'C';
        return 'D';
    }

    /**
     * Get description for a predicate.
     * 
     * @param string $predicate
     * @return string
     */
    public static function getPredicateDescription($predicate)
    {
        switch (strtoupper($predicate)) {
            case 'A': return 'Sangat Baik';
            case 'B': return 'Baik';
            case 'C': return 'Cukup';
            case 'D': return 'Perlu Bimbingan';
            default: return '-';
        }
    }
}
