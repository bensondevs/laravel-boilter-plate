<?php

use Faker\Factory;
use Illuminate\Support\Str;

/**
 * Get the last character of a string
 *
 * @param string $string
 * @return string
 */
if (!function_exists('last_character')) {
    function last_character(string $string)
    {
        return substr($string, -1);
    }
}

/**
 * Check if last character of string
 * is match specified string
 *
 * @param string $string
 * @param string $match
 * @return  bool
 */
if (!function_exists('is_last_character')) {
    function is_last_character(string $string, string $match)
    {
        return last_character($string) === $match;
    }
}

/**
 * Get the first character of a string
 *
 * @param string $string
 * @return string
 */
if (!function_exists('first_character')) {
    function first_character(string $string)
    {
        return substr($string, 0, 1);
    }
}

/**
 * Check if first character of string
 * is match specified string
 *
 * @param string $string
 * @param string $match
 * @return  bool
 */
if (!function_exists('is_first_character')) {
    function is_first_character(string $string, string $match): bool
    {
        return first_character($string) === $match;
    }
}

/**
 * Check if string starts with certain string
 *
 * @param string $string
 * @param string $match
 * @return bool
 */
if (!function_exists('is_str_starts_with')) {
    function is_str_starts_with(string $string, string $match): bool
    {
        return substr($string, 0, strlen($match)) === $match;
    }
}

/**
 * Check if string ends with certain string
 *
 * @param string $string
 * @param string $match
 * @return bool
 */
if (!function_exists('is_str_ends_with')) {
    function is_str_ends_with(string $string, string $match): bool
    {
        return substr($string, -(strlen($match))) === $match;
    }
}

if (!function_exists('is_str_hashed')) {
    function is_str_hashed(string $string): bool
    {
        return strlen($string) == 60 &&
            preg_match('/^\$2y\$/', $string);
    }
}

/**
 * Check if string has uppercase character
 *
 * @param string $string
 * @return bool
 */
if (!function_exists('string_has_uppercase')) {
    function string_has_uppercase(string $string)
    {
        return preg_match('/[A-Z]/', $string);
    }
}

/**
 * Check if string has numeric character
 *
 * @param string $string
 * @return bool
 */
if (!function_exists('string_has_numeric')) {
    function string_has_numeric(string $string)
    {
        return preg_match('~[0-9]+~', $string);
    }
}

/**
 * Check if string has special character
 *
 * @param string $string
 * @return bool
 */
if (!function_exists('string_has_special_char')) {
    function string_has_special_char(string $string)
    {
        return preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string);
    }
}

/**
 * Strip all spaces from string
 *
 * @param string $string
 * @return string
 */
if (!function_exists('strip_spaces')) {
    function strip_spaces(string $string)
    {
        return str_replace(' ', '', $string);
    }
}

/**
 * Convert string to singular version
 *
 * @param string $string
 * @return string
 */
if (!function_exists('str_to_singular')) {
    function str_to_singular(string $string)
    {
        return Str::singular($string);
    }
}

/**
 * Convert string to plural version
 *
 * @param string $string
 * @return string
 */
if (!function_exists('str_to_plural')) {
    function str_to_plural(string $string)
    {
        return Str::plural($string);
    }
}

/**
 * Convert string to snake_case
 *
 * @param string $string
 * @return string
 */
if (!function_exists('str_snake_case')) {
    function str_snake_case(string $string)
    {
        return Str::snake($string);
    }
}

/**
 * Convert string to camelCase
 *
 * @param string $string
 * @return string
 */
if (!function_exists('str_camel_case')) {
    function str_camel_case(string $string)
    {
        return Str::camel($string);
    }
}

/**
 * Convert string to PascalCase
 *
 * @param string $string
 * @return string
 */
if (!function_exists('str_pascal_case')) {
    function str_pascal_case(string $string)
    {
        return ucfirst(Str::camel($string));
    }
}

/**
 * Convert snake case to normal case
 *
 * @param string $snakeCase
 * @return string
 */
if (!function_exists('snake_to_normal_case')) {
    function snake_to_normal_case(string $snakeCase)
    {
        return ucwords(str_replace('_', ' ', $snakeCase));
    }
}

/**
 * Convert snake case to camel case
 *
 * @param string $snakeCase
 * @param bool $capitalizeFirstChar
 */
if (!function_exists('snake_to_camel')) {
    function snake_to_camel(
        string $snakeCase,
        bool   $capitalizeFirstChar = false
    )
    {
        $spacedString = str_replace('_', ' ', $snakeCase);
        $capitalized = ucwords($spacedString);
        $result = str_replace(' ', '', $capitalized);

        if (!$capitalizeFirstChar) {
            $result[0] = strtolower($result[0]);
        }

        return $result;
    }
}

/**
 * Convert string to slugged string
 *
 * @param string $string
 * @return string
 */
if (!function_exists('sluggify')) {
    function sluggify(string $string)
    {
        return Str::slug($string);
    }
}

/**
 * Convert boolean written in string to real boolean
 *
 * @param mixed $string
 * @return bool
 */
if (!function_exists('strtobool')) {
    function strtobool($string = null)
    {
        if ($string === null) {
            return false;
        }

        if ($string == 'true' || $string == 'false') {
            return filter_var($string, FILTER_VALIDATE_BOOLEAN);
        }

        if ($string == '1' || $string == '0') {
            return boolval($string);
        }

        return true;
    }
}

/**
 * Generate random string by specified
 * amount of characters. Default amount is 5
 *
 * @param int $length
 * @return string
 */
if (!function_exists('random_string')) {
    function random_string(int $length = 5)
    {
        return Str::random($length);
    }
}

/**
 * Alias for "random_string" function for camel case version
 *
 * @param int $length
 * @return  string
 */
if (!function_exists('randomString')) {
    function randomString(int $length = 5)
    {
        return random_string($length);
    }
}

/**
 * Create random email address
 *
 * @return string
 */
if (!function_exists('random_email')) {
    function random_email()
    {
        $email = random_string(10) . '@' . random_string(10);

        return $email . '.' . random_string(3);
    }
}

/**
 * Random alphabeths genetor
 *
 * @param int $length
 * @return  string
 */
if (!function_exists('random_alphabeth')) {
    function random_alphabeth(int $length = 5)
    {
        if ($length <= 0) return ''; // Recursive breaker

        $chars = [
            // Uppercase
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
            'W', 'X', 'Y', 'Z',

            // Lowercase
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
            'w', 'x', 'y', 'Z'
        ];
        $index = rand(0, (count($chars) - 1));

        return $chars[$index] . random_alphabeth($length - 1);
    }
}

/**
 * Random numeric string generator
 *
 * @param int $length
 * @return string
 */
if (!function_exists('random_num_str')) {
    function random_num_str(int $length = 5)
    {
        $randomNum = '';
        for ($count = 0; $count < $length; $count++) {
            $randomNum .= (string)rand(0, 9);
        }

        return $randomNum;
    }
}

/**
 * Search string in array
 *
 * @param array $pool
 * @param string $keyword
 * @return  mixed
 */
if (!function_exists('array_str_search')) {
    function array_str_search(array $pool, string $keyword)
    {
        foreach ($pool as $element) {
            if (stripos($element, $keyword) !== false)
                return $element;
        }
    }
}

/**
 * Extract file name from the full path string
 *
 * @param string $fullPath
 * @return string
 */
if (!function_exists('extract_filename')) {
    function extract_filename(string $fullPath = '')
    {
        $paths = explode('/', $fullPath);

        return $paths[count($paths) - 1];
    }
}

if (!function_exists('extract_file_extension')) {
    /**
     * Extract file extension from filename.
     *
     * @param string $fileName
     * @return string
     */
    function extract_file_extension(string $fileName): string
    {
        $explode = explode('.', $fileName);

        return $explode[count($explode) - 1];
    }
}

/**
 * Concat paths
 *
 * @param array $paths
 * @param bool $startSlash
 * @param bool $endSlash
 * @return string
 */
if (!function_exists('concat_paths')) {
    function concat_paths(
        array $paths,
        bool  $startSlash = false,
        bool  $endSlash = false
    ): string
    {
        $paths = array_map(function ($path) {
            // Remove / in first character
            if ($path) {
//                if (first_character($path) == '/') {
//                    $path = substr($path, 1);
//                }

                // Remove / in last character
                if (last_character($path) == '/') {
                    $path = substr($path, 0, -1);
                }
            }


            return $path;
        }, $paths);

        $result = implode('/', $paths);

        if ($startSlash) $result = '/' . $result;
        if ($endSlash) $result .= '/';

        return $result;
    }
}

/**
 * Decode base32 string value
 *
 * @param string $secret
 * @return string
 */
if (!function_exists('decode_base32')) {
    function decode_base32(string $secret, array $base32Chars = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
        'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
        '=',  // padding char
    ]): bool|string
    {
        // Make sure the secret is not empty
        // If yes, then return empty string as well
        if (empty($secret)) return '';

        $flippedChars = array_flip($base32Chars);
        $paddCharCount = substr_count($secret, $base32Chars[32]);
        $allowedValues = [6, 4, 3, 1, 0];

        // Make sure that padding char count is in allowed
        if (!in_array($paddCharCount, $allowedValues)) {
            return false;
        }

        // Make sure the padding left and right of the code is equal
        for ($index = 0; $index < count($allowedValues); $index++) {
            if ($paddCharCount === $allowedValues[$index]) {
                if (
                    substr($secret, -($allowedValues[$index]))
                    !==
                    str_repeat($base32Chars, $allowedValues[$index])
                ) {
                    return false;
                }
            }
        }

        // Prepare secret by the string conversions
        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);

        // Build binary string of base32
        $binStr = '';
        for ($index = 0; $index < count($secret); $index++) {
            // Make sure the strng is in base32 allowed chars
            if (!in_array($secret[$index], $base32Chars)) {
                return false;
            }

            // Temporary variable
            $x = '';
            for ($subIndex = 0; $subIndex < 8; $subIndex++) {
                $x .= str_pad(base_convert(@$flippedChars[@$secret[$index + $subIndex]], 10, 2), 5, '0', STR_PAD_LEFT);
            }

            // Eight bit strings
            $eightBitStrs = str_split($x, 8);
            for ($bitIndex = 0; $bitIndex < count($eightBitStrs); $bitIndex++) {
                $binStr .= (($y = chr(base_convert($eightBitStrs[$bitIndex], 2, 10))) || ord($y) == 48) ? $y : '';
            }
        }

        return $binStr;
    }
}

if (!function_exists('htmlentity_replace')) {
    /**
     * Replace template message HTML entities with supplied value.
     *
     * Set the needle value with HTML entity that will be replaced.
     * Put the second argument with desired value.
     * And lastly put the whole text in the last argument.
     *
     * @param string $needle
     * @param string $value
     * @param string $haystack
     * @return string
     */
    function htmlentity_replace(
        string $needle,
        string $value,
        string $haystack
    ): string
    {
        return str_replace(
            htmlentities($needle),
            $value,
            $haystack
        );
    }
}

/**
 * Return the sign of '<' for more humanize code.
 *
 * @return string
 */
if (!function_exists('less_than')) {
    function less_than(): string
    {
        return '<';
    }
}

/**
 * Return the sign of `<=` for more humanize code.
 *
 * @return string
 */
if (!function_exists('less_or_equal_than')) {
    function less_or_equal_than(): string
    {
        return '<=';
    }
}

/**
 * Return the sign of `==` for more humanize code.
 *
 * @return string
 */
if (!function_exists('equal_than')) {
    function equal_than(): string
    {
        return '==';
    }
}

/**
 * Return the sign of `>` for more humanize code.
 *
 * @return string
 */
if (!function_exists('more_than')) {
    function more_than(): string
    {
        return '>';
    }
}

/**
 * Return the sign of `>=` for more humanize code.
 *
 * @return string
 */
if (!function_exists('more_or_equal_than')) {
    function more_or_equal_than(): string
    {
        return '>=';
    }
}

/**
 * Return the sign of `!=` for more humanize code.
 *
 * @return string
 */
if (!function_exists('not_equal_to')) {
    function not_equal_to(): string
    {
        return '!=';
    }
}

/**
 * Merge array of words into one sentence
 *
 * @param array $words
 * @param string $glue
 * @return string
 */
if (!function_exists('make_sentence')) {
    function make_sentence(array $words = [], string $glue = ' '): string
    {
        if (count($words) < 1) {
            return long_sentence();
        }

        return implode($glue, $words);
    }
}

/**
 * Make a long long sentence using faker instance
 *
 * @param int $wordCount
 * @return string
 */
if (!function_exists('long_sentence')) {
    function long_sentence(int $wordCount = 100)
    {
        $faker = Factory::create();

        $words = [];
        for ($index = 0; $index < $wordCount; $index++) {
            array_push($words, $faker->word);
        }

        return make_sentence($words);
    }
}

if (!function_exists('set_array_as_url_parameters')) {
    function set_array_as_url_parameters(string $url, array $array): string
    {
        if (!str_ends_with('?', $url)) {
            $url .= '?';
        }

        foreach ($array as $attribute => $value) {
            $value = is_array($value) ?
                json_encode($value) : $value;

            $url .= $attribute . '=' . $value;

            if (array_key_last($array) !== $attribute) {
                $url .= '&';
            }
        }

        return $url;
    }
}

if (!function_exists('str_to_upper_special_char')) {
    function str_to_upper_special_char($str)
    {
        if (strpos($str, '/')) {
            $words = explode("/", $str);
            $finalString = "";
            foreach ($words as $word) {
                $word = ucfirst(strtolower($word));
                $finalString .= $word . "/";
            }
            return rtrim($finalString, '/');
        }
        return ucwords($str);
    }
}

if (!function_exists('first_words')) {
    /**
     * Get first x (amount given) words from a sentence.
     *
     * @param string $sentence
     * @param int $wordCount
     * @return string
     */
    function first_words(string $sentence, int $wordCount = 10): string
    {
        return implode(
            ' ',
            array_slice(explode(' ', $sentence), 0, $wordCount)
        );
    }
}
