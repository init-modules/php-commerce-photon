<?php

namespace Init\CommercePhoton\Pages;

use Illuminate\Support\Str;
use Init\Photon\Data\PhotonDocumentData;
use Init\Photon\Pages\Contracts\PhotonPageDefinition;

abstract class AbstractCommercePhotonPageDefinition implements PhotonPageDefinition
{
    public function group(): ?string
    {
        return $this->copy('Commerce', 'Коммерция');
    }

    public function description(): ?string
    {
        return null;
    }

    public function canDuplicate(): bool
    {
        return false;
    }

    public function resolveDocument(
        PhotonDocumentData $document,
        array $context,
    ): PhotonDocumentData {
        return PhotonDocumentData::createFromPayload([
            ...$document->toArray(),
            'name' => $this->name(),
            'route' => $this->routePattern(),
        ]);
    }

    public function prepareDocumentForSave(
        PhotonDocumentData $storedDocument,
        PhotonDocumentData $submittedDocument,
        array $context,
    ): PhotonDocumentData {
        return PhotonDocumentData::createFromPayload([
            ...$submittedDocument->toArray(),
            'name' => $this->name(),
            'route' => $this->routePattern(),
        ]);
    }

    public function resolveResources(array $context): array
    {
        return [];
    }

    public function persistResources(array $context, array $resources): void
    {
    }

    public function toCatalogItem(): array
    {
        $navigationRoute = $this->navigationRoute();

        return [
            'key' => $this->key(),
            'name' => $this->name(),
            'description' => $this->description(),
            'group' => $this->group(),
            'kind' => $this->kind(),
            'route' => $navigationRoute ?? $this->routePattern(),
            'routePattern' => $this->routePattern(),
            'navigationRoute' => $navigationRoute,
            'canOpen' => $this->canOpen(),
            'canDuplicate' => $this->canDuplicate(),
        ];
    }

    protected function routeConfig(string $key, string $fallback): string
    {
        $configured = config("commerce-photon.routes.{$key}");

        if (is_string($configured) && trim($configured) !== '') {
            return $this->normalizePath($configured);
        }

        return $this->normalizePath($fallback);
    }

    protected function normalizePath(string $path): string
    {
        $normalized = '/' . ltrim(trim($path), '/');

        return rtrim($normalized, '/') ?: '/';
    }

    protected function matchRoutePattern(string $pattern, string $path): ?array
    {
        $patternSegments = explode('/', trim($pattern, '/'));
        $pathSegments = explode('/', trim($path, '/'));

        if ($pattern === '/' && $path === '/') {
            return [];
        }

        if (count($patternSegments) !== count($pathSegments)) {
            return null;
        }

        $matches = [];

        foreach ($patternSegments as $index => $segment) {
            $candidate = $pathSegments[$index] ?? '';

            if (preg_match('/^\{([A-Za-z0-9_]+)\}$/', $segment, $groups) === 1) {
                $matches[$groups[1]] = $candidate;

                continue;
            }

            if ($segment !== $candidate) {
                return null;
            }
        }

        return $matches;
    }

    protected function buildRoute(string $pattern, array $parameters): string
    {
        $route = $pattern;

        foreach ($parameters as $key => $value) {
            $route = str_replace('{' . $key . '}', (string) $value, $route);
        }

        return $route;
    }

    protected function copy(string $en, string $ru): string
    {
        return $this->locale() === 'ru' ? $ru : $en;
    }

    protected function locale(): string
    {
        return Str::of((string) app()->getLocale())
            ->trim()
            ->lower()
            ->replace('_', '-')
            ->before('-')
            ->value() ?: 'en';
    }

    protected function makeDocument(string $suffix, array $blocks): PhotonDocumentData
    {
        return PhotonDocumentData::createFromPayload([
            'id' => 'photon-commerce-' . $suffix,
            'name' => $this->name(),
            'route' => $this->routePattern(),
            'updatedAt' => now()->toISOString(),
            'blocks' => $blocks,
        ]);
    }
}
