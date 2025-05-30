<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DatabaseBackupNotification;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup the database and upload it to Digital Ocean Spaces';

    public function handle()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $filePath = storage_path('app/' . $filename);

        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPassword = env('DB_PASSWORD', '');
        $dbName = env('DB_DATABASE', 'forge');

        $command = [
            'mysqldump',
            '--user=' . $dbUser,
            '--password=' . $dbPassword,
            '--host=' . $dbHost,
            '--port=' . $dbPort,
            $dbName,
            '--result-file=' . $filePath
        ];

        $process = new Process($command);

        try {
            $process->mustRun();
            $this->info("Database backup created: $filename");

            // Upload to DigitalOcean Spaces
            Storage::disk('do_spaces')->put("backups/$filename", file_get_contents($filePath), 'private');
            $this->info("Backup uploaded successfully to DigitalOcean Spaces: backups/$filename");

            // Get URL (if your Space is public, otherwise remove this)
            $storageUrl = Storage::disk('do_spaces')->url("backups/$filename");

            // Send email notification
            Notification::route('mail', env('BACKUP_NOTIFICATION_EMAIL'))
                ->notify(new DatabaseBackupNotification($filename, $storageUrl));

            $this->info("Email notification sent to " . env('BACKUP_NOTIFICATION_EMAIL'));

            // Delete local file after upload
            unlink($filePath);
        } catch (ProcessFailedException $exception) {
            $this->error("Backup failed: " . $exception->getMessage());
        }
    }
}
