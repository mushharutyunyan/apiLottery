<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use URL;
use Session;
use Redirect;
use Illuminate\Support\Facades\Input;
use Config;
use App\Models\Plan;
use App\Models\Payment;
/** All Paypal Details class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment as ApiPayment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
class PaymentController extends Controller
{
    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /** setup PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function payWithPaypal(Request $request){
        $data = $request->all();
        if(!Plan::where('id',$data['plan_id'])->count()){
            die();
        }
        $plan = Plan::where('id',$data['plan_id'])->first();
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($plan->name) /** item name **/
        ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($plan->amount); /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item));
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($plan->amount);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($plan->description)
            ->setInvoiceNumber(uniqid());

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(url('/payment/paypal/success')) /** Specify return URL **/
        ->setCancelUrl(url('/payment/paypal/error'));
        $payment = new ApiPayment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (Config::get('app.debug')) {
                Session::put('error','Connection timeout');
                return redirect('/payment/paypal/error');
                /** echo "Exception: " . $ex->getMessage() . PHP_EOL; **/
                /** $err_data = json_decode($ex->getData(), true); **/
                /** exit; **/
            } else {
                Session::put('error','Some error occur, sorry for inconvenient');
                return redirect('/payment/paypal/error');
                /** die('Some error occur, sorry for inconvenient'); **/
            }
        }

        $approvalUrl = $payment->getApprovalLink();
        if(isset($approvalUrl)) {
            /** redirect to paypal **/
            /** add payment ID to session **/
            Session::put('paypal_payment_id', $payment->getId());

            Payment::create(array(
               'user_id' => Auth::user()['id'],
               'plan_id' => $data['plan_id'],
                'paymentId' => Session::get('paypal_payment_id'),
                'status' => Payment::PROCESSING,
            ));

            return redirect($approvalUrl);
        }
        Session::put('error','Unknown error occurred');
        return redirect('/payment/paypal/error');
    }

    public function getPaypalPaymentStatus($response){
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            Session::put('error','Payment failed');
            return redirect('/payment/paypal/error');
        }
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        $payment = ApiPayment::get($payment_id, $this->_api_context);
        /** PaymentExecution object includes information necessary **/
        /** to execute a PayPal account payment. **/
        /** The payer_id is added to the request query parameters **/
        /** when the user is redirected from paypal back to your site **/
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        /** dd($result);exit; /** DEBUG RESULT, remove it later **/
        if ($result->getState() == 'approved') {
            /** it's all right **/
            /** Here Write your database logic like that insert record or value in database if you want **/
            Payment::where('paymentId',$payment_id)->update(array(
                'status' => Payment::SUCCESS,
                'cart' => $result->cart
            ));
            $payment = Payment::where('paymentId',$payment_id)->first();
            User::where("id",$payment->user->id)->update(array('count_requests' => ($payment->user->count_requests + $payment->plan->calls)));
            return redirect('/')->with(['status' => 'Payment success','paymentId' => $payment_id]);
        }
        Payment::where('paymentId',$payment_id)->update(array(
            'status' => Payment::WRONG,
        ));
        Session::put('error','Payment failed');
        return redirect('/payment/paypal/error');
    }

    public function error(){
        return view('errors.payment');
    }

}
