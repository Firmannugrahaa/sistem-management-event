<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Image Source Analysis ===\n\n";

// Check catalog items
$catalogWithImage = \App\Models\VendorCatalogItem::whereNotNull('image_url')->where('image_url', '!=', '')->count();
$catalogTotal = \App\Models\VendorCatalogItem::count();
echo "Catalog Items with image_url: $catalogWithImage / $catalogTotal\n";

// Check portfolio images
$portfolioImages = \App\Models\VendorPortfolioImage::count();
echo "Portfolio Images total: $portfolioImages\n";

// Check vendors with logo
$vendorsWithLogo = \App\Models\Vendor::whereNotNull('logo_path')->count();
echo "Vendors with logo: $vendorsWithLogo\n\n";

// Sample catalog items
echo "=== Sample Catalog Items ===\n";
$items = \App\Models\VendorCatalogItem::with('vendor')->take(5)->get();
foreach ($items as $item) {
    echo "- {$item->name} (Vendor: {$item->vendor->brand_name})\n";
    echo "  image_url: " . ($item->image_url ?: 'NULL') . "\n";
}

// Sample portfolio images
echo "\n=== Sample Portfolio Images ===\n";
$portfolios = \App\Models\VendorPortfolio::with(['images', 'vendor'])->take(5)->get();
foreach ($portfolios as $portfolio) {
    echo "- {$portfolio->title} (Vendor: {$portfolio->vendor->brand_name})\n";
    echo "  Images: " . $portfolio->images->count() . "\n";
    foreach ($portfolio->images->take(2) as $img) {
        echo "    -> {$img->image_path}\n";
    }
}
