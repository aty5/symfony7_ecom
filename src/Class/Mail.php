<?php

namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{

    public function send($to_email, $to_name, $subject, $template, $vars = null)
    {
        // recupere le template
        $content = file_get_contents(dirname(__DIR__) . '/Mail/' . $template);

        // recupere les variables facultatives
        if ($vars) {
            foreach ($vars as $key => $var) {
                $content = str_replace('{'.$key.'}', $var, $content);
            }
        }
        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'],true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "atilla.yas@live.com",
                        'Name' => "Symfony7_ecom"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 6667632,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);


    }
}