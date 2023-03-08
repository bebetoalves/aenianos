<?php

if (! function_exists('placekitten')) {
    function placekitten(int $width, int $height): string
    {
        return sprintf('//placekitten.com/g/%d/%d', $width, $height);
    }
}
