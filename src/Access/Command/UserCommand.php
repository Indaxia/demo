<?php
namespace App\Access\Command;

use App\Access\Repository\UserRepository;
use App\Access\Factory\UserFactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use App\Access\Factory\UserIdentityFactoryInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use App\Access\Exception\VerificationFactorException;

class UserCommand extends Command 
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserFactoryInterface
     */
    protected $factory;

    /**
     * @var UserIdentityFactoryInterface
     */
    protected $identityFactory;

    public function __construct(
        UserRepository $repository, 
        UserFactoryInterface $factory, 
        UserIdentityFactoryInterface $identityFactory
    ) {
        parent::__construct();

        $this->repository = $repository;
        $this->factory = $factory;
        $this->identityFactory = $identityFactory;
    }

    protected function configure()
    {
        $this
            ->setName('access:user')
            ->setDescription('User management commands')
            ->addArgument('action', InputArgument::REQUIRED, 'Action: create, find, count, delete')
            ->addArgument('identity', InputArgument::OPTIONAL, '(for create, find, delete) Identity of the user. Usually an email')
            ->addArgument('authenticity', InputArgument::OPTIONAL, '(for create) Authenticity of the user. Usually a password')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, '(for find, delete) User ID. You can omit identity field when using this')
            ->addOption('criteria', null, InputOption::VALUE_REQUIRED, '(for find) Find criteria in JSON, e.g. --criteria \'{"contacts.country":"en"}\'')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $action = $input->getArgument('action');

        switch($action) {
            case 'create':
                $this->create($input, $output);
                return;
            case 'find':
                $this->find($input, $output);
                return; 
            case 'delete':
                $this->delete($input, $output);
                return; 
            case 'count':
                $this->count($input, $output);
                return; 
        }
    }

    protected function create(InputInterface $input, OutputInterface $output)
    {
        $rawIdentity = $input->getArgument('identity');
        $rawAuthenticity = $input->getArgument('authenticity');

        $output->writeln('Creating a user with the Identity: ' . $rawIdentity);

        $user = $this->factory->create((string)$rawIdentity, (string)$rawAuthenticity, true);
        $this->repository->save($user);

        $output->writeln('Done. User ID: ' . $user->getId());
    }

    protected function find(InputInterface $input, OutputInterface $output)
    {
        $rawIdentity = $input->getArgument('identity');
        $id = $input->getOption('id');
        $criteria = $input->getOption('criteria');
        if($criteria) {
            $criteria = json_decode($criteria, true);
            if(!is_array($criteria)) {
                throw new \Exception('Wrong criteria JSON syntax');
            }
        }

        $user = null;
        $users = null;

        if(is_array($criteria)) {
            $output->writeln('Trying to find by criteria' . PHP_EOL);

            $users = $this->repository->findBy($criteria);
        } elseif($id) {
            $output->writeln('Trying to find by id: ' . $id . PHP_EOL);

            $user = $this->repository->find($id);
        } else {
            $output->writeln('Trying to find by user identity: ' . $rawIdentity . PHP_EOL);

            $user = $this->repository->findByIdentity($this->identityFactory->create($rawIdentity));
        }

        if($users) {
            $output->writeln('Found, total: '.count($users).PHP_EOL);
            foreach($users as $u) {
                $output->writeln('ID: '.$u->getId().', Identifier: '.$u->getIdentity()->getIdentifier());
            }
        }

        if($user) {
            $output->writeln('User found:');
            $output->writeln('  ID: '.$user->getId());
            $output->writeln('  Identifier: '.$user->getIdentity()->getIdentifier());


            $verification = 'verified';
            try {
                $user->getVerificationState()->verify($user);
            } catch(VerificationFactorException $e) {
                $verification = $e->getMessage();
            }
            $output->writeln('  Verification: ' . $verification);

            $output->writeln('  Contacts: ');
            $contacts = $user->getContacts();
            $output->writeln('    FullName: ' . $contacts->getFullName());
            $output->writeln('    Country: ' . $contacts->getCountry());
            $output->writeln('    Email: ' . $contacts->getEmail());
            $output->writeln('    Phone: ' . $contacts->getPhone());
            
            $output->writeln('  Settings: ');
            $contacts = $user->getSettings();

            $output->writeln('  Banned at: ' . ($user->getBannedAt() ? $user->getBannedAt()->format('c') : '(not banned)'));
            $output->writeln('  Ban reason: ' . ($user->getBanReason() ?: '(no ban reason)'));

            $roles = $user->getRoles();
            if($roles) {
                $output->writeln('    Roles: ');
                foreach($roles as $role) {
                    $output->writeln('      ' . (string)$role);
                }
            } else {
                $output->writeln('    Roles: (no roles)');
            }

            $output->writeln('');

            return $user;
        }

        $output->writeln('User not found.');
    }

    protected function delete(InputInterface $input, OutputInterface $output)
    {
        $user = $this->find($input, $output);

        if($user) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Delete the user? (y/n) ', false);

            if (!$helper->ask($input, $output, $question)) {
                $output->writeln(PHP_EOL.'Action aborted.');
                return;
            }

            $this->repository->remove($user);

            $output->writeln(PHP_EOL.'User removed.');
        }
    }

    protected function count(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(PHP_EOL.'Total user count: ' . $this->repository->allCount());
    }
}