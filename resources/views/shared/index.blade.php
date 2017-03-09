<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css" rel="stylesheet">
        
        <style>
            body {
                font-family: "Roboto";
                box-sizing: border-box;
            }
            table {
                width: 100%;
                padding: 40px;
                box-sizing: border-box;
                word-break: break-all;
            }
            table td, table th {
                border: 1px solid #aaa;
                padding: 10px 20px;
                box-sizing: border-box;
            }
            table td:first-child, table td:last-child {
                text-align: center;
                font-weight: 700;
            }
            a.remove {
                color: #f00;
                font-size: 30px;
                text-decoration: none;
            }
            tr.divider {
                background-color: #aaa;
            }
            tr.divider td {
                padding-top: 40px;
            }
            #form input[type=text]{
                width: 100%;
                padding-top: 10px;
                padding-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <table>
            <thead>
            <col width="5%">
            <col width="30%">
            <col width="60%">
            <col width="5%">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Comment</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @if(count($data))
                @foreach($data as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>{{ $d->name }}</td>
                    <td>@if(strlen($d->comment)) {{ $d->comment }} @else — @endif</td>
                    <td><a href="/delete/{{ $d->id }}" class="remove">&times;</a></td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                </tr>
                @endif
                <tr class="divider">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr id="form">
                    <td>AUTO</td>
                    <td><input type="text" name="name" required="required" maxlength="32" placeholder="Name" form="store"></td>
                    <td><input type="text" name="comment" maxlength="255" placeholder="Comment" form="store"></td>
                    <td><input type="submit" value="ADD" form="store"></td>
                </tr>
            </tbody>
        </table>
        <form action="/store" method="POST" id="store"></form>
    </body>
</html>