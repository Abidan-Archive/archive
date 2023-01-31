<?php

namespace App;

use Illuminate\Support\Collection;

class Inspiring {
    public static function quote() : string
    {
        return Collection::make([
            "There are a million paths in this world...\nbut any sage will tell you they can all be reduced to one.\nImprove yourself.",
            "When you're alone, first look for a weapon.",
            "The Dragon Advances.",
            "Verbal response not required for calculation corrections."
        ])->random();
    }
}
