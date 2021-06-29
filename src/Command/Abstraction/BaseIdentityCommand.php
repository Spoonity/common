<?php


namespace Spoonity\Command\Abstraction;


use Spoonity\Exception;
use Spoonity\Service\AccountsService;
use Spoonity\Service\IdentityService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseIdentityCommand
 * @package Spoonity\Command\Abstraction
 */
abstract class BaseIdentityCommand extends BaseCommand
{
    /** @var IdentityService  */
    private $identityService;

    /**
     * BaseIdentityCommand constructor.
     * @param ContainerInterface $container
     * @param AccountsService $accountsService
     * @param IdentityService $identityService
     */
    public function __construct(ContainerInterface $container, AccountsService $accountsService, IdentityService $identityService)
    {
        $this->identityService = $identityService;

        parent::__construct($container, $accountsService);
    }

    public function configure()
    {
        $pieces = explode(':', $this->getCronCommand());

        if(is_array($pieces)) {
            $executionName = [];

            for($i=0; $i<sizeof($pieces); $i++) {
                if($i === 1) {
                    $executionName[] = 'identity';
                }

                $executionName[] = $pieces[$i];
            }

            $this->setName(implode(':', $executionName));
        }

        parent::configure();
    }

    /**
     * @return string
     */
    protected abstract function getCronCommand(): string;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $token = $this->getContainer()->getParameter('temp_auth_key');
        $startTime = hrtime(true);
        $page = 0;

        /**
         * iterate over each identity.
         */
        do {
            $page++;

            try {
                $identities = $this->identityService->getIdentities($token, $page);

            } catch(\Exception $e) {
                throw new Exception\UnknownErrorException('Error connecting to Identity service');
            }

            if(
                !isset($identities['items']) ||
                !isset($identities['paging'])
            ) {
                throw new Exception\UnknownErrorException('Invalid response format from Identity service');
            }

            /**
             * foreach identity, connect to database and process schedules.
             */
            foreach($identities['items'] as $identity) {
                $credentials = $this->identityService->getCredentials($identity['id'], $token, IdentityService::CREDENTIAL_TYPE_WAREHOUSE);

                $output->write(sprintf("Running reporting:process-schedule for %s(%d): ",
                    $identity['name'],
                    $identity['id']
                ));

                if(
                    !is_array($credentials) ||
                    !isset($credentials['items']) ||
                    empty($credentials['items'])
                ) {
                    $output->writeln(sprintf("<warning>Error: %d</warning>", 'No warehouse credentials'));

                    continue;
                }

                $credentials = $credentials['items'][0];

                $databaseUrl = sprintf("mysql://%s:%s@%s:%d/%s?serverVersion=5.7",
                    $credentials['username'],
                    $credentials['password'],
                    (getenv('KUBERNETES_SERVICE_HOST') != null) ? $credentials['proxy_ip'] : $credentials['hostname'],
                    $credentials['port'],
                    $credentials['database_name']
                );

                putenv(sprintf("DATABASE_URL=%s", $databaseUrl));

                exec($this->getCronCommand(), $result, $exitCode);

                if($exitCode !== 0) {
                    $output->writeln(sprintf("<error>Error: %d</error>", $exitCode));

                    continue;
                }

                $output->writeln("<info>Success</info>");
            }

        } while($identities['paging']['next'] !== null);

        $endTime = hrtime(true);

        $output->writeln(sprintf("Schedules processed, took %d seconds",
            round((($endTime - $startTime) / 1000000000), 4)
        ));

        return 0;
    }
}