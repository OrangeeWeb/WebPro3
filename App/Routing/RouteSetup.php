<?php

/**
*   Direct Setup
*   Direct::[to, post](url, controller@method)
*/

Direct::get("/test/{id}", 'MainController@index');


Direct::get("/login", 'LoginController@index');
Direct::post("/login", 'LoginController@post');


Direct::get("/", 'MainController@test');


//Direct::err("404", 'MainController@error');