<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Speech\SpeechClient;
use \falahati\PHPMP3\MpegAudio;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Core\ExponentialBackoff;
use Illuminate\Support\Str;
use Exception;

class VoicetranscriptionController extends Controller
{
    //
    public function index(){
        return view('welcome');
    }

    //Assyncronous call, takes too long
    function transcribe_async_gcs($bucketName, $objectName, $languageCode = 'en-US', $options = [])
        {
            // Create the speech client
            $keyfilepath=resource_path().'/My Project-e07bb0e17775.json';
            $speech = new SpeechClient([
                'keyFilePath' => $keyfilepath,
                'languageCode' => $languageCode,
            ]);

            // Fetch the storage object
            $storage = new StorageClient(['keyFilePath' => $keyfilepath]);
            $object = $storage->bucket($bucketName)->object($objectName);

            // Create the asyncronous recognize operation
            $operation = $speech->beginRecognizeOperation(
                $object,
                $options
            );

            // Wait for the operation to complete
            $backoff = new ExponentialBackoff(10);
            $backoff->execute(function () use ($operation) {
                print('Waiting for operation to complete' . PHP_EOL);
                $operation->reload();
                if (!$operation->isComplete()) {
                    throw new Exception('Job has not yet completed', 500);
                }
            });

            $data=array();

            // Print the results
            if ($operation->isComplete()) {
                $results = $operation->results();

                foreach ($results as $result) {
                    $alternative = $result->alternatives()[0];
                    array_push($data,$alternative['transcript']);
                }

                return view('welcome',['data'=>$data]); 
            }
        }

    public function upload(Request $request){
        
        $keyfilepath=resource_path().'/My Project-e07bb0e17775.json';
       

        //get the file upload
        $file=$request->file('upload');

        /*$command = "ffmpeg -i $file -c:v libx264 convertedaudio.mp3";
        exec($command);

        $file="convertedaudio.mp3";*/
        
        # Instantiates a client
        $speech = new SpeechClient([
            'keyFilePath' => $keyfilepath,
            'languageCode' => 'en-US',
        ]);

        $data=array();
        
        //read audio total legnth
        $total_duration=MpegAudio::fromFile($file)->getTotalDuration();

        
        //Cut the audio into 50 seconds pieces
        for($i=0;$i < (int)$total_duration;$i+=50){
            
            $file2=MpegAudio::fromFile($file)->trim($i,50)->saveFile("new.mp3");
            $options = [
                
            ];
            
            //create a new filename
            $newfilename=Str::random(32).".wav";
            $file3="new.mp3";
    
            //convert the mp3 to wav
            $command = "ffmpeg -i $file3 -c:v libx264 $newfilename";
            exec($command);
            
            //call speach library to recognise the speech
            $results = $speech->recognize(fopen($newfilename, 'r'), $options);
    
            //add the output of that piece to data array
            foreach ($results as $result) {
                array_push($data,$result->alternatives()[0]['transcript']);
            }

        }

        return view('welcome',compact('data')); 

    }

}

