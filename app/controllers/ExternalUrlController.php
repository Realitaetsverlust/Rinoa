<?php

class ExternalUrlController extends Controller {
    public function loadExternalUrl($externalUrl) {
        header("Location: ".$externalUrl);
        exit();
    }
}