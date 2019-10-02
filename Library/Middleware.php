<?php

namespace Library;

abstract class Middleware{
    public abstract function handle(Request $request);
}
