<?php

namespace App\Command;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CleanOrderTableCommand extends Command
{
    protected static $defaultName = 'app:clean-order-table';
    protected static $defaultDescription = 'Delete inactive orders';
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }
    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription(self::$defaultDescription)
            ->setHelp('This command delete inactive order in database each day.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orders = $this->em->getRepository(Order::class)->findAll();

        $currentTime = new \DateTime();
        foreach ($orders as $order) {
            $orderDateTime = $order->getCreatedAt()->modify('+1 day');
            if (!$order->getIsActive()) {
                if ($currentTime > $orderDateTime){
                    $this->em->remove($order);
                }
            }
        }
        $this->em->flush();

        return Command::SUCCESS;
    }
}
