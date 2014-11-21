<?php

    namespace Theme\Functions;

    class Path 
    {

        function __construct() 
        {
            $this->template = \trailingslashit(\get_template_directory_uri());
        }

        function template()
        {
            return \trailingslashit(\get_template_directory_uri());
        }
    }
