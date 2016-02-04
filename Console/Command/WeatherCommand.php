<?php

namespace Console\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WeatherCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('oreo')
            ->setDescription('Get current weather')
            ->addArgument(
                'city',
                InputArgument::REQUIRED,
                'Which city??'
            )
            ->addOption(
                'gay',
                null,
                InputOption::VALUE_NONE,
                'If set, the output will be gay'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $city = $input->getArgument('city');

        $apiResponse = $this->callWeatherApi($city);
        $description = $this->getWeatherDescription($apiResponse);
        $degrees = $this->getWeatherDegrees($apiResponse);

        $text = 'The weather in '. $city. ' is ' . $description . ' with '.$degrees. ' degrees';

        if ($input->getOption('gay')) {
            $text = "<bg=yellow;fg=magenta;options=bold> " .$text. "</>";
        }

        $output->writeln($text);

    }

    protected function callWeatherApi ($city)
    {
        $ch = curl_init('http://api.openweathermap.org/data/2.5/weather?q='.$city.',uk&appid=44db6a862fba0b067b1930da0d769e98');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    protected function getWeatherDescription ($apiResponse)
    {
        return $apiResponse->weather[0]->description;
    }

    protected function getWeatherDegrees ($apiResponse)
    {
        return $apiResponse->main->temp;
    }
}