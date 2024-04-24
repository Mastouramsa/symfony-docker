<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-articles',
    description: 'Ajouter un article',
)]
class CreateArticlesCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nbArticles', InputArgument::REQUIRED, 'Nombre d\'article')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nbArticles = $input->getArgument('nbArticles');

        
        if ($nbArticles < 1){
            $io->warning('Erreur au niveau du nombre d\'article ' . $nbArticles);

            return Command::FAILURE;
        }

        for ($compteur = 0; $compteur < $nbArticles; $compteur++){
            $io->Comment('Creation article ' . $compteur);
            $article = new Article();
            $article->setTitle("Article numéro : " . $compteur);
            $article->setText("Article Description");
            $article->setDate(new \DateTime());
            $article->setAuthor("Mastoura");
            $this->entityManager->persist($article);
        }

        $this->entityManager->flush();
        $io->success($compteur . ' articles crées !!!' );

        return Command::SUCCESS;
    }
}
