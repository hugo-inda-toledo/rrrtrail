<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Routing\Router;
use Cake\Http\ServerRequest;

class SlackHelper extends Helper
{
    // (string) $message - message to be passed to Slack
    // (string) $room - room in which to write the message, too
    // (string) $icon - You can set up custom emoji icons to use with each message
    public static function message($message, $room = "reportes", $icon = ":sunglasses:", $files = array()) {
        
        $room = ($room) ? $room : "engineering";
        $attachments = [];

        if(count($files) > 0){

            $request = new ServerRequest();

            $fields = [];

            $x = 0;
            foreach($files as $type => $data){
                $fields[$x]['title'] = $type;
                $fields[$x]['value'] = $request->webroot.'/files/pdfs/'.$data['file_name'];
                $fields[$x]['short'] = true;
            }

            $attachments = array([
                'fallback' => 'Hey! See this message',
                'pretext'  => 'Here is the plan name',
                'color'    => '#ff6600',
                'fields'   => $fields
            ]);
        }

        $data = json_encode(array(
            "channel"       =>  "#{$room}",
            "text"          =>  $message,
            "icon_emoji"    =>  $icon,
            "attachments"   =>  $attachments
        ));
    
        // You can get your webhook endpoint from your Slack settings
        $ch = curl_init("https://hooks.slack.com/services/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");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('payload' => $data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
    
        // Laravel-specific log writing method
        // Log::info("Sent to Slack: " . $message, array('context' => 'Notifications'));
        return $result;
    }
}