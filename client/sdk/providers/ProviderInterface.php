<?php
namespace Sdk\Providers;

interface ProviderInterface
{
    //public function handleCodeType(): void;
    public function getInfos(string $token): ?array;
    public function getLinks(): string;
    public function handleRoute($route = null): ?array;
}


?>