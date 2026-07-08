<?php

namespace App\View\Components;

use App\Models\RepairService;
use App\Models\RepairServiceSubPage;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class RepairServiceSection extends Component
{
    public Collection $sections;

    // Mapping: DB page_category value → Clean URL segment
    private array $urlMap = [
        'repair-service' => 'repairing-services',  // DB: repair-service → URL: repairing-services
        'x-ray'          => 'x-ray',
        'c-arm'          => 'c-arm',
        // Add more in future: 'ct-scan' => 'ct-scan-services',
    ];

    /**
     * @param string|array $types  e.g. 'x-ray' or ['x-ray', 'c-arm', 'repairing']
     * @param array $types        Order will be preserved exactly as passed
     */
    public function __construct(string|array $types = ['x-ray', 'c-arm'])
    {
        $typeList = is_string($types) ? [$types] : ($types ?? ['x-ray', 'c-arm']);

        // Sab relevant columns select kar lo ek baar
        $repairService = RepairService::select([
            'repair_service_heading',
            'repair_service_short_description',
            'x_ray_heading',
            'x_ray_short_description',
            'c_arm_heading',
            'c_arm_short_description',
            // Future mein aur categories add ho to yahan daal dena
        ])->first();


        $this->sections = collect();

        foreach ($typeList as $type) {
            $type = trim(strtolower($type)); // 'x-ray' ya 'c-arm'

            // Yeh rakhna hai page_category ke liye (DB mein hyphen hai)
            $categoryType = $type; // 'x-ray'

            // Field name ke liye hyphen ko underscore kar do
            $fieldType = str_replace('-', '_', $type); // 'x_ray', 'c_arm'

            $headingField = $fieldType . '_heading';         // 'x_ray_heading'
            $descField    = $fieldType . '_short_description'; // 'x_ray_short_description'

            $heading = $repairService?->{$headingField} ?? '';
            $description = $repairService?->{$descField} ?? '';

            $items = RepairServiceSubPage::where('page_category', $categoryType) // hyphen wala
                ->where('is_active', true)
                ->select(['title', 'slug', 'short_description'])
                ->get();

            // dd($type, $categoryType , $fieldType , $headingField , $descField , $heading , $description , $items);

            if ($items->isNotEmpty() || filled($heading) || filled($description)) {
                $this->sections->push([
                    'type' => $categoryType,
                    'urlSegment' => $this->urlMap[$categoryType] ?? $categoryType, // Clean URL part
                    'headingData' => split_heading($heading),
                    'shortDescription' => $description,
                    'items' => $items,
                ]);
            }
        }
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.repair-service-section');
    }
}
