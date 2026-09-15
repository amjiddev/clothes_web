<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CollectionSection;

class FixCollectionLinks extends Command
{
    protected $signature = 'fix:collection-links';
    protected $description = 'Fix collection section button links to use brand slugs';

    public function handle()
    {
        // Map of section keys to brand slugs
        $updates = [
            'best_sellers' => 'gull-ahmad',
            'featured_collection' => 'apna-raza',
            'summer_collection' => 'evan-strong',
        ];

        foreach ($updates as $sectionKey => $brandSlug) {
            $section = CollectionSection::where('section_key', $sectionKey)->first();
            if ($section) {
                $section->update(['button_link' => $brandSlug]);
                $this->info("Updated {$sectionKey} with brand slug: {$brandSlug}");
            } else {
                $this->warn("Section not found: {$sectionKey}");
            }
        }

        $this->info('All collection links updated successfully!');
    }
}
