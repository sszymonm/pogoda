<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'app_weather', requirements: ['city' => '[a-zA-Z\-\s]+'])]
    public function city(Location $location, MeasurementRepository $repository): Response
    {
        $measurements = $repository->findByLocation($location);
        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
    #[Route('/weather/{city}/{country}', name: 'app_weather_city_country', requirements: ['city' => '[a-zA-Z\-\s]+', 'country' => '[A-Z]{2}'])]
    public function cityCountry(Location $location, MeasurementRepository $repository, string $country): Response
    {
        $measurements = $repository->findByLocationAndCountry($location, $country);
        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }






}
