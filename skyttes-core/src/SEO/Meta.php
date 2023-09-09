<?php declare(strict_types=1);

namespace Skyttes\Core\SEO;

class Meta {
    public string $name = "";

    public string $lang = "";
    
    public string $title = "";

    public string $description = "";

    public string $url = "/";

    /**
     * @var string[]
     */
    public array $keywords = [];

    public function formatKeywords(): string
    {
        return implode(", ", $this->keywords);
    }

    public function formatTitle(): string
    {
        if (!strlen(trim($this->title))) return $this->name;

        return implode(" | ", [$this->title, $this->name]);
    }
}