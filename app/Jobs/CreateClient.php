<?php

namespace App\Jobs;

use App\Database;
use App\DatabasePvc;
use App\Deployment;
use App\DeploymentPvc;
use App\Ingress;
use App\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateClient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $deployment;
    private $service;
    private $deployment_pvc;
    private $ingress;
    private $database;
    private $database_pvc;


    public function __construct(Deployment $deployment, Service $service, DeploymentPvc $deployment_pvc, Ingress $ingress, Database $database, DatabasePvc $database_pvc)
    {
        //
        $this->deployment = $deployment;
        $this->service = $service;
        $this->deployment_pvc = $deployment_pvc;
        $this->ingress = $ingress;
        $this->database = $database;
        $this->database_pvc = $database_pvc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
