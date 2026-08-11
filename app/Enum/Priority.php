<?php

namespace App\Enum;

enum Priority: string
{
    case LOW = 'Low';
    case MEDIUM = 'Medium';
    case HIGH = 'High';

    /**
     * Optional helper to display human-readable labels or badges.
     */
    public function label(): string
    {
        return $this->value;
    }
}