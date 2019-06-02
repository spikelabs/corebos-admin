<?php

namespace App\Jobs;

use App\Client;
use App\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Queue;


class UpdateClientImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $image_tag;

    public function __construct($image_tag)
    {
        $this->image_tag = $image_tag;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image_data = explode(":", $this->image_tag);

        $image_name = $image_data[0];
        $tag = $image_data[1];

        $image = Image::where("dockerhub_image", $image_name)->first();

        if ($image) {
            $clients = Client::where("image_id", $image->id)->get();

            foreach ($clients as $client) {
                $job = (new UpdateClientDeployment(
                    $client->id,
                    $tag
                ))->onConnection('redis');

                Queue::push($job);
            }
        }
    }
}
