<?php

namespace App\Models;

trait ExcludeSystemFillable
{
    /**
     * Attributes to exclude from fillable.
     *
     * @var array
     */
    protected $commonExcludes = [
        'uuid',
        'creator_id',
    ];

    /**
     * Get the fillable attributes, excluding system attributes.
     *
     * @param bool $from_search_repo
     * @return array
     */
    public function getFillable($from_search_repo = false)
    {
        $fillable = parent::getFillable(); // Get the original fillable attributes.

        if (!$from_search_repo) return $fillable;

        // Remove systemFillable attributes from the fillable array.
        $fillable = array_diff($fillable, $this->systemFillable ?? []);

        // Remove common excluded attributes from the fillable array.
        $fillable = array_diff($fillable, $this->commonExcludes);

        return $fillable;
    }
}
