<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use Doctrine\ORM\EntityManagerInterface;

class WeatherUtil
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @return Measurement[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        $measurementRepository = $this->entityManager->getRepository(Measurement::class);

        $measurements = $measurementRepository->findBy(
            ['location' => $location],
            ['date' => 'ASC']
        );

        return $measurements;
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForCountryAndCity(string $country, string $city): array
    {
        $location = $this->entityManager->getRepository(Location::class)->findOneBy([
            'country' => $country,
            'city' => $city,
        ]);

        return $this->getWeatherForLocation($location);

    }
}
