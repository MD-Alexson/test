<?php

function schedule() {
    $now = getTime();
    $categories = App\Category::all();
    foreach ($categories as $cat) {
        if ($cat->status === 'scheduled' && $now >= $cat->scheduled) {
            $cat->status = 'published';
            $cat->save();
        }
    }
    $posts = App\Post::all();
    foreach ($posts as $post) {
        if ($post->status === 'scheduled' && $now >= $post->scheduled) {
            $post->status = 'published';
            if ($post->stripe === "soon") {
                $post->stripe = null;
            }
            $post->save();
        }
    }
    $susers = App\Suser::all();
    foreach ($susers as $suser) {
        if ($suser->expire && $now >= $suser->expires) {
            $suser->status = false;
            $suser->save();
        }
    }
    $users = App\User::all();
    foreach ($users as $user) {
        if ($now >= $user->expires) {
            $user->status = false;
            $user->save();
        }
    }
}

function scheduleDaily() {
    $users = \App\User::orderBy('created_at', 'desc')->get();
    $now = getTime();
    foreach ($users as $user) {
        $num = (int) floor(($user->expires - $now) / 60 / 60 / 24);
        if ($num >= 3 && $num < 4) {
            \Mail::send('emails.pay3days', ['user' => $user], function ($m) use ($user) {
                $m->from('postmaster@abckabinet.ru', 'ABC Кабинет');
                $m->to($user->email, $user->name)->subject('Продление аккаунта ABC Кабинет');
            });
        } elseif ($num >= 1 && $num < 2) {
            \Mail::send('emails.pay1day', ['user' => $user], function ($m) use ($user) {
                $m->from('postmaster@abckabinet.ru', 'ABC Кабинет');
                $m->to($user->email, $user->name)->subject('Продление аккаунта ABC Кабинет');
            });
        } elseif ($num >= -1 && $num < 0) {
            \Mail::send('emails.payday', ['user' => $user], function ($m) use ($user) {
                $m->from('postmaster@abckabinet.ru', 'ABC Кабинет');
                $m->to($user->email, $user->name)->subject('Продление аккаунта ABC Кабинет');
            });
        }
    }
}

function getDomainByRemote() {
    $project = App\Project::where('remote_domain', Request::getHost())->select('domain')->first();
    if (!$project) {
        abort(404);
    }
    return $project->domain;
}

function prep_url($str = '') {
    if ($str === 'http://' OR $str === '') {
        return '';
    }
    $url = parse_url($str);
    if (!$url || !isset($url['scheme'])) {
        return 'http://' . $str;
    }
    return $str;
}

function cmp($a, $b) {
    if($a->order < $b->order){
        return -1;
    } else if ($b->order < $a->order){
        return 1;
    } else {
        return 0;
    }
}
