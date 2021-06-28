<?php
namespace Sdk\Provider;


interface ProviderInterface
{
    public function handleCodeType(): void;
    public function handlePasswordType(): void;
    public function getInfos(string $token): array;
}

abstract class Provider{
    protected string $client_id;
    protected string $client_secret;
}

?>