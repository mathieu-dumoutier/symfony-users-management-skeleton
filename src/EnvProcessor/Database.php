<?php

declare(strict_types=1);

namespace App\EnvProcessor;

use App\Repository\ConfigurationRepository;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class Database implements EnvVarProcessorInterface
{
    public function __construct(
        private ConfigurationRepository $configurationRepository,
    ) {
    }

    public function getEnv(string $prefix, string $name, \Closure $getEnv): ?string
    {
        try {
            // When database does not exist, it will throw an exception
            $config = $this->configurationRepository->find($name);

            if (null === $config) {
                return null;
            }

            return $config->getValue();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getProvidedTypes(): array
    {
        return [
            'database' => 'string',
        ];
    }
}
