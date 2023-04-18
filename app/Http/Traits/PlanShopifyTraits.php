<?php
namespace App\Http\Traits;

use Illuminate\Support\Arr;
use App\Models\User;
use Illuminate\Support\Facades\Log;

trait  PlanShopifyTraits{

    public function appRecurringCharge($user,$payload){
        return $user->api()->rest('POST', 'admin/recurring_application_charges.json', $payload);
    }

    public function getRecurringApplicationCharges($user,$chargeId){
        return $user->api()->rest("GET", '/admin/api/' . config('global.shopify_api_version') . '/recurring_application_charges/' . $chargeId);
    }
}
