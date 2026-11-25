<?php

namespace App\Console\Commands;

use Aws\S3\S3Client;
use Illuminate\Console\Command;
use Throwable;

class EnsureMinioBucket extends Command
{
    protected $signature = 'minio:ensure-bucket';

    protected $description = 'Cria o bucket do MinIO se ainda não existir';

    public function handle(): int
    {
        $bucket = config('filesystems.disks.s3.bucket');

        try {
            $client = new S3Client([
                'version' => 'latest',
                'region' => config('filesystems.disks.s3.region', 'us-east-1'),
                'endpoint' => config('filesystems.disks.s3.endpoint'),
                'use_path_style_endpoint' => true,
                'credentials' => [
                    'key' => config('filesystems.disks.s3.key'),
                    'secret' => config('filesystems.disks.s3.secret'),
                ],
            ]);

            if (! $client->doesBucketExist($bucket)) {
                $client->createBucket(['Bucket' => $bucket]);
                $this->info("Bucket [{$bucket}] criado.");
            } else {
                $this->info("Bucket [{$bucket}] já existe.");
            }

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Falha ao preparar MinIO: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
