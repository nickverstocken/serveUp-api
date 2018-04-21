<!DOCTYPE html>
<html lang="nl-BE">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Hallo {{ $username}},
    <br>
    Bedankt om je te registreren bij Serve-Up. Voor je begint vergeet niet je email adres te verifiëren!
    <br>
    Klik op de link hieronder of kopieer hem in de adres balk van je browser om je email te verifiëren
    Please click on the link below or copy it into the address bar of your browser to confirm your email address:
    <br>

    <a href="{{ url('user/verify', $verification_code)}}">Confirm my email address </a>

    <br/>
    Vele groetjes,
    Het Serve-Up team!
</div>

</body>
</html>