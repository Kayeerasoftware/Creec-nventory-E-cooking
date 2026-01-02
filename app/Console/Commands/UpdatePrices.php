<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Part;
use App\Models\Appliance;

class UpdatePrices extends Command
{
    protected $signature = 'prices:update';
    protected $description = 'Update prices for all parts and appliances';

    public function handle()
    {
        $this->info('Updating prices...');

        // Update parts with estimated prices in UGX (5,000 - 150,000)
        Part::chunk(100, function ($parts) {
            foreach ($parts as $part) {
                $part->price = rand(5000, 150000);
                $part->save();
            }
        });

        // Update appliances with estimated prices in UGX (200,000 - 2,000,000)
        Appliance::chunk(100, function ($appliances) {
            foreach ($appliances as $appliance) {
                $appliance->price = rand(200000, 2000000);
                $appliance->save();
            }
        });

        $partsCount = Part::whereNotNull('price')->count();
        $appliancesCount = Appliance::whereNotNull('price')->count();

        $this->info("Prices updated successfully!");
        $this->info("Parts with prices: {$partsCount}");
        $this->info("Appliances with prices: {$appliancesCount}");

        return 0;
    }
}
