<?php

function usersFiltered(){
    $return = false;
    if(Session::get('users_filter_created_at') !== -1) {
        $return = true;
    }
    if(Session::get('users_filter_created_at_from') !== "") {
        $return = true;
    }
    if(Session::get('users_filter_created_at_to') !== "") {
        $return = true;
    }
    if(Session::get('users_filter_plan_id') !== -1) {
        $return = true;
    }
    if(Session::get('users_filter_payment_term') !== -1) {
        $return = true;
    }
    if(Session::get('users_filter_status') !== -1) {
        $return = true;
    }
    return $return;
}