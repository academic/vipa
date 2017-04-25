<?php
namespace Vipa\CoreBundle\Service;

class OrcidService
{
    public function getBio($orcidId)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => 'https://pub.orcid.org/'.$orcidId.'/orcid-profile',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json'
                ],
            ]
        );

        $result = curl_exec($curl);

        return $result;
    }
}
