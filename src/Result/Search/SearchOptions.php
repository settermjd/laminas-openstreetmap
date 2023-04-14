<?php

declare(strict_types=1);

namespace Laminas\OpenStreetMap\Result\Search;

readonly class SearchOptions
{
    private bool $showAddressDetails;
    private bool $showExtraTags;
    private bool $showNameDetails;
    private bool $deDupeResults;
    private bool $debug;

    public function __construct(
        bool $showAddressDetails = false,
        bool $showExtraTags = false,
        bool $showNameDetails = false,
        bool $deDupeResults = false,
        bool $debug = false
    ) {
        $this->showAddressDetails = $showAddressDetails;
        $this->showExtraTags      = $showExtraTags;
        $this->showNameDetails    = $showNameDetails;
        $this->deDupeResults      = $deDupeResults;
        $this->debug              = $debug;
    }
}
