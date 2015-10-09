<?php

    require_once("constants.php");

    function apologize($message){
        render("apology.php", ["message" => $message]);
        exit;
    }

    function dump($variable){
        require("../templates/dump.php");
        exit;
    }

    function logout(){
        $_SESSION = [];

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        session_destroy();
    }

    function query(){
        $sql = func_get_arg(0);

        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle)) {
            try {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER.";charset=utf8", USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
                
            } catch (Exception $e) {
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        $statement = $handle->prepare($sql);
        if ($statement === false) {
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            return false;
        }
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination) {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination)) {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

    function render($template, $values = []) {
        // if template exists, render it
        if (file_exists("../templates/$template")){
            // extract variables into local scope
            extract($values);

            require("../templates/header.php");
            require("../templates/$template");
            require("../templates/footer.php");
        }

        else {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }

?>