<?php

namespace App;

use Illuminate\Http\Request;

class EDSAPI
{

    private $headers;
    private $authToken;
    private $authTimeout;
    private $sessionToken;
    private $client;
    private $userid;
    private $password;
    private $profile;
    private $org;


    function __construct($userid, $password, $profile, $org)
    {

        $this->userid = $userid;
        $this->password = $password;
        $this->profile = $profile;
        $this->org = $org;

        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Create a client with a base URI
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://eds-api.ebscohost.com/']);

        $this->getAuthToken();
        $this->getSessionToken();

    }

    public function search(Request $request)
    {

        $params = $this->buildRequest($request);

        $response = $this->client->request('POST', 'edsapi/rest/Search', [
            'headers' => $this->headers,
            'json' => $params
        ]);

        $items = $this->processResponse($response);

        return $items;

    }

    private function buildRequest(Request $request)
    {

        $params = [
            'SearchCriteria' => [
                'Queries' => [
                    [
                        'BooleanOperator' => 'AND',
                        'Term' => $request->get('search_term_1')
                    ]
                ],
                "SearchMode" => "all",
                "IncludeFacets" => "y",
                "Sort" => "relevance",
                "AutoSuggest" => "y",
                "AutoCorrect" => "y"
            ],
            "RetrievalCriteria" => [
                "View" => "brief",
                "ResultsPerPage" => 20,
                "PageNumber" => 1,
                "Highlight" => "y",
                "IncludeImageQuickView" => "n"
            ],
            "Actions" => null
        ];

        return $params;

    }

    private function processResponse($response)
    {

        $results = json_decode($response->getBody());

        $items = [];
        // Perhaps have some customs getters to format data and determine best data
        foreach($results->SearchResult->Data->Records as $record){
            $items[] = [
                'name' => collect($record->Items)->where('Name', 'Title')->first()->Data,
                'link' => $record->PLink
            ];
        }

        return $items;

    }

    private function getAuthToken()
    {

        $response = $this->client->request('POST', 'authservice/rest/UIDAuth', [
            'headers' => $this->headers,
            'json' => [
                'UserId' => $this->userid,
                'Password' => $this->password
            ]
        ]);

        $uIDAuthResponse = json_decode($response->getBody());
        $this->authToken = $uIDAuthResponse->AuthToken;
        $this->authTimeout = $uIDAuthResponse->AuthTimeout;

        $this->headers['x-authenticationToken'] = $this->authToken;

    }

    private function getSessionToken()
    {

        $response = $this->client->request('POST', 'edsapi/rest/createsession', [
            'headers' => $this->headers,
            'json' => [
                'Profile' => $this->profile,
                'Org' => $this->org
            ]
        ]);

        $this->sessionToken = json_decode($response->getBody())->SessionToken;

        $this->headers['x-sessionToken'] = $this->sessionToken;

    }

}
