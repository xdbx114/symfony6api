<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;

#[AsCommand(
    name: 'getdata',
    description: 'Add a short description for your command',
)]
class GetdataCommand extends Command
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = HttpClient::create(); 

        $postsResponse = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts');
        $usersResponse = $client->request('GET', 'https://jsonplaceholder.typicode.com/users');
    
        $posts = $postsResponse->toArray();
        $users = $usersResponse->toArray();
        foreach ($posts as $postData) {
            $post = new Post();
            $post->setTitle($postData['title']);
            $post->setBody($postData['body']);
            $userId = $postData['userId'];
            foreach ($users as $userData) {
                if ($userData['id'] === $userId) {
                    $explodedName = explode(' ', $userData['name']);
                    $post->setFirstName($explodedName[0]);
                    $post->setLastName($explodedName[1]);
                }
            }
    
            $this->entityManager->persist($post);
        }
    
        $this->entityManager->flush();
    
        $output->writeln('Posty zostały pobrane i zapisane.');
    
        return Command::SUCCESS;
    }
}
