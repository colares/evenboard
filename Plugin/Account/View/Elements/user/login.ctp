<?php
if(!$this->Session->read('Auth.User')){
    if(
        $this->params->controller != 'users'
        ||
        $this->params->action != 'login'
    ){
        echo $this->element('Account.user/login_content');
    } // end if
} else {
    echo $this->element('user/profile_data');
}
?>