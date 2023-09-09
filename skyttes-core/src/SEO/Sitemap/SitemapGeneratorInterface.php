<?php declare(strict_types=1);

namespace Skyttes\Core\SEO\Sitemap;

interface SitemapGeneratorInterface {
    public function generate(
        string $url,
        string $destination,
        bool $submitToSearchEngines
    ): void;
}