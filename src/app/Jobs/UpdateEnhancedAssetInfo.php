<?php

namespace Droplister\XcpCore\App\Jobs;

use Exception;
use Droplister\XcpCore\App\Asset;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateEnhancedAssetInfo implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Update Enhanced Asset Info job
    |--------------------------------------------------------------------------
    |
    | The purpose of this job is to determine whether an asset might be
    | using enhanced asset info and, if it might be, try and fetch
    | that data even though there is a good chance of failure. 
    |
    */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Asset
     *
     * @var \Droplister\XcpCore\App\Asset
     */
    protected $asset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Asset $asset)
    {
        $this->asset = $asset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Let's just treat it as a string
        $string = trim($this->asset->description);

        // Only bother if it might be json
        if(substr($string, -5) === '.json') 
        {
            // Good candidate for being a url
            if(substr($string, 0, 4) === 'http')
            {
                $url = $string;

                $data = $this->getFileContents($url);
            }
            // Try adding protocol to string
            else
            {
                $url_http = 'http://' . $string;
                $url_https = 'https://' . $string;

                $data = $this->getFileContents($url_http);
                if(! $data) $data = $this->getFileContents($url_https);
            }

            if($this->guardAgainstInvalidJson($data))
            {
                $this->updateAsset($data);
            }
        }
    }

    /**
     * Get file contents.
     * 
     * @param  string  $url
     * @return mixed
     */
    private function getFileContents($url)
    {
        $context = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];

        try
        {
            // Avoid getting caught loading
            set_time_limit(5);

            // Context helps with edge cases
            return file_get_contents($url, false, stream_context_create($context));
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
     * Update asset. 
     * 
     * @param  array  $data
     * @return void
     */
    private function updateAsset($data)
    {
        try
        {
            // Encoding helps with edge cases
            $this->asset->update([
                'meta' => mb_convert_encoding($data, 'UTF-8', 'UTF-8')
            ]);
        }
        catch (Exception $e)
        {
            // Not Valid
        }
    }

    /**
     * Guard against invalid json.
     * 
     * @param  string  $data
     * @return boolean
     */
    private function guardAgainstInvalidJson($data)
    {
        if (! empty($data))
        {
            @json_decode($data);

            return json_last_error() === JSON_ERROR_NONE;
        }

        return false;
    }
}