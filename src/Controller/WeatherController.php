<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MeasurementRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'app_weather', requirements: ['city' => '[a-zA-Z\-\s]+'])]
    public function city(Location $location, WeatherUtil $util): Response
    {
        $measurements = $util->getWeatherForLocation($location);
        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
    #[Route('/weather/{city}/{country}', name: 'app_weather_city_country', requirements: ['city' => '[a-zA-Z\-\s]+', 'country' => '[A-Z]{2}'])]
    public function cityCountry(Location $location, WeatherUtil $util, string $country, string $city): Response
    {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }






}
