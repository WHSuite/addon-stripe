<?php
namespace Addon\Stripe\Libraries;

use App\Libraries\Interfaces\Gateway\PaymentInterface;
use \Whsuite\Inputs\Post as PostInput;

class Stripe implements PaymentInterface
{
    protected $stripeToken = false;

    /**
     * setup a payment, load the omnipay module
     * setup params, account names / amount etc..
     *
     * @param   array   array of data in order to perform the transaction
     * @param   bool    Indicator of whether we're setting up for check return
     * @return  bool    return true / false in order to proceed with the transaction
     */
    public function setup($data, $returnSetup = false)
    {
        \Stripe\Stripe::setApiKey(\App::get('configs')->get('settings.stripe.stripe_secret_key'));

        $this->stripeToken = PostInput::get('stripeToken');
        if (empty($this->stripeToken)) {
            return false;
        }

        return true;
    }

    /**
     * process the payment
     *
     * @param   array   array of data in order to perform the transaction
     */
    public function process($data)
    {
        if (empty($this->stripeToken)) {
            return false;
        }

        $site_name = \App::get('configs')->get('settings.general.sitename');

        // get the currency code
        $Currency = \Currency::find($data['currency_id']);
        $Invoice = \Invoice::find($data['invoice_id']);

        $chargeArray = array(
            'source' => $this->stripeToken,
            'amount' => ($data['total_due'] * 100),
            'currency' => $Currency->code,
            'description' => $site_name . ' ' . \App::get('translation')->get('invoice_no') . ' #'. $Invoice->invoice_no
        );

        try {
            $StripeCharge = \Stripe\Charge::create($chargeArray);

            if (is_object($StripeCharge) && $StripeCharge->status == 'succeeded') {
                // finalise the order
                $addon = \Addon::where('directory', '=', 'stripe')
                    ->where('is_gateway', '=', 1)
                    ->with('Gateway')
                    ->first();

                \App\Libraries\Payments::finalisePayment(
                    $data['invoice_id'],
                    $StripeCharge->id,
                    $addon
                );

                return true;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * check the return of a payment
     *
     * @param   array   array of data in order to perform the transaction
     * @return  bool|string
     */
    public function checkReturn($data)
    {
        return true;
    }
}
