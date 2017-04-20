
<h1>HTML Injection</h1>

<p>On the login page, the form action uses PHP_SELF without quoting <em>and</em> HTML encoding the value.</p>

<p>Evil site owner sends <a href="/security/login.php/%22%3E%3Cscript%20src=%22/security/uploads/profile-pictures/666.js%22%3E%3C/script%3E%3Cf">this link</a> to the admin.</p>
