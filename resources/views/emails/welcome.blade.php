<!DOCTYPE html>

<html>

<head>
    <title>Welcome to Music Teachers Aid</title>
</head>

<body>

<h3>Welcome to Music Teachers Aid</h3>

<p>Hey {{$user['first_name']}},</p>

<p>We are excited that you signed up for Music Teachers Aid.</p>

<p>Music Teachers Aid is built by a former music teacher and Berklee College of Music attendee.</p>

<p>We hope that you love our software as much as we do.</p>

<p>If you have any questions or concerns please use the support link after you log in under your profile.</p>

<p>Your registered email id is {{$user['email']}}</p>

<p>To unlock the full potential of the software please sign up for a <a href="{{ route('account.subscription') }}" target="_blank">Premium Account</a> to unlock all the system features</p>

</body>

</html>