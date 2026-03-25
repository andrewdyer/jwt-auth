<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth;

final readonly class Claims
{
    public function __construct(
        public string     $iss,
        public ?string    $aud,
        public int        $iat,
        public int        $nbf,
        public int        $exp,
        public string     $jti,
        public int|string $sub,
        public array      $custom = [],
    ) {
    }

    public function toArray(): array
    {
        return array_filter(array_merge([
            'iss' => $this->iss,
            'aud' => $this->aud,
            'iat' => $this->iat,
            'nbf' => $this->nbf,
            'exp' => $this->exp,
            'jti' => $this->jti,
            'sub' => $this->sub,
        ], $this->custom), static fn ($v) => $v !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            iss: $data['iss'] ?? 'app',
            aud: $data['aud'] ?? null,
            iat: $data['iat'],
            nbf: $data['nbf'],
            exp: $data['exp'],
            jti: $data['jti'],
            sub: $data['sub'],
            custom: array_diff_key($data, array_flip(['iss', 'aud', 'iat', 'nbf', 'exp', 'jti', 'sub']))
        );
    }
}
