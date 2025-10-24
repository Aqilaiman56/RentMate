<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\ItemImage;

class MigrateOldItemImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:migrate-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old single images from items.ImagePath to item_images table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of old item images...');

        // Get all items that have ImagePath but no records in item_images
        $items = Item::whereNotNull('ImagePath')
            ->whereHas('images', function($query) {
                // This will get items with no images in item_images table
            }, '=', 0)
            ->get();

        if ($items->isEmpty()) {
            $this->info('No items found that need migration.');
            return 0;
        }

        $this->info("Found {$items->count()} items to migrate.");

        $bar = $this->output->createProgressBar($items->count());
        $bar->start();

        $migrated = 0;
        $skipped = 0;

        foreach ($items as $item) {
            try {
                // Create entry in item_images table
                ItemImage::create([
                    'ItemID' => $item->ItemID,
                    'ImagePath' => $item->ImagePath,
                    'DisplayOrder' => 0
                ]);

                $migrated++;
            } catch (\Exception $e) {
                $skipped++;
                $this->error("\nFailed to migrate item {$item->ItemID}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Migration completed!");
        $this->info("Migrated: {$migrated} items");

        if ($skipped > 0) {
            $this->warn("Skipped: {$skipped} items (due to errors)");
        }

        return 0;
    }
}
