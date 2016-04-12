<?php
    // Make sure that the index.php file is in the same directory as the 'lib' folder.
    require_once __DIR__ . '/lib/OAuth/OAuthTokenService.php';
    require_once __DIR__ . '/lib/Speech/SpeechService.php';


    $uniqid = uniqid();
    $fp = fopen( '/tmp/'.$uniqid.'.mp3', 'wb' );
    fwrite( $fp, $GLOBALS[ 'HTTP_RAW_POST_DATA' ] );
    fclose( $fp );

    exec('/usr/bin/ffmpeg -i /tmp/'.$uniqid.'.mp3 -acodec pcm_s16le -ac 1 -ar 16000 /tmp/'.$uniqid.'.wav');

    /*
     * Project - Steth
     * ffmpeg -i payload.mp3 -acodec pcm_s16le -ac 1 -ar 16000 payload.wav
     */

    // Use any namespaced classes.
    use Att\Api\OAuth\OAuthTokenService;
    use Att\Api\Speech\SpeechService;

    // Use the app account settings from developer.att.com for the following values.
    // Make sure that the API scope is set to SPEECH for the Speech API before
    // retrieving the App Key and App Secret.

    // Enter the value from the 'App Key' field obtained at developer.att.com
    // in your app account.
    $clientId = 'XXXXX';

    // Enter the value from the 'App Secret' field obtained at developer.att.com
    // in your app account.
    $clientSecret = 'XXXXX';

    // Create the service for requesting an OAuth access token.
    $osrvc = new OAuthTokenService('https://api.att.com', $clientId, $clientSecret);

    // Get the OAuth access token.
    $token = $osrvc->getToken('STTC');

    // Create the service for interacting with the Speech API.
    $speechSrvc = new SpeechService('https://api.att.com', $token);

    // The Speech API requires the audio files to be certain formats. In order to
    // convert speech files to the proper format, the ffmpeg program may be used.
    // The ffmpeg program can be downloaded from https://ffmpeg.org/

    // The following try/catch blocks can be used to test the methods of the
    // Speech API. To test a specific method, comment out the other try/catch blocks.

    try {
        // Enter the path of the file to translate.
        // For example: $fname = '/tmp/file.wav';
        $fname = $uniqid.'.wav';
        // Enter the path of the grammar file.
        // For example: $gfname = '/tmp/x-grammar.txt'
        $gfname = 'x-grammer.txt';
        // Enter the path of the dictionary file.
        // For example: $dfname = '/tmp/x-dictionary.txt'
        $dfname = null;//'x-dictionary.txt';
        $speechContext = 'GenericHints';
        // Send the request to convert the speech file to text.
        $response = $speechSrvc->speechToTextCustom(
            $speechContext, $fname, $gfname, $dfname
        );
        $recognition = $response['Recognition'];

        $words = $recognition['NBest'][0]['Words'];
        $number = "";
        foreach ($words as $word) {
            switch ($word) {
                case "one":
                    $number.="1";
                    break;
                case "two":
                    $number.="2";
                    break;
                case "three":
                    $number.="3";
                    break;
                case "four":
                    $number.="4";
                    break;
                case "five":
                    $number.="5";
                    break;
                case "six":
                    $number.="6";
                    break;
                case "seven":
                    $number.="7";
                    break;
                case "eight":
                    $number.="8";
                    break;
                case "nine":
                    $number.="9";
                    break;
                case "zero":
                    $number.="0";
                    break;
                case "oh":
                    $number.="0";
                    break;
                default:
                    $number.="?";
            }
        }

        echo $number;

    } catch(ServiceException $se) {
        echo $se->getErrorResponse();
    }
