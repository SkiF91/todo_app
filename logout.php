<?php
require_once('init.php');
unset(CustomVars::$SESSION->user_session);
unset(CustomVars::$SESSION->user_remote_addr);
unset(CustomVars::$SESSION->user_id);
CustomVars::$current_user = null;

redirect_to_login_if_needed();