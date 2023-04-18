<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Osiset\ShopifyApp\Contracts\Objects\Values\ShopDomain as ShopDomainValue;
use Osiset\ShopifyApp\Objects\Enums\DataSource;
use Osiset\ShopifyApp\Objects\Values\NullShopDomain;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use Osiset\ShopifyApp\Storage\Models\Charge;

use App\Models\SsContract;
use Illuminate\Routing\Route;

class CheckPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if( !Auth::user() && !@$request->id){

            $shdomain = $this->getShopDomainFromData($request);
            if( $shdomain->isNull() && @$request->query('name') ){
                $shdomain = new ShopDomain($request->query('name'));
            }

            if ($shdomain->isNull()) {
                return Redirect::route('login');
            }else{
                $domain = $shdomain->toNative();
                $user = User::where('name', $domain)->first();
                if( $user ){
                    Auth::login($user);
                }else{
                    return Redirect('authenticate?shop=' . $domain);
                }
            }
        }

        $shop = Auth::user();
        if( !$shop ){
            return Redirect::route('login');
        }

        if (config('shopify-app.billing_enabled') === true) {
            if (!$shop->isFreemium() && !$shop->isGrandfathered() && !$shop->plan) {

                // They're not grandfathered in, and there is no charge or charge was declined... redirect to billing
                //return redirect('plan-page');
                return Redirect('plan-page');
            }
        }
        return $next($request);
    }

    /**
     * Gets the shop domain from the data.
     *
     * @param Request $request The request object.
     *
     * @return ShopDomainValue
     */
    private function getShopDomainFromData(Request $request): ShopDomainValue
    {

        $options = [
            DataSource::INPUT()->toNative(),
            DataSource::HEADER()->toNative(),
            DataSource::REFERER()->toNative()
        ];

        foreach ($options as $option) {
            $result = $this->getData($request, $option);

            if (isset($result['shop'])) {
                // Found a shop
                return new ShopDomain($result['shop']);
            }
        }

        // No shop domain found in any source
        return new NullShopDomain();
    }

    /**
     * Grab the data.
     *
     * @param Request $request The request object.
     * @param string  $source  The source of the data.
     *
     * @return array
     */
    private function getData(Request $request, string $source): array
    {
        // All possible methods

        $options = [

            // GET/POST
            DataSource::INPUT()->toNative() => function () use ($request): array {
                // Verify
                $verify = [];
                foreach ($request->query() as $key => $value) {
                    $value = $this->parseDataSourceValue($value);
                    $verify[$key] = is_array($value) ? '["'.implode('", "', $value).'"]' : $value;
                }

                return $verify;
            },
            // Headers
            DataSource::HEADER()->toNative() => function () use ($request): array {
                // Always present
                $shop = $request->header('X-Shop-Domain');
                $signature = $request->header('X-Shop-Signature');
                $timestamp = $request->header('X-Shop-Time');

                $verify = [
                    'shop'      => $shop,
                    'hmac'      => $signature,
                    'timestamp' => $timestamp,
                ];

                // Sometimes present
                $code = $request->header('X-Shop-Code') ?? null;
                $locale = $request->header('X-Shop-Locale') ?? null;
                $state = $request->header('X-Shop-State') ?? null;
                $id = $request->header('X-Shop-ID') ?? null;
                $ids = $request->header('X-Shop-IDs') ?? null;

                foreach (compact('code', 'locale', 'state', 'id', 'ids') as $key => $value) {
                    if ($value) {
                        $verify[$key] = is_array($value) ? '["'.implode('", "', $value).'"]' : $value;
                    }
                }

                return $verify;
            },
            // Headers: Referer
            DataSource::REFERER()->toNative() => function () use ($request): array {
                $url = parse_url($request->header('referer'), PHP_URL_QUERY);
                parse_str($url, $refererQueryParams);

                // Verify
                $verify = [];
                foreach ($refererQueryParams as $key => $value) {
                    $verify[$key] = is_array($value) ? '["'.implode('", "', $value).'"]' : $value;
                }

                return $verify;
            }
        ];




        return $options[$source]();
    }

    private function parseDataSourceValue($value): string
    {
        /**
         * Format the value.
         *
         * @param mixed $val
         *
         * @return string
         */
        $formatValue = function ($val): string {
            return is_array($val) ? '["'.implode('", "', $val).'"]' : $val;
        };

        // Nested array
        if (is_array($value) && is_array(current($value))) {
            return implode(', ', array_map($formatValue, $value));
        }

        // Array or basic value
        return $formatValue($value);
    }

    /**
     * Handle bad verification by killing the session and redirecting to auth.
     *
     * @param Request         $request The request object.
     * @param ShopDomainValue $domain  The shop domain.
     *
     * @return void
     */
    private function handleBadVerification(Request $request, ShopDomainValue $domain)
    {
        if ($domain->isNull()) {
            // We have no idea of knowing who this is, this should not happen
            return Redirect::route('login');
        }

        // Set the return-to path so we can redirect after successful authentication
        Session::put('return_to', $request->fullUrl());

        // Kill off anything to do with the session
        $this->shopSession->forget();

        // Mis-match of shops
        return Redirect::route(
            'authenticate.oauth',
            ['shop' => $domain->toNative()]
        );
    }
}
