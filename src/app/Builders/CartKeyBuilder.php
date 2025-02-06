<?php

namespace App\Builders;

use App\Enums\CacheEnum;
use App\Exceptions\AgentNotFoundException;
use BugraBozkurt\InterServiceCommunication\Exceptions\UnauthorizedException;
use BugraBozkurt\InterServiceCommunication\Helpers\AuthHelper;
use Exception;

class CartKeyBuilder
{
    private const BEARER_PREFIX = 'Bearer ';

    /**
     * Sepet anahtarını oluşturur.
     *
     * - Eğer Authorization header (Bearer token) varsa: "user:{customerId}"
     * - Aksi halde: "guest:{agentId}"
     *
     * @return string
     * @throws Exception
     */
    public function build(): string
    {
        if ($customerId = $this->getCustomerIdFromAuthorization()) {
            return CacheEnum::USER->getValue() . $customerId;
        }

        if ($agentId = $this->getAgentId()) {
            return CacheEnum::GUEST->getValue() . $agentId;
        }

        throw new AgentNotFoundException();
    }

    /**
     * Authorization header içindeki Bearer token'dan müşteri ID'sini alır.
     *
     * @return string|null
     * @throws UnauthorizedException
     */
    private function getCustomerIdFromAuthorization(): ?string
    {
        $authorizationHeader = request()->header('Authorization');

        if ($authorizationHeader && str_starts_with($authorizationHeader, self::BEARER_PREFIX)) {
            return AuthHelper::customerId();
        }

        return null;
    }

    /**
     * Request içinden Agent ID değerini alır.
     *
     * @return string|null
     */
    private function getAgentId(): ?string
    {
        return request()->header('x-agent-id') ?? request()->input('agent_id');
    }
}
