<?php

class Core {

    // Function to validate the post data
    function validate_post($data)
    {
        /* Validating the hostname, the database name and the username. The password is optional. */
        return !empty($data['hostname']) && !empty($data['username']) && !empty($data['database']);
    }

    // Function to show an error
    function show_message($type,$message) {
        return $message;
    }

    // Function to write the config files
    function write_configs($data) {
        $db_written   = $this->write_db_config($data);
        $base_written = $this->write_base_config($data);

        return $db_written && $base_written;
    }

    // Function to write the base config file
    function write_base_config($data) {

        // Config path
        $template_path  = 'config/config.php';
        $output_path    = '../application/config/config.php';

        // Open the file
        $config_file = file_get_contents($template_path);

        $new  = str_replace("%BASEURL%",$data['baseurl'],$config_file);
        $new  = str_replace("%ENCRYPTKEY%",$data['encryptkey'],$new);
        $new  = str_replace("%COOKIEPREFIX%",$data['cookieprefix'],$new);
        $new  = str_replace("%COOKIEDOMAIN%",$data['cookiedomain'],$new);
        $new  = str_replace("%COOKIEPATH%",$data['cookiepath'],$new);
        $new  = str_replace("%CSRFTOKENNAME%",$data['csrftokenname'],$new);
        $new  = str_replace("%CSRFCOOKIENAME%",$data['csrfcookiename'],$new);

        $proddbupload = 'FALSE';
        if ($data['proddbupload'] == 1)
        {
            $proddbupload = 'TRUE';
        }

        $new  = str_replace("%PRODDBUPLOAD%",$proddbupload,$new);

        // Write the new database.php file
        $handle = fopen($output_path,'w+');

        // Chmod the file, in case the user forgot
        @chmod($output_path,0777);

        // Verify file permissions
        if(is_writable($output_path)) {

            // Write the file
            if(fwrite($handle,$new)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    } 

    // Function to write the db config file
    function write_db_config($data) {

        // Config path
        $template_path  = 'config/database.php';
        $output_path    = '../application/config/database.php';

        // Open the file
        $database_file = file_get_contents($template_path);

        $new  = str_replace("%HOSTNAME%",$data['hostname'],$database_file);
        $new  = str_replace("%USERNAME%",$data['username'],$new);
        $new  = str_replace("%PASSWORD%",$data['password'],$new);
        $new  = str_replace("%DATABASE%",$data['database'],$new);

        // Write the new database.php file
        $handle = fopen($output_path,'w+');

        // Chmod the file, in case the user forgot
        @chmod($output_path,0777);

        // Verify file permissions
        if(is_writable($output_path)) {

            // Write the file
            if(fwrite($handle,$new)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }
}