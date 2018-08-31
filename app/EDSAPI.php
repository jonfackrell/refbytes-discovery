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
        $source_types = $this->getSourceTypes($response);
        $subjects = $this->getSubjectFacets($response);

        return ['items' => $items , 'source_types' => $source_types, 'subjects' => $subjects];

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
        //dd($results->SearchResult);
        $items = [];
        // Perhaps have some customs getters to format data and determine best data
        foreach($results->SearchResult->Data->Records as $record){
            $items[] = [
                'name' => $this->getName($record),
                'author' => $this->getAuthor($record),
                'link' => $record->PLink,
                'image' => $this->getImage($record),
                'subjects' => $this->getSubjects($record)
            ];
        }

        return $items;

    }

    private function getName($record)
    {
        $name = '';
        try{
            $name = collect($record->Items)->where('Name', 'Title')->first()->Data;
        }catch(\Exception $e){

        }
        return $name;
    }

    private function getAuthor($record)
    {
        $author = '';
        try{
            $author = collect($record->Items)->where('Name', 'Author')->first()->Data;
        }catch(\Exception $e){

        }
        return $author;
    }

    private function getImage($record)
    {
        $image = '';
        try{
            $image = $record->ImageInfo[0]->Target;
        }catch(\Exception $e){

        }
        return $image;
    }

    private function getSubjects($record)
    {
        $subjects = [];
        try{
            $subjects = explode('<br />', html_entity_decode(collect($record->Items)->where('Name', 'Subject')->first()->Data));
        }catch(\Exception $e){

        }
        return $subjects;
    }

    private function getSourceTypes($response)
    {
        $results = json_decode($response->getBody());
        $types = [];
        try{
            $types = collect(collect($results->SearchResult->AvailableFacets)->where('Id', 'SourceType')->first()->AvailableFacetValues)->all();
            //dd($types);
        }catch(\Exception $e){
            dd($e->getMessage());
        }
        return $types;
    }

    private function getSubjectFacets($response)
    {
        $results = json_decode($response->getBody());
        //dd($results);
        $subjects = [];
        try{
            $subjects = collect(collect($results->SearchResult->AvailableFacets)->where('Id', 'SubjectEDS')->first()->AvailableFacetValues)->all();
            //dd($types);
        }catch(\Exception $e){
            dd($e->getMessage());
        }
        return $subjects;
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
