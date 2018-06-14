<?php

class AdminController extends Controller {
    public function render() {
        if(!Session::isUserAllowedForAdmin()) {
            Route::rerouteToController("Main");
        }
        parent::render();
    }
}