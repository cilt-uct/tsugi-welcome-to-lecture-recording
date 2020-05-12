# Tsugi: Welcome to Lecture Recording

A page to introduce lecture recording to a course site and help with the initial configuration.

To execute scripts add the folloign to visuod:
```
wwwrun ALL=(ALL) NOPASSWD: /usr/local/scripts/tsugi/tsugi-oc-remove.pl
wwwrun ALL=(ALL) NOPASSWD: /usr/local/scripts/tsugi/tsugi-oc-setup.pl
```

Requires the scripts to be set in visudo and the tmp folder to have wwwrun ownership