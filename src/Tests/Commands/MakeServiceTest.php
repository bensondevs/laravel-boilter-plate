<?php

namespace Bensondevs\LaravelBoilerPlate\Tests\Commands;

/**
 * @see \Bensondevs\LaravelBoilerPlate\Commands\MakeService
 *      To the tested command class.
 */
class MakeServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Ensure make service command is callable.
     *
     * @test
     * @return void
     */
    public function ensureCommandCallable(): void
    {
        $serviceName = 'LaravelBoilerPlateService';

        echo shell_exec('cd ../../../ && php artisan make:service ' . $serviceName);
        $expectedDestination = __DIR__ . '../../../../app/Services/' . $serviceName;

        $this->assertTrue(file_exists($expectedDestination));
    }
}
