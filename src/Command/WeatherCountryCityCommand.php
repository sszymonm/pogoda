<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:country-city',
)]
class WeatherCountryCityCommand extends Command
{
    private WeatherUtil $weatherUtil;
    private LocationRepository $locationRepository;

    public function __construct(WeatherUtil $weatherUtil, LocationRepository $locationRepository)
    {
        $this->weatherUtil = $weatherUtil;
        $this->locationRepository = $locationRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED, 'Kod kraju (np. PL)')
            ->addArgument('city', InputArgument::REQUIRED, 'Nazwa miasta (np. Warszawa)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $country = $input->getArgument('country');
        $cityName = $input->getArgument('city');

        $location = $this->locationRepository->findOneBy([
            'country' => $country,
            'city' => $cityName,
        ]);

        if (!$location) {
            $io->error(sprintf('Nie znaleziono lokalizacji: %s, %s', $cityName, $country));
            return Command::FAILURE;
        }

        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));

        foreach ($measurements as $measurement) {
            $io->writeln(sprintf(
                "\t%s: %s°C, Pressure: %s hPa, Humidity: %s%%",
                $measurement->getDate()->format('Y-m-d'),
                $measurement->getCelsius(),
                $measurement->getPressure(),
                $measurement->getHumidity()
            ));
        }

        return Command::SUCCESS;
    }
}

