<?php

namespace App;

use Illuminate\Support\Collection;

class Inspiring
{
    public static function quote(): string
    {
        return Collection::make([
            // Cradle
            "There are a million paths in this world...\nbut any Sage will tell you they can all be reduced to one.\nImprove yourself.",
            "When you're alone, first look for a weapon.",
            'The Dragon Advances.',
            "You're asking me, but who am I supposed to ask?",
            'Verbal response not required for calculation corrections.',
            "No, that can't be right. That's ridiculous. Spoon. Get out of here with your nonsense words.",
            "If I have to choose between disappointing you or my disciple...well, I'm sorry, but I don't like you very much.",
            "How has no one killed you yet?\nSheer Laziness.",
            "When you saw everything, you usually had to pretend you didn't. It was more polite that way",
            // Travelers Gate
            'The only thing worse than a victory in battle is defeat.',
            'They folded clothes, dusted shelves, polished swords; except for the periodic murder attempts, they were perfect hosts.',
            // Elder Empire
            'Sleepwalking was a terrible curse.',
            'So what if he turned all shoes into ducks?',
            "While Honor has never won a single battle,\nDeceit wins wars.",
            // Last Horizon
            'To survive in this galaxy, you need a wand in one hand and a gun in the other.',
            "Would you like to compete for control of\nThe Last Horizon?",
        ])->random();
    }
}
