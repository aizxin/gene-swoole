<?php
$router = new \gene\router();
$router->clear()
    ->get("/", "\Controllers\Home\Index@index")
    ->group("/admin")
        ->get("/", "\Controllers\Admin\Index@index")
        ->post("/auth/login", "\Controllers\Admin\Auth@login")
        ->post("/auth/user", "\Controllers\Admin\Auth@user")
    ->group()
    ->get(401, "\Controllers\Controller@error401")
    ->error(404, "\Controllers\Controller@error404");
