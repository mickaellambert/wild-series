<?php

namespace App\Test;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Service\ProgramDuration;
use PHPUnit\Framework\TestCase;
 
class ProgramDurationTest extends TestCase
{
    public function testOnlyMinutes()
    {
        $programDuration = new ProgramDuration();

        $program = new Program();
        $season = new Season();
        $episode = new Episode();

        $episode->setDuration(25);
        $season->addEpisode($episode);
        $program->addSeason($season);

        $this->assertEquals([0, 0, 0, 25], $programDuration->calculate($program));
    }

    public function testMoreThan60Minutes()
    {
        $programDuration = new ProgramDuration();

        $program = new Program();
        $season = new Season();
        
        $episode1 = new Episode();
        $episode2 = new Episode();

        $episode1->setDuration(25);
        $episode2->setDuration(55);
        
        $season->addEpisode($episode1);
        $season->addEpisode($episode2);

        $program->addSeason($season);

        $this->assertEquals([0, 1, 20], $programDuration->calculate($program));
    }
}