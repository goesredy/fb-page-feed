<?php
/**
 * Description of FbFeed
 *
 * @author hafiz
 */
namespace Yohafiz\FbFeed;

use GuzzleHttp\Client;

class Request {

    static function getPageFeed($pageId, $fbSecretKey, $fbAppId, $maxPost = 20) {
        $client = new Client();

        // this is how to construct access token using secret key and app id
        $accessToken = $fbAppId . '|' . $fbSecretKey;

        // make request as stated in https://developers.facebook.com/docs/graph-api/using-graph-api
        $url = 'https://graph.facebook.com/' . $pageId . '/feed';

        // error handler when status code not 200
        try {

            // start requet
            $response = $client->request('GET', $url, [
                'query' => [
                    'access_token' => $accessToken,
                    'limit' => $maxPost,
                    // fields that we intended to get
                    'fields' => 'id,message,created_time,from,permalink_url,full_picture',
                ]
            ]);
            $json = $response->getBody();

            if ($response->getStatusCode() == 200) {

                $dataArray = json_decode($json, true);

                // reformat data
                $feeds = $dataArray['data'];

                return $feeds;
            }
            else {
                return [
                    'error' => true,
                    'status_code' => $response->getStatusCode(),
                    'message' => nil
                ];
            }

        }
        catch (\Exception $e) {
            return [
                'error' => true,
                'status_code' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}