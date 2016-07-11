@extends('shared.app')
@section('content')
<div id="mt">
    <img src="{{ asset('assets/images/logo_header.png') }}">
    <p>Сервера ABC Кабинета находится на <strong>обслуживании</strong>.<br/>Приносим извинения за временные неудобства,<br/>это продлится совсем <strong>недолго</strong>.</p>
</div>
<style>
    #mt {
        width: 440px;
        padding: 20px;
        height: 200px;
        background: #fff;
        text-align: center;
        position: fixed;
        top: 50%;
        left: 50%;
        margin-left: -230px;
        margin-top: -110px;
        border: 2px solid #eee;
        box-sizing: border-box;
    }

    #mt > img {
        margin-bottom: 20px;
    }
</style>
@endsection