<?php declare(strict_types=1);

namespace Skyttes\Doctrine\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DumpToSqlCommand extends Command
{
    public const DESTINATION_ARG = "destination";

    public const EXECUTABLE_ARG = "executable";

    protected static $defaultName = "skyttes:db:dump";

    public function __construct(
        private readonly array $database,
        string $name = "skyttes:db:dump"
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
                "Destination for the SQL file",
            )
            ->addArgument(
                self::EXECUTABLE_ARG,
                InputArgument::OPTIONAL,
                "Path to the mysqldump executable",
                "mysqldump"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $destination = $input->getArgument(self::DESTINATION_ARG);
        $executable = $input->getArgument(self::EXECUTABLE_ARG);

        $username = $this->database["user"];
        $password = $this->database["password"];
        $database = $this->database["dbname"];

        $cmd =
            "$executable -u $username --password=$password $database --ignore-table=$database.doctrine_migrations > $destination" .
            PHP_EOL;

        $io->comment($cmd);
        exec($cmd, $out, $statusCode);

        if ($statusCode !== 0) {
            $io->error("Failed to execute the dump command.");
        }

        $io->writeln($out);

        return $statusCode;
    }
}
