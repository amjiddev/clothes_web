<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Brand;
use App\Models\CollectionSection;

class FixCollectionSections extends Command
{
    protected $signature = 'fix:collection-sections';
    protected $description = 'Fix collection sections to have proper brand links';

    public function handle()
    {
        $this->info('Fixing collection sections...');

        // Get the brands
        $gullAhmad = Brand::where('slug', 'gull-ahmad')->first();
        $apnaRaza = Brand::where('slug', 'apna-raza')->first();
        $eranStrong = Brand::where('slug', 'eran-strong')->first();

        if ($gullAhmad) {
            CollectionSection::where('section_key', 'summer_collection')->update([
                'button_text' => 'Gull Ahmad',
                'button_link' => '/brands-page?brand=' . $gullAhmad->id
            ]);
            $this->info("Updated Summer Collection to link to Gull Ahmad (ID: {$gullAhmad->id})");
        }

        if ($apnaRaza) {
            CollectionSection::where('section_key', 'featured_collection')->update([
                'button_text' => 'Apna Raza', 
                'button_link' => '/brands-page?brand=' . $apnaRaza->id
            ]);
            $this->info("Updated Featured Collection to link to Apna Raza (ID: {$apnaRaza->id})");
        }

        if ($eranStrong) {
            CollectionSection::where('section_key', 'best_sellers')->update([
                'button_text' => 'Eran Strong',
                'button_link' => '/brands-page?brand=' . $eranStrong->id
            ]);
            $this->info("Updated Best Sellers to link to Eran Strong (ID: {$eranStrong->id})");
        }

        $this->info('Collection sections updated successfully!');
        return 0;
    }
}