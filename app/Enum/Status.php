<?php

namespace App\Enum;

enum Status: string
{
    case PLANNING = 'Planning';
    case IN_PROGRESS = 'In Progress';
    case ON_HOLD = 'On Hold';
    case COMPLETED = 'Completed';

    /**
     * Optional helper to display human-readable labels or badges.
     */
    public function label(): string
    {
        return $this->value;
    }
}