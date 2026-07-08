<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RepointMediaDisk extends Command
{
    protected $signature = 'media:repoint-disk
        {--from=public : Disk name currently stored on existing media rows}
        {--to=r2 : Disk name to move existing media rows onto}
        {--dry-run : Report the row counts without updating anything}';

    protected $description = 'Repoint existing Spatie MediaLibrary rows (disk + conversions_disk) to a new disk after a storage switch.';

    public function handle(): int
    {
        if (! Schema::hasTable('media')) {
            $this->warn('No "media" table found — nothing to repoint.');

            return self::SUCCESS;
        }

        $from = $this->option('from');
        $to = $this->option('to');
        $dry = (bool) $this->option('dry-run');

        $diskCount = DB::table('media')->where('disk', $from)->count();
        $convCount = DB::table('media')
            ->where('conversions_disk', $from)
            ->count();

        $this->info(sprintf('Repointing media from "%s" to "%s".', $from, $to));
        $this->line("  disk column:             {$diskCount} row(s)");
        $this->line("  conversions_disk column: {$convCount} row(s)");

        if ($dry) {
            $this->info('[dry-run] No changes written. Copy the files first with: php artisan assets:sync-r2');

            return self::SUCCESS;
        }

        $updatedDisk = DB::table('media')->where('disk', $from)->update(['disk' => $to]);
        $updatedConv = DB::table('media')->where('conversions_disk', $from)->update(['conversions_disk' => $to]);

        $this->info("Updated disk on {$updatedDisk} row(s) and conversions_disk on {$updatedConv} row(s).");
        $this->comment('Make sure the files are already on the destination disk (php artisan assets:sync-r2) before serving.');

        return self::SUCCESS;
    }
}
