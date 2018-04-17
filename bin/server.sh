#!/bin/sh

## Part of CodeIgniter Composer Installer
##
## @author     Kenji Suzuki <https://github.com/kenjis>
## @license    MIT License
## @copyright  2015 Kenji Suzuki
## @link       https://github.com/kenjis/codeigniter-composer-installer

cd `dirname $0`/..
export SENDGRID_API_KEY=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
/c/xampp5.6/php/php -S 127.0.0.1:8000 -t public -f bin/router.php
