<?php

namespace App\Service;
use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): array
    {
        $minutes = 0;

        foreach ($program->getSeasons() as $season)
        {
            foreach ($season->getEpisodes() as $episode) {
                $minutes += $episode->getDuration();
            }
        }

        $hours = floor($minutes / 60);
        $days = floor($hours / 24);
        $h = $hours - ($days * 24);
        $min = $minutes - ($hours * 60);

        return [$days, $h, $min];
    }
}