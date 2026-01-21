<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductVariable;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use App\Models\Weight;
use Illuminate\Support\Facades\DB;

class MigrateProductVariables extends Command
{
    protected $signature = 'migrate:product-variables
                            {--dry-run : Show what would be migrated without actually doing it}
                            {--rollback : Rollback migration to ProductVariable}';
    
    protected $description = 'Migrate ProductVariable (denormalized) to ProductVariant (normalized)';

    public function handle()
    {
        if ($this->option('rollback')) {
            return $this->rollback();
        }

        if ($this->option('dry-run')) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $this->info('Starting ProductVariable migration...');
        $this->line('');

        try {
            DB::beginTransaction();

            $count = $this->migrateVariables();

            if (!$this->option('dry-run')) {
                DB::commit();
                $this->info("✓ Successfully migrated {$count} product variants!");
                $this->info('ProductVariable table can now be archived.');
            } else {
                DB::rollBack();
                $this->info("DRY RUN: Would have migrated {$count} product variants");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Migration failed: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }

    private function migrateVariables(): int
    {
        $variables = ProductVariable::all();
        $count = 0;

        $this->output->progressStart($variables->count());

        foreach ($variables as $variable) {
            // Check if product exists
            $product = Product::find($variable->product_id);
            if (!$product) {
                $this->warn("Product {$variable->product_id} not found, skipping variant");
                $this->output->progressAdvance();
                continue;
            }

            // Find or create Color
            $color = null;
            if ($variable->color) {
                $color = Color::firstOrCreate(
                    ['name' => $variable->color],
                    ['slug' => str_slug($variable->color)]
                );
            }

            // Find or create Size
            $size = null;
            if ($variable->size) {
                $size = Size::firstOrCreate(
                    ['name' => $variable->size],
                    ['slug' => str_slug($variable->size)]
                );
            }

            // Find or create Weight
            $weight = null;
            if ($variable->weight) {
                $weight = Weight::firstOrCreate(
                    ['value' => $variable->weight],
                    ['unit' => 'kg']
                );
            }

            // Create ProductVariant
            if (!$this->option('dry-run')) {
                ProductVariant::create([
                    'product_id' => $variable->product_id,
                    'color_id' => $color?->id,
                    'size_id' => $size?->id,
                    'weight_id' => $weight?->id,
                    'sku' => $variable->sku,
                    'stock' => $variable->stock ?? 0,
                    'created_at' => $variable->created_at,
                    'updated_at' => $variable->updated_at,
                ]);
            }

            $count++;
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        return $count;
    }

    private function rollback(): int
    {
        $this->warn('⚠ This will rollback variants to ProductVariable table');
        
        if (!$this->confirm('Are you sure?')) {
            $this->info('Rollback cancelled');
            return 0;
        }

        try {
            DB::beginTransaction();

            $variants = ProductVariant::all();
            $count = 0;

            $this->output->progressStart($variants->count());

            foreach ($variants as $variant) {
                ProductVariable::create([
                    'product_id' => $variant->product_id,
                    'color' => $variant->color?->name,
                    'size' => $variant->size?->name,
                    'weight' => $variant->weight?->value,
                    'sku' => $variant->sku,
                    'stock' => $variant->stock,
                    'created_at' => $variant->created_at,
                    'updated_at' => $variant->updated_at,
                ]);

                $count++;
                $this->output->progressAdvance();
            }

            $this->output->progressFinish();

            DB::commit();
            $this->info("✓ Rolled back {$count} variants to ProductVariable");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Rollback failed: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }
}
