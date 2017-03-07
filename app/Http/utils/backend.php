<?php

function getPreviewLink($type, $id) {
    $sid = \Session::getId();
    if ($type === "project") {
        $project = App\Project::findOrFail($id);
        if (!empty($project->remote_domain)) {
            return "http://" . $project->remote_domain . '/?sid=' . $sid;
        } else {
            return "http://" . $project->domain . "." . config('app.domain') . "/?sid=" . $sid;
        }
    } elseif ($type === 'category') {
        $category = App\Category::findOrFail($id);
        $project = $category->project;
        if (!empty($project->remote_domain)) {
            return "http://" . $project->remote_domain . '/categories/' . $category->id . '/?sid=' . $sid;
        } else {
            return "http://" . $project->domain . "." . config('app.domain') . '/categories/' . $category->id . '/?sid=' . $sid;
        }
    } elseif ($type === 'post') {
        $post = App\Post::findOrFail($id);
        $project = $post->category->project;
        if (!empty($project->remote_domain)) {
            return "http://" . $project->remote_domain . '/posts/' . $post->id . '/?sid=' . $sid;
        } else {
            return "http://" . $project->domain . "." . config('app.domain') . '/posts/' . $post->id . '/?sid=' . $sid;
        }
    } elseif ($type === 'webinar') {
        $web = App\Webinar::findOrFail($id);
        $project = $web->project;
        if (!empty($project->remote_domain)) {
            return "http://" . $project->remote_domain . '/webinar/' . $web->url;
        } else {
            return "http://" . $project->domain . "." . config('app.domain') . '/webinar/' . $web->url;
        }
    }
}

function sortDefaults() {
    if (!\Session::has('sort.users.order_by') || empty(\Session::get('sort.users.order_by'))) {
        \Session::put('sort.users.order_by', 'created_at');
    }
    if (!\Session::has('sort.users.order') || empty(\Session::get('sort.users.order'))) {
        \Session::put('sort.users.order', 'desc');
    }
    if (!\Session::has('sort.posts_all.order_by') || empty(\Session::get('sort.posts_all.order_by'))) {
        \Session::put('sort.posts_all.order_by', 'order_all');
    }
    if (!\Session::has('sort.posts_all.order') || empty(\Session::get('sort.posts_all.order'))) {
        \Session::put('sort.posts_all.order', 'asc');
    }
    if (!\Session::has('sort.posts.order_by') || empty(\Session::get('sort.posts.order_by'))) {
        \Session::put('sort.posts.order_by', 'order');
    }
    if (!\Session::has('sort.posts.order') || empty(\Session::get('sort.posts.order'))) {
        \Session::put('sort.posts.order', 'asc');
    }
    if (!\Session::has('sort.categories.order_by') || empty(\Session::get('sort.categories.order_by'))) {
        \Session::put('sort.categories.order_by', 'order');
    }
    if (!\Session::has('sort.categories.order') || empty(\Session::get('sort.categories.order'))) {
        \Session::put('sort.categories.order', 'asc');
    }
}

function getAvaibleIprKey($ipr_level_id) {
    $ipr_level = \App\IprLevel::findOrFail($ipr_level_id);
    $result = $ipr_level->ipr_keys()->first();
    return $result;
}

function grAddContact($user) {
    $project = $user->project;
    if (!strlen($project->gr_api_key)) {
        return false;
    }
    $getresponse = new GetResponse($project->gr_api_key);
    if (strlen($project->gr_domain)) {
        $getresponse->enterprise_domain = $project->gr_domain;
        $getresponse->api_url = "https://api3.getresponse360." . $project->gr_api_url . "/v3";
    }

    $test = $getresponse->accounts();
    if (!isset($test->accountId) || !strlen($test->accountId)) {
        return false;
    }

    $camp_id = $user->level->gr_campaign;
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

function grUpdateContact($user, $oldCamp) {
    $project = $user->project;
    if (!strlen($project->gr_api_key)) {
        return false;
    }
    $getresponse = new GetResponse($project->gr_api_key);
    if (strlen($project->gr_domain)) {
        $getresponse->enterprise_domain = $project->gr_domain;
        $getresponse->api_url = "https://api3.getresponse360." . $project->gr_api_url . "/v3";
    }

    $test = $getresponse->accounts();
    if (!isset($test->accountId) || !strlen($test->accountId)) {
        return false;
    }

    $camp_id = $user->level->gr_campaign;
    if ($camp_id === $oldCamp) {
        return false;
    }

    $old = (Array) $getresponse->getContacts(array(
                'query' => array(
                    'email' => $user->email,
                    'campaignId' => $oldCamp),
                'fields' => 'contactId'
    ));

    if (isset($old[0]) && !empty($old[0])) {
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

function grDeleteContact($user) {
    $project = \App\Project::findOrFail(Session::get('selected_project'));
    if (!strlen($project->gr_api_key)) {
        return false;
    }
    $getresponse = new GetResponse($project->gr_api_key);
    if (strlen($project->gr_domain)) {
        $getresponse->enterprise_domain = $project->gr_domain;
        $getresponse->api_url = "https://api3.getresponse360." . $project->gr_api_url . "/v3";
    }

    $test = $getresponse->accounts();
    if (!isset($test->accountId) || !strlen($test->accountId)) {
        return false;
    }

    $camp_id = $user->level->gr_campaign;
    if (strlen($camp_id)) {
        $old = (Array) $getresponse->getContacts(array(
                    'query' => array(
                        'email' => $user->email,
                        'campaignId' => $camp_id),
                    'fields' => 'contactId'
        ));

        if (isset($old[0]) && !empty($old[0])) {
            $old_contact_id = $old[0]->contactId;
            $getresponse->deleteContact($old_contact_id);
        }
    }
}
