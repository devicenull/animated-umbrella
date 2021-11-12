
This is a fork of the [trunk-recorder](https://github.com/robotastic/trunk-recorder) audioplayer.php that's been modified to work a little better.

New features include:

* Live-updating when new calls are added
* Ability to select multiple talkgroups


No idea how long I'll be interested in maintaining this


Installing
==========

* Check out the code, run `composer install`
* Create a config.php like the following:

```
define('DB_HOST', '127.0.0.1');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');

define('CONFIG_FILE', __DIR__.'/../configs/config.json');

/**
*   This allows you to remove the leading portion of a directory name, so you can use
*   absolute paths in the config file, and not have to match that structure in your web server
*
*   For example, if your recordings are in `/home/trunkrecorder/audio_files`, you'd set this to
*   "/home/trunkrecorder/", and define a location block in the nginx config like so:
*    location /audio_files/ {
*        root /home/trunkrecorder/;
*    }
*   Which would let you put your document root anywhere, and not require serving up the entire
*   trunk-recorder directory.
*/
define('HTTP_BASE_PATH', '/home/trunkrecorder');

```
* A MySQL database is currently required (but unused).  CONFIG_FILE should be the path to your trunk-recorder config.json
