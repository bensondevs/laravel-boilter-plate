<?php

if (! function_exists('first_character')) {
    /**
     * Get the first character of a string
     *
     * @param string  $string
     * @return string
     */
    function first_character(string $string)
    {
        return substr($string, 0, 1);
    }
}

if (! function_exists('last_character')) {
    /**
     * Get the last character of a string
     *
     * @param string  $string
     * @return string
     */
    function last_character(string $string)
    {
        return substr($string, -1);
    }
}

if (! function_exists('concat_paths')) {
    /**
     * Concatenate paths.
     *
     * @param  array  $paths
     * @param  bool  $startSlash
     * @param  bool  $endSlash
     * @return string
     */
    function concat_paths(
        array $paths,
        bool $startSlash = false,
        bool $endSlash = false
    ): string {
        $paths = array_filter($paths);
        $paths = array_map(function ($path) {
            if (first_character($path) == '/') {
                $path = substr($path, 1);
            }

            // Remove / in last character
            if (last_character($path) == '/') {
                $path = substr($path, 0, -1);
            }

            return $path;
        }, $paths);

        $result = implode('/', $paths);

        if ($startSlash) $result = '/' . $result;
        if ($endSlash) $result .= '/';

        return $result;
    }
}

if (!function_exists('test_path')) {
    /**
     * Get the test folder relative path.
     *
     * @param string $path
     * @return string
     */
    function test_path(string $path = ''): string
    {
        return concat_paths(
            [base_path(), 'tests', $path],
            true,
            true
        );
    }
}

if (! function_exists('stub_path')) {
    /**
     * Get where the stub path.
     *
     * @param string $stubName
     * @return string
     */
    function stub_path(string $stubName): string
    {
        $stubFolderPath = __DIR__ . '/src/Stubs';
        
        return concat_paths([$stubFolderPath, $stubName]);
    }
}
