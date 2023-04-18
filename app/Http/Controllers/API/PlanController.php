<?php

namespace App\Http\Controllers\API;

use App\Http\Traits\PlanShopifyTraits;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Charge;
use App\Models\User;
use App\Models\Plan;
use Carbon\Carbon;
use Exception;

class PlanController extends Controller
{

    use PlanShopifyTraits;

    public function index(){
        try {
            $plans = Plan::all()->toArray();
            return parent::retriveJSONReponse($plans);
        } catch (\Throwable $th) {
            logger($th);
            return parent::operationFailedJSONResponse($th->getMessage());
        }
    }


     /**
     * Select Plan by merchant.
     *
     * @param request entity to be get name and plan
     *
     * @param name get Shop URL from merchant choose for billing plan
     *
     * @param plan get PlanID from merchant choose for billing plan
     *
     * @return Response
     */
    public function planChange(Request $request)
    {

        $name = $request->input('name');
        $plan = $request->input('plan');

        if(!$name || !$plan) return;

        try {
            $user = Auth::user();
            if (!$user) {
                $user = User::where('name', $name)->first();
                Auth::login($user);
            }
            $dbPlan = Plan::where('id', $plan)->first();

            /*  Plan recurring application changres
                isTest instance is set by default 1 for test billing purpose.
            */
            $isTest = $dbPlan->test  ? $dbPlan->test : 1;

            $payload = [
                'recurring_application_charge' => [
                    "return_url"    => env('APP_URL') . 'api/change-plan-db/?uid='.$user->id,
                    "capped_amount" => $dbPlan->capped_amount,
                    "price"         => $dbPlan->price,
                    "name"          => $dbPlan->name,
                    "terms"         => $dbPlan->terms,
                    "test"          => $isTest,
                    "trial_days"    => 0,
                ]
            ];

            /* Note:: appRecurringCharge
               this one recuring charge api service of shopify billing
            */
            $result = $this->appRecurringCharge($user,$payload);

            if (!$result['errors']) {
                $data = $result['body']->container['recurring_application_charge'];
                return parent::retriveJSONReponse($data);
            } else {
                logger("recurring-error::");
                logger(json_encode($result));
                return parent::operationFailedJSONResponse();
            }

        }catch(Exception $e){
            return parent::operationFailedJSONResponse($e->getMessage());
        }
    }


     /**
     * After checkout return data and update to the database.
     *
     * @param request entity to be get uid
     *
     * @param uid get User ID from return URL from recurring_application_charge response
     *
     *
     * @return response
     */

    public function changePlanDB(Request $request){

        try {
            $shop = User::find($request->input('uid'));
            $oldCharge = Charge::where('status', 'ACTIVE')->where('user_id', $shop->id)->first();

            if ($oldCharge) {
                $oldCharge->status = 'CANCELLED';
                $oldCharge->cancelled_on = Carbon::now()->format('Y-m-d H:i:s');
                $oldCharge->save();
            }


            /* Note:: getRecurringApplicationCharges
               this one checkout selected plan by using returning charge id
            */

            $response = $this->getRecurringApplicationCharges($shop,$request->charge_id);

            if (!$response['errors']) {
                $chargeData = $response['body']->container['recurring_application_charge'];

                $plan = Plan::where('name', $chargeData['name'])->first();
                $charge = new Charge;

                $charge->charge_id = $chargeData['id'];
                $charge->test = $chargeData['test'];
                $charge->status = strtoupper($chargeData['status']);
                $charge->name = $chargeData['name'];
                $charge->terms = $plan->terms;
                $charge->interval = $plan->interval;
                $charge->capped_amount = $chargeData['capped_amount'];
                $charge->type = 'RECURRING';
                $charge->price = $chargeData['price'];
                $charge->trial_days = $chargeData['trial_days'];

                $charge->billing_on = Carbon::parse($chargeData['billing_on'])->format('Y-m-d H:i:s');
                $charge->activated_on = Carbon::parse($chargeData['activated_on'])->format('Y-m-d H:i:s');
                $charge->trial_ends_on = Carbon::parse($chargeData['trial_ends_on'])->format('Y-m-d H:i:s');
                $charge->created_at = Carbon::parse($chargeData['trial_ends_on'])->format('Y-m-d H:i:s');
                $charge->updated_at = Carbon::parse($chargeData['trial_ends_on'])->format('Y-m-d H:i:s');

                $charge->plan_id = $plan->id;
                $charge->user_id = $shop->id;

                $charge->save();
            }

            $shop->plan_id = $plan->id;
            $dbShop = User::where('id', $shop->id)->orderBy('created_at', 'desc')->first();

            if (!$dbShop->member_count_update_at) {
                $dbShop->member_count_update_at = $charge->activated_on;
                $dbShop->save();
            }

            $storeName = substr($shop->name, 0, strpos($shop->name, ".myshopify.com"));;

            $shop->save();
            Auth::login($shop);

            /**
             * Note:: return home path with parent url for access shopify child route with default store request data.
             * URL:: https://admin.shopify.com/store/{shop_name}/apps/{app_name}
             */

            $url = "https://admin.shopify.com/store/".$storeName."/apps/".config("global.app_name");
            return redirect($url);

        }catch( \Exception $e ){
            logger("============= ERROR ::  changePlanDB =============");
            logger($e);
            return parent::operationFailedJSONResponse($e->getMessage());

        }
    }
}
