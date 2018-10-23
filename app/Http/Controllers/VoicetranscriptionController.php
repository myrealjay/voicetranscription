<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoicetranscriptionController extends Controller
{
    //
    public function index(){
        return view('welcome');
    }

    public function upload(Request $request){
        /*$this->validate($request, [
            'upload' =>'required|mimes:audio/mp3'
        ]);*/
        $file=$request->file('upload');
        $base64 = base64_encode($file);

        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);

        $body = array(
         "audio" => ["content"=>$base64],
          "config" => [ "encoding"=>"ENCODING_UNSPECIFIED"]);
          

        $r = $client->request('POST', 'https://speech.googleapis.com/v1/speech:recognize', [
            'body' => json_encode($body)
        ]);
        $response = $r->getBody()->getContents();

        dd($response);
    }
}
