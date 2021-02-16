<?php


namespace App\Services\WeatherServices;


class GismeteoService
{

    const WEATHER_URL = 'https://www.gismeteo.ru/weather-%s/';

    private string $cityCode;

    public function setCityCode(string $cityCode): GismeteoService
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    public function getCurrentData(): ?array
    {
        if (!$this->cityCode) throw new \InvalidArgumentException('no cityCode');

        return $this->getProcessedData($this->getFetchedData());
    }

    private function getFetchedData(): ?string
    {
        return file_get_contents(sprintf(static::WEATHER_URL, $this->cityCode));
    }

    private function getProcessedData(string $data): ?array
    {
        $data = explode('MG.Media.data = {', $data)[1];
        $data = explode('</script>', $data)[0];
        $data = "{" . rtrim(trim($data), ';');
        $data = json_decode($data, true);

        return $data;
    }

}
