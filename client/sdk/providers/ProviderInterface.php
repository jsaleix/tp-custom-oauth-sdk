<?php
namespace Sdk\Provider;


interface ProviderInterface
{
    public function handleCodeType(): void;
    public function handlePasswordType(): void;
    public function getInfos(string $token): array;
}

?>