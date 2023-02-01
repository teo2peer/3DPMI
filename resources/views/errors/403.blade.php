<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/errors/403/style.css">
    <title>Error 403</title>
</head>

<body>
    <h1>403</h1>
    <div>
        <p>> <span>ERROR CODE</span>: "<i>{{$error ?? "HTTP 403 Forbidden"}}</i>"</p>
        <p>> <span>ERROR DESCRIPTION</span>: "<i>{{ $description ?? "Access Denied. You Do Not Have The Permission To
                Access This Page On
                This Server"}}</i>"</p>
        <p>> <span>ERROR POSSIBLY CAUSED BY</span>: [<b> {{ $message ?? "execute access forbidden, read access
                forbidden, write access
                forbidden, ssl required, ssl 128 required, ip address rejected, client certificate required, site access
                denied, too many users, invalid configuration, password change, mapper denied access, client certificate
                revoked, directory listing denied, client access licenses exceeded, client certificate is untrusted or
                invalid, client certificate has expired or is not yet valid, passport logon failed, source access
                denied, infinite depth is denied, too many requests from the same client ip"}}</b>...]</p>
        <p>> <span>SOME PAGES ON THIS SERVER THAT YOU DO HAVE PERMISSION TO ACCESS</span>: [<a href="/">Home Page</a>]
        </p>
        <p>> <span>HAVE A NICE DAY :-)</span></p>
    </div>
    <script type="text/javascript" src="/assets/errors/403/script.js"></script>
</body>

</html>