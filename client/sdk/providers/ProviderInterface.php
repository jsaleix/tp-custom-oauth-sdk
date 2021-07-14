<?php
namespace Sdk\Providers;

interface ProviderInterface
{
    public function getLinks(): string;
    public function getErrorMessage(): void;
    public function handleRoute($route = null): ?array;
}


?>