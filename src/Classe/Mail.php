<?php
namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail 
{

    private $api_key = 'cecbb4ce0e95de1f65caa90616776161';
    private $api_key_secret = '6bd048ce729d45b03a53c3bda49badd1';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "contact@romaincurtet.com",
                        'Name' => "Tout Pour La Gratte"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name,
                        ]
                    ],
                    'TemplateID' => 3785303,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

}



?>