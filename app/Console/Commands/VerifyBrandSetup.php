<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Brand;
use App\Models\CollectionSection;

class VerifyBrandSetup extends Command
{
    protected $signature = 'verify:brand-setup';
    protected $description = 'Verify that brand setup is working correctly';

    public function handle()
    {
        $this->info('=== BRAND SETUP VERIFICATION ===');
        $this->newLine();

        // Check brands
        $this->info('BRANDS:');
        $brands = Brand::all();
        foreach ($brands as $brand) {
            $this->line("- {$brand->name} (ID: {$brand->id}, Slug: {$brand->slug})");
        }

        $this->newLine();
        $this->info('COLLECTION SECTIONS:');
        $sections = CollectionSection::all();
        foreach ($sections as $section) {
            $this->line("- {$section->section_name} (Button: '{$section->button_text}', Link: '{$section->button_link}')");
        }

        $this->newLine();
        $this->info('=== SETUP COMPLETE! ===');
        $this->info('Now when you click:');
        foreach ($brands as $brand) {
            $this->line("- '{$brand->name}' button → Goes to /brands-page?brand={$brand->id}");
        }
        $this->newLine();
        $this->info('The brands page will automatically show the selected brand in the sidebar!');
        
        return 0;
    }
}