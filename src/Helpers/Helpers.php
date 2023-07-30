<?php

use App\Enums\Currency;
use App\Enums\Gender\Gender;
use App\Enums\GeneralStatus;
use App\Enums\MessageStatus;
use App\Enums\ReverificationStatusEnum;
use App\Enums\Review\ReviewStatus;
use App\Enums\RouteStatus\FriendRouteStatus;
use App\Enums\RouteStatus\MemberRouteStatus;
use App\Enums\RouteStatus\MessageRouteStatus;
use App\Enums\RouteStatus\ProfileReviewRouteStatus;
use App\Enums\RouteStatus\UsersRouteStatus;
use App\Enums\Subscription\UserSubscriptionStatus;
use App\Enums\UserProfile\UserProfileStatus;
use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\BannedIp;
use App\Models\Log;
use App\Models\Subscription\SubscriptionPackage;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\ViewPhonenumberVariable;
use App\Services\EmailService;
use App\Services\IpApiLocationService;
use App\Services\TwilioService;
use App\Services\Utility\LogService;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

if (!function_exists('searchInCollection')) {

    /**
     * Search value in collection
     *
     * @param Collection $collection
     * @param mixed $search
     * @return array
     */
    function searchInCollection(Collection $collection, mixed $search): array
    {
        return ($collection->filter(function ($item) use ($search) {
            $attributes = array_keys($item);
            foreach ($attributes as $attribute)
                if (isset($item[$attribute]) && (!is_array($item[$attribute])))
                    if (stripos($item[$attribute], $search) !== false)
                        return true;

            return false;
        }))->toArray();
    }
}

if (!function_exists('urlToUsername')) {
    /**
     * Convert full url to username
     *
     * @param string $urlString
     * @return  string
     */
    function urlToUsername(string $urlString): string
    {
        $urlString = str_replace('http://', '', $urlString);
        $urlString = str_replace('https://', '', $urlString);
        $urlString = str_replace('www.', '', $urlString);

        $clearParams = explode('/', $urlString);

        $mainDomain = $clearParams[0];
        $breakMainDomain = explode('.', $mainDomain);
        $domainName = $breakMainDomain[0];
        $domainExtension = $breakMainDomain[1];

        return $domainName . $domainExtension;
    }
}

if (!function_exists('emailToUsername')) {
    /**
     * Convert email name to username
     *
     * @param string $email
     * @return string
     */
    function emailToUsername(string $email): string
    {
        $explode = explode('@', $email);
        return $explode[0];
    }
}

if (!function_exists('get_pure_class')) {
    /**
     * Get object pure class name without namespaces
     *
     * @param mixed $class
     * @return string
     */
    function get_pure_class(mixed $class): string
    {
        $class = get_class($class);
        $explode = explode('\\', $class);
        return $explode[count($explode) - 1];
    }
}

if (!function_exists('get_lower_class')) {
    /**
     * Get object lower class version
     *
     * @param mixed $class
     * @return string
     */
    function get_lower_class(mixed $class): string
    {
        $lowerClassname = get_pure_class($class);
        $lowerClassname = str_snake_case($lowerClassname);
        return strtolower($lowerClassname);
    }
}

if (!function_exists('get_plural_lower_class')) {
    /**
     * Get object plural lower case name
     *
     * This will be helpful to create variable name
     *
     * @param mixed $class
     * @return string
     */
    function get_plural_lower_class(mixed $class): string
    {
        return str_to_plural(get_lower_class($class));
    }
}

if (!function_exists('numbertofloat')) {
    /**
     * Convert any number value to float
     *
     * @param float|int|string $number
     * @return string
     */
    function numbertofloat(float|int|string $number): string
    {
        return sprintf('%.2f', $number);
    }
}

if (!function_exists('currency_format')) {
    /**
     * Format any numeric value to currency
     *
     * @param float|int|string $amount
     * @param string $currencyCode
     * @param string $locale
     * @return bool|string
     */
    function currency_format(
        float|int|string $amount,
        string           $currencyCode = 'EUR',
        string           $locale = 'nl_NL.UTF-8'
    ): bool|string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currencyCode);
    }
}

if (!function_exists('db')) {
    /**
     * Make alias for Laravel DB class
     *
     * @param string $table
     * @return DB|Builder
     */
    function db(string $table = ''): DB|Builder
    {
        return ($table) ? DB::table($table) : new DB;
    }
}

if (!function_exists('clean_filename')) {
    /**
     * Clean file name from directory special characters
     *
     * @param string $filename
     * @return array|string
     */
    function clean_filename(string $filename): array|string
    {
        // Replace > with space
        $filename = str_replace('/', ' ', $filename);

        // Replace > with space
        $filename = str_replace('>', ' ', $filename);

        // Replace | with space
        $filename = str_replace('|', ' ', $filename);

        // Replace : with space
        $filename = str_replace(':', ' ', $filename);

        // Replace & with space
        $filename = str_replace('&', ' ', $filename);

        // Replace ? with space
        $filename = str_replace(' ', '_', $filename);

        // Replace spaces with _
        return str_replace(' ', '_', $filename);
    }
}
if (!function_exists('random_hex_color')) {

    /**
     * Generate random hex color
     *
     * @return  string
     */
    function random_hex_color(): string
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}


if (!function_exists('random_phone')) {
    /**
     * Generate random phone number
     * by specifying the length of digits
     *
     * @param int $length
     * @return  string
     * @throws Exception
     */
    function random_phone(int $length = 12): string
    {
        if ($length <= 0) return '';

        $digit = ((string)random_int(0, 9));
        return $digit . random_phone($length - 1);
    }
}

if (!function_exists('json_decode_array')) {
    /**
     * Decode JSON string directly to array
     *
     * @param string $json
     * @return array|null
     */
    function json_decode_array(string $json): ?array
    {
        return json_decode($json, true);
    }
}

if (!function_exists('is_valid_json')) {
    /**
     * Check if a string if valid json
     *
     * @param string $string
     * @return bool
     */
    function is_valid_json(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (!function_exists('jsonResponse')) {
    /**
     * Shorter version for laravel response json
     *
     * @param array $response
     * @return JsonResponse
     */
    function jsonResponse(array $response): JsonResponse
    {
        return response()->json($response);
    }
}

if (!function_exists('uppercaseArray')) {
    /**
     * Convert every element to uppercase in array
     *
     * @param array $array
     * @return array
     */
    function uppercaseArray(array $array): array
    {
        return array_map('strtoupper', $array);
    }
}


if (!function_exists('current_currency')) {
    /**
     * Get current currency of the authenticated user
     *
     * @return  int
     */
    function current_currency()
    {
        if ($user = auth()->user()) {
            return $user->currency;
        }

        return Currency::EUR;
    }
}

if (!function_exists('random_subnet')) {
    /**
     * Generate random Subnet
     *
     * @return string
     */
    function random_subnet(): string
    {
        return mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '/24';
    }
}

if (!function_exists('random_ip')) {
    /**
     * Generate random ip
     *
     * @return string
     */
    function random_ip(): string
    {
        return mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
    }
}

if (!function_exists('auth')) {
    /**
     * Return laravel auth class facade
     *
     * @return Auth
     */
    function auth(): Auth
    {
        return new Auth;
    }
}

if (!function_exists('authGuard')) {
    /**
     * Return laravel auth class with guard facade
     *
     * @param string $guardName
     * @return Guard|StatefulGuard
     */
    function authGuard(string $guardName = 'guest'): Guard|StatefulGuard
    {
        return Auth::guard($guardName);
    }
}

if (!function_exists('is_authenticated')) {
    /**
     * Check if current user is authenticated
     *
     * @return bool
     */
    function is_authenticated(): bool
    {
        return auth()->check();
    }
}
if (function_exists('authUser')) {

    /**
     * Get auth user instantly
     *
     * @return User|null
     */
    function authUser(): ?User
    {
        return auth()->check() ?
            auth()->user() :
            null;
    }
}

if (!function_exists('authUserId')) {
    /**
     * Get auth user id instantly
     *
     * @return string|int|null
     */
    function authUserId(): int|string|null
    {
        if (!auth()->check()) {
            return null;
        }

        return auth()->user()->id;
    }
}

if (!function_exists('authAdminUserId')) {
    /**
     * Get Admin id instantly
     *
     * @return string|int|null
     */
    function authAdminUserId(): int|string|null
    {
        if (!auth()->guard('admin')->check()) return null;

        return auth()->guard('admin')->user()->id;
    }
}
if (!function_exists('authLoginUserId')) {

    /**
     * Get Login id instantly
     *
     * @return string|int|null
     */
    function authLoginUserId(): int|string|null
    {
        return authAdminUserId() ?? authUserId();
    }
}

if (!function_exists('authUserKey')) {
    /**
     * Get auth user key instantly
     *
     * @return string|int
     */
    function authUserKey(): int|string|null
    {
        return auth()->check() ?
            auth()->user()->user_key :
            null;
    }
}

if (!function_exists('authUserType')) {
    /**
     * Get auth user type instantly
     *
     * @return string|null
     */
    function authUserType(): ?string
    {
        return auth()->user()->type;
    }
}

if (!function_exists('authProfile')) {
    /**
     * Get auth user profile instantly
     *
     * @return UserProfile|null
     */
    function authProfile(): ?UserProfile
    {
        return auth()->user()->userProfile;
    }
}
if (!function_exists('session_profile_image')) {

    /**
     * Get user's profile image stored in session
     *
     * @return string
     */
    function session_profile_image(): ?string
    {
        return session('pro_img', null);
    }
}

if (!function_exists('image_placeholder')) {
    /**
     * Get path of image placeholder from config value
     *
     * @return string
     */
    function image_placeholder(): string
    {
        return config('rackspace.image_placeholder');
    }
}

if (!function_exists('dest_obj_to_arr')) {
    /**
     * Destructure object class into array
     *
     * @param Object $class
     * @param array $attributes
     * @return array
     */
    function dest_obj_to_arr($class, array $attributes): array
    {
        $result = [];

        foreach ($attributes as $attribute) {
            $result[$attribute] = $class->{$attribute};
        }

        return $result;
    }
}

if (!function_exists('current_page')) {
    /**
     * Get laravel current page
     *
     * @return int
     */
    function current_page(): int
    {
        return request()->input(
            'page',
            request()->input('current_page', 1)
        );
    }
}

/**
 * Create log of the application
 *
 * @param array $parameters
 * @return Log
 */
if (!function_exists('create_log')) {
    function create_log(array $parameters)
    {
        return Log::create($parameters);
    }
}

/**
 * Record log using log service.
 *
 * @param string $type
 * @param array $resources
 * @param string $message
 * @return bool
 */
if (!function_exists('record_log')) {
    function record_log(
        string $type,
        string $message = '',
        string $resource = '',
        array  $params = [],
        array  $response = []
    ): bool
    {
        $service = app(LogService::class);

        return $service->write($type, $message, $resource, $params, $response);
    }
}

/**
 * Send SMS to a certain phone number
 *
 * @param string $phoneNumber
 * @param string $content
 * @return void
 */
if (!function_exists('send_sms')) {
    function send_sms(string $phoneNumber, string $content = 'Blank'): bool
    {
        $twilio = new TwilioService();
        return $twilio->sendSms($phoneNumber, $content);
    }
}

/**
 * Send email to target mail
 *
 * @param string $recipient
 * @param string $subject
 * @param string $content
 * @return void
 */
if (!function_exists('send_mail')) {
    function send_mail(
        string $recipient,
        string $subject,
        string $content
    ): void
    {
        $service = app(EmailService::class);
        $service->send($recipient, $subject, $content);
    }
}

/**
 * Return requesters IP address
 *
 * @return string
 */

//@todo AW factor out
if (!function_exists('request_ip')) {
    function request_ip()
    {
        if (request()->ip() == config('app.local_ip')) {
            return config('development.ip_address');
        }
        return request()->ip();
    }
}

if (!function_exists('request_ip_detail')) {
    /**
     * Get requester information details using IP
     *
     * @param string $ipAddress
     * @return array
     * @throws Exception
     * @see \Tests\Unit\Helpers\HelpersTest::test_request_ip_detail()
     *      To the helper method unit tester method.
     */
    function request_ip_detail(string $ipAddress = ''): array
    {
        $ipAddress = $ipAddress ?: request_ip();
        $ipAddress = ($ipAddress == config('app.local_ip')) ? config('development.ip_address') : $ipAddress;
        $ipApiService = new IpApiLocationService();
        $ipApiService->setIp($ipAddress);

        return (array)$ipApiService->findLocation(true);
    }
}

if (!function_exists('request_ip_timezone')) {
    /**
     * Get current requester timezone based on the IP address.
     *
     * @return string
     * @throws Exception
     */
    function request_ip_timezone(): string
    {
        $ipDetail = request_ip_detail();

        return $ipDetail['timezone'] ??
            config('app.timezone');
    }
}


if (!function_exists('stdclass_to_array')) {
    /**
     * Convert stdClass instance to array
     *
     * @param stdClass $object
     * @return array
     */
    function stdclass_to_array(stdClass $object): array
    {
        return json_decode(json_encode($object), true);
    }
}

if (!function_exists('which_env')) {
    /**
     * Get certain env variable with condition.
     * If true then get value from second parameter as env attribute.
     * If false do for the third parameter
     *
     * @param bool $condition
     * @param string $firstEnvAttr
     * @param string $secondEnvAttr
     * @param mixed|null $defaultValue
     * @return mixed
     */
    function which_env(
        bool   $condition,
        string $firstEnvAttr,
        string $secondEnvAttr,
        mixed  $defaultValue = null
    ): mixed
    {
        $attr = $condition ? $firstEnvAttr : $secondEnvAttr;

        return env($attr, $defaultValue);
    }
}

if (!function_exists('pagination_info')) {
    /**
     * Get current pagination information including
     * `page` and `per_page`.
     *
     * @return array
     */
    function pagination_info(): array
    {
        return request()->only([
            'page',
            'per_page'
        ]);
    }
}

if (!function_exists('pagination_apply_resource')) {
    /**
     * Apply resource class for pagination collection
     *
     * @param string $resourceClass
     * @param LengthAwarePaginator $pagination
     * @return LengthAwarePaginator
     */
    function pagination_apply_resource(string $resourceClass, $pagination)
    {
        $collection = $resourceClass::collection($pagination);

        $pagination->setCollection($collection);

        return $pagination;
    }
}
if (!function_exists('request_interval')) {

    /**
     * Get time interval of friend request
     *
     * @return int
     */
    function request_interval(): int
    {
        return App\Models\RequestVariable::first()->time_interval;
    }
}

if (!function_exists('array_random_element')) {
    /**
     * Get random element in an array
     *
     * @param array $haystack
     * @return mixed
     */
    function array_random_element(array $haystack): mixed
    {
        return array_rand($haystack, 1)[0];
    }
}
if (!function_exists('max_phone_view')) {

    /**
     * Get max phone view count
     *
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function max_phone_view(): int
    {
        if (!$maxPhoneView = cache()->get('max_phone_view', 0)) {
            $variable = ViewPhonenumberVariable::first() ?:
                ViewPhonenumberVariable::create([
                    'max_phone_view' => 50,
                    'error_msg' => 'Failed to get set amount of max phone view',
                ]);

            $maxPhoneView = $variable->max_phone_view;
            cache()->put('max_phone_view', $maxPhoneView);
        }

        return $maxPhoneView;
    }
}
if (!function_exists('factory')) {

    /**
     * Patch for upgrade of `factory()` method in older laravel version.
     *
     * @param string $model
     * @param int $quantity
     * @return Factory
     */
    function factory(
        string $model,
        int    $quantity = 1
    ): Illuminate\Database\Eloquent\Factories\Factory
    {
        return $quantity > 1 ?
            (new $model)->factory($quantity) : (new $model)->factory();
    }
}

if (!function_exists('fake_safe_email')) {
    /**
     * Create safe email using factory and add it with timestamp.
     *
     * @return string
     */
    function fake_safe_email(): string
    {
        $faker = Faker\Factory::create();

        $safeEmail = $faker->safeEmail;

        $explode = explode('@', $safeEmail);
        $explode[0] = $explode[0] . strtolower(random_string(10));
        $explode[1] = 'gmail.com';

        return implode('@', $explode);
    }
}
if (!function_exists('cors_headers')) {

    /**
     * Get array collection of CORS headers.
     *
     * @return array
     */
    function cors_headers(): array
    {
        return [
            'X-Container-Meta-Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Expose-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',
            'Access-Control-Request-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization'
        ];
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

if (!function_exists('not')) {
    /**
     * Turn the statement
     *
     * @param string|bool $statement
     * @return bool
     */
    function not(string|bool $statement): bool
    {
        return !strtobool($statement);
    }
}

if (!function_exists('is_not_null')) {
    /**
     * Check whether specified statement is null or not.
     *
     * @param mixed $statement
     * @return bool
     */
    function is_not_null(mixed $statement): bool
    {
        return (!is_null($statement));
    }
}

if (!function_exists('not_in_array')) {
    /**
     * Check whether the needle is in haystack.
     *
     * This method is the reversed version of in_array()
     *
     * @param string $needle
     * @param array $haystack
     * @return bool
     */
    function not_in_array(string $needle, array $haystack): bool
    {
        return !in_array($needle, $haystack);
    }
}

if (!function_exists('mailgun_list_name')) {
    /**
     * Get Mailgun list name
     *
     * @param string $list_name
     * @return string
     */
    function mailgun_list_name(string $list_name): string
    {
        if (!strpos($list_name, '@')) {
            $list_name = $list_name . '@' . config('mailgun.domain');
        }

        return $list_name;
    }
}

if (!function_exists('app_in_production')) {
    /**
     * Check whether current application is in production.
     *
     * @return bool
     */
    function app_in_production(): bool
    {
        $environment = config('app.env', 'local');

        return $environment === 'production';
    }
}

if (!function_exists('pagination_size')) {
    /**
     * Get pagination size, if changed then this will update to session.
     *
     * @param int $default
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function pagination_size(int $default = 10): int
    {
        // Get per_page value from input parameter from URL
        $inputSize = request()->get('per_page', request()->get('pagination_size'));

        // Get per_page value from session storage
        $sessionSize = session()->get('per_page', $default);
        session()->put('per_page', $sessionSize);

        // If input size is not the same as session size then update it
        if (!is_null($inputSize)) {
            session()->put('per_page', $inputSize);
            $sessionSize = session()->get('per_page', $default);
        }

        return $sessionSize;
    }
}


if (!function_exists('is_on_maintenance_mode')) {
    /**
     * Check whether the current application is on maintenance mode.
     *
     * @param bool $asString If set as true, will return "on" or "off"
     * @return bool|string
     */
    function is_on_maintenance_mode(bool $asString = false): bool|string
    {
        $mode = file_exists(storage_path('framework/down'));
        if (!$asString) {
            return $mode;
        }

        return $mode ? 'on' : 'off';
    }
}

if (!function_exists('class_uses_trait')) {
    /**
     * Check whether a class uses a trait.
     *
     * @param string $className
     * @param string $traitName
     * @return bool
     */
    function class_uses_trait(string $className, string $traitName): bool
    {
        return in_array($traitName, class_uses_recursive($className));
    }
}
