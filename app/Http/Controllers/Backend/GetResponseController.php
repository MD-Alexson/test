<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Project;
use Session;
use GetResponse;
use Request;

class GetResponseController extends Controller {

    private $messages = [
    ];

    public function index() {
        $data['title'] = 'Getresponse';
        $project = Project::findOrFail(Session::get('selected_project'));

        if ($project->gr_api_key) {
            $getresponse = $this->index_gr_data($project);
            if ($getresponse) {
                return view('backend.getresponse.index')->with('data', $data)->with('project', $project)->with('getresponse', $getresponse);
            } else {
                return view('backend.getresponse.index')->with('data', $data)->with('project', $project);
            }
        }

        return view('backend.getresponse.index')->with('data', $data)->with('project', $project);
    }

    public function settings() {
        $project = Project::findOrFail(Session::get('selected_project'));

        $project->gr_api_key = htmlspecialchars(Request::get('api_key'));
        $project->gr_domain = htmlspecialchars(Request::get('domain'));
        $project->gr_api_url = htmlspecialchars(Request::get('api_url'));

        $project->save();

        return redirect('/getresponse');
    }

    public function campaigns() {
        $camps = Request::input('camp_id');
        foreach ($camps as $level_id => $camp_id) {
            $level = \App\Level::findOrFail($level_id);

            if ($level->gr_campaign !== $camp_id) {
                $old_camp_id = $level->gr_campaign;
                $level->gr_campaign = $camp_id;
                $level->save();

                $this->sync_contacts($level, $camp_id, $old_camp_id);
            }
        }
        return redirect('/getresponse');
    }

    public function sendByLevel() {
        $level = \App\Level::findOrFail((int) Request::input('level_id'));
        $camp_id = $level->gr_campaign;

        $subject = htmlspecialchars(Request::input('subject'));
        $message = htmlentities(Request::input('text'));

        $project = Project::findOrFail(Session::get('selected_project'));
        $getresponse = new GetResponse($project->gr_api_key);
        if (strlen($project->gr_domain)) {
            $getresponse->enterprise_domain = $project->gr_domain;
            $getresponse->api_url = "https://api3.getresponse360." . $project->gr_api_url . "/v3";
        }

        $contacts_obj = $getresponse->getContacts(array(
            'query' => array(
                'campaignId' => $camp_id,
            ),
            'fields' => 'contactId'
        ));
        $contacts = Array();
        foreach ($contacts_obj as $id) {
            array_push($contacts, $id->contactId);
        }

        $from_fields = (Array) $getresponse->getFromFields();
        $from = $from_fields[0]->fromFieldId;
        foreach ($from_fields as $field) {
            if ($field->isDefault === "true") {
                $from = $field->fromFieldId;
                break;
            }
        }

        $params = Array(
            "subject" => $subject,
            "fromField" => Array(
                "fromFieldId" => $from
            ),
            "campaign" => Array(
                "campaignId" => $camp_id
            ),
            "content" => Array(
                "html" => null,
                "plain" => $message
            ),
            "sendSettings" => Array(
                "timeTravel" => false,
                "perfectTiming" => true,
                "selectedCampaigns" => Array($camp_id),
                "selectedSegments" => Array(),
                "selectedSuppressions" => Array(),
                "excludedCampaigns" => Array(),
                "excludedSegments" => Array(),
                "selectedContacts" => $contacts
            )
        );
        $response = $getresponse->sendNewsletter($params);
        dd($response);
//        return $response;
    }

    /* -- */

    public function test() {
        $api_key = Request::get('api_key');
        $domain = Request::get('domain');
        $api_url = "https://api3.getresponse360." . Request::get('api_url') . "/v3";

        $getresponse = new GetResponse($api_key);
        if (strlen($domain)) {
            $getresponse->enterprise_domain = $domain;
            $getresponse->api_url = $api_url;
        }

        $data = $getresponse->accounts();
        if (isset($data->accountId) && strlen($data->accountId)) {
            return 1;
        } else {
            return 0;
        }
    }

    private function index_gr_data($project) {
        $getresponse = new GetResponse($project->gr_api_key);
        if (strlen($project->gr_domain)) {
            $getresponse->enterprise_domain = $project->gr_domain;
            $getresponse->api_url = "https://api3.getresponse360." . $project->gr_api_url . "/v3";
        }

        $response['account'] = $getresponse->accounts();
        if (!isset($response['account']->accountId)) {
            return false;
        }

        $response['campaigns'] = (Array) $getresponse->getCampaigns();

        return $response;
    }

    private function sync_contacts($level, $camp_id, $old_camp_id) {
        $project = Project::findOrFail(Session::get('selected_project'));
        $getresponse = new GetResponse($project->gr_api_key);
        if (strlen($project->gr_domain)) {
            $getresponse->enterprise_domain = $project->gr_domain;
            $getresponse->api_url = "https://api3.getresponse360." . $project->gr_api_url . "/v3";
        }

        $getresponse->updateCampaign($camp_id, Array(
            "optinTypes" => Array(
                "api" => "single"
            )
        ));
        foreach ($level->susers as $user) {
            if (strlen($old_camp_id)) {
                $old = $getresponse->getContacts(array(
                    'query' => array(
                        'email' => $user->email,
                        'campaignId' => $old_camp_id),
                    'fields' => 'contactId'
                ));

                $old = (Array) $old;
                $old_contact_id = $old[0]->contactId;
                $getresponse->deleteContact($old_contact_id);
            }

            if (strlen($camp_id)) {
                $getresponse->addContact(Array(
                    'name' => $user->name,
                    'email' => $user->email,
                    'campaign' => Array(
                        "campaignId" => $camp_id
                    )
                ));
            }
        }
    }

}
