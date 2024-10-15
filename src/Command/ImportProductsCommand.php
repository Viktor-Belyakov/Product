<?php

namespace App\Command;

use App\Controller\ProductMessageController;
use App\Service\ProductParserService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:import-products',
    description: 'Обработка файла',
    aliases: ['app:import-products'],
    hidden: false
)]
class ImportProductsCommand extends Command
{
    protected static $defaultName = 'app:import:products';

    public function __construct(
        private readonly MessageBusInterface $messageBus,
    )
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Обрабатываем и отправляем для сохранения файл')
            ->addArgument('filePath', InputArgument::REQUIRED);
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('filePath');

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \RuntimeException('Файл не найден или недоступен для чтения.');
        }

        try {
            $parsedProducts = ProductParserService::parseProducts($filePath);
            $request = Request::createFromGlobals();
            $request->request->set('parsedProducts', $parsedProducts);

            (new ProductMessageController())->sendParsedProducts($request, $this->messageBus);
        }  catch (Exception $e) {
            $output->writeln('<error>Произошла ошибка: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
