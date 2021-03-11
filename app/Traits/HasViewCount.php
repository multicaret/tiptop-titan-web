<?php

namespace App\Traits;


trait HasViewCount
{
    /**
     * Increment views
     *
     * @return int
     */
    public function viewed()
    {
        if ( ! session()->get("is_{$this->getTable()}_{$this->id}_viewed")) {
            $this->timestamps = false;
            $this->increment('view_count');
            $this->timestamps = true;
            session()->put("is_{$this->getTable()}_{$this->id}_viewed", true);
        }

        return $this->views;
    }
}
