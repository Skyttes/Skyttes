<?php declare(strict_types=1);

namespace Skyttes\Core\SEO\Sitemap\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Skyttes\Core\SEO\Sitemap\SitemapGeneratorInterface;

class GenerateSitemapCommand extends Command
{
    public const DESTINATION_ARG = "destination";
    
    public const URL_ARG = "url";

    protected static $defaultName = "skyttes:sitemap:generate";

    public function __construct(
        private readonly SitemapGeneratorInterface $sitemap,
        string $name = "skyttes:sitemap:generate",
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription("Dump the current database to an SQL file.")
            ->addArgument(
                self::DESTINATION_ARG,
                InputArgument::OPTIONAL,
                "Destination for the sitemap"
            )
            ->addArgument(
                self::URL_ARG,
                InputArgument::OPTIONAL,
                "Public URL",
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $url = $input->getArgument(self::URL_ARG);
        $destination = $input->getArgument(self::DESTINATION_ARG);

        $submit = $io->confirm("Submit sitemap to Google/Yandex for indexing?");

        $this->sitemap->generate($url, $destination, $submit);

        return 0;
    }
}
