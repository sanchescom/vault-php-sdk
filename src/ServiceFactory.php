<?php
namespace Jippi\Vault;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Log\LoggerInterface;

class ServiceFactory
{
    protected static $services = [
        'sys' => 'Jippi\Vault\Services\Sys',
        'data' => 'Jippi\Vault\Services\Data',
        'auth/token' => 'Jippi\Vault\Services\Auth\Token',
        'auth/approle'=>'Jippi\Vault\Services\Auth\AppRole'
    ];

    protected $client;

    public function __construct(array $options = array(), LoggerInterface $logger = null, GuzzleClient $guzzleClient = null)
    {
        $this->client = new Client($options, $logger, $guzzleClient);
    }

    public function get($service)
    {
        $services = $this->getServices();

        if (!array_key_exists($service, $services)) {
            $servicesString = implode('", "', array_keys($services));

            throw new \InvalidArgumentException(
                sprintf('The service "%s" is not available. Pick one among "%s".', $service, $servicesString)
            );
        }

        $class = $services[$service];

        return new $class($this->client);
    }

    protected function getServices()
    {
        return array_merge(self::$services, static::$services);
    }
}
