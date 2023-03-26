<?php

namespace App;

use Illuminate\Support\Collection;

class Inspiring {
    public static function quote() : string
    {
        return Collection::make([
            "There are a million paths in this world...\nbut any Sage will tell you they can all be reduced to one.\nImprove yourself.",
            "When you're alone, first look for a weapon.",
            "The Dragon Advances.",
            "Verbal response not required for calculation corrections.",
            "No, that can't be right. That's ridiculous. Spoon. Get out of here with your nonsense words.",
            "If I have to choose between disappointing you or my disciple...well, I'm sorry, but I don't like you very much.",
            "The only thing worse than a victory in battle is defeat.",
            "How has no one killed you yet?\nSheer Laziness.",
            "When you saw everything, you usually had to pretend you didn't. It was more polite that way",
            "While Honor has never won a single battle,\nDeceit wins wars.",
            "Sleepwalking was a terrible curse.",
            "So what if he turned all shoes into ducks?",
            "They folded clothes, dusted shelves, polished swords; except for the periodic murder attempts, they were perfect hosts."
        ])->random();
    }
}
