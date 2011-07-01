<?php
class sdTotalPrivacy extends spidersDesign_pluginFramework_1
{
    public function settingsPage( $page )
    {
        $this->settings->heading( __( "Enable password protection") );
            $this->settings->checkbox( "enabled", array(
                "help" => __( "Use this to disable/enable password protection" )
            ) );
            
        $this->settings->heading( __( "Set password/s" ) );
            $this->settings->password( "password", array(
                "help" => __( "Password to be used to authenticate visitors" )
            ) );
            $this->settings->checkbox( "multiple_passwords", array(
                "Use this to enable multiple passwords - lets you define up to 10 different passwords"
            ) );
                $
    }
}
?>