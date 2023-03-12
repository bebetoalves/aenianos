<?php

if (! function_exists('placekitten')) {
    function placekitten(int $width, int $height): string
    {
        return sprintf('https://placekitten.com/%d/%d', $width, $height);
    }
}

if (! function_exists('image_url')) {
    function image_url(string $path): string
    {
        if (str_contains($path, 'http')) {
            return $path;
        }

        return url(sprintf('%s/%s', 'storage', $path));
    }
}
