<?php

namespace App\Commands\Create\Location;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Location\Area;
use App\Repository\Location\AreaRepository;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'create:location:area')]
class CreateAreaCommand extends Command
{
    public function __construct(private AreaRepository $areaRepository, private EntityManagerInterface $entityManagerInterface)
    {
        parent::__construct();
    }

    private static array $areas = [[1,'01','Guadeloupe','guadeloupe'],[2,'02','Martinique','martinique'],[3,'03','Guyane','guyane'],[4,'04','La Réunion','la reunion'],[5,'06','Mayotte','mayotte'],[6,'11','Île-de-France','ile de france'],[7,'24','Centre-Val de Loire','centre val de loire'],[8,'27','Bourgogne-Franche-Comté','bourgogne franche comte'],[9,'28','Normandie','normandie'],[10,'32','Hauts-de-France','hauts de france'],[11,'44','Grand Est','grand est'],[12,'52','Pays de la Loire','pays de la loire'],[13,'53','Bretagne','bretagne'],[14,'75','Nouvelle-Aquitaine','nouvelle aquitaine'],[15,'76','Occitanie','occitanie'],[16,'84','Auvergne-Rhône-Alpes','auvergne rhone alpes'],[17,'93','Provence-Alpes-Côte d\'Azur','provence alpes cote dazur'],[18,'94','Corse','corse'],[19,'COM','Collectivités d\'Outre-Mer','collectivites doutre mer']];
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach(CreateAreaCommand::$areas as $area)
        {
            if (sizeof($this->areaRepository->findBy(['name' => $area[1]])) < 1) {
                $newArea = new Area();
                $newArea->setNumber($area[1]);
                $newArea->setName($area[2]);

                $this->entityManagerInterface->persist($newArea);
            }
        }
        $this->entityManagerInterface->flush();

        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}