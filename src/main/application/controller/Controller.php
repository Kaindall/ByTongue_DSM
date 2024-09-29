<?php

interface Controller {
    public function fallback(RequestHandler $request);
}