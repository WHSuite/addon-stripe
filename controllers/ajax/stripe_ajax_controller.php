<?php

class StripeAjaxController extends GatewayBaseController
{
    /**
     * retrieve the invoice details and load the stripe button
     *
     */
    public function loadStripeButton($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        if (is_object($invoice) && $invoice->id == $invoice_id) {
            $total_due = $invoice->total - $invoice->total_paid;

            $this->view->set(
                array(
                    'total_due' => $total_due,
                    'invoice' => $invoice,
                    'currency' => $invoice->Currency()->first(),
                    'publishable_key' => \App::get('configs')->get('settings.stripe.stripe_publishable_key'),
                    'site_name' => \App::get('configs')->get('settings.general.sitename'),
                    'stripe_description' => \App::get('configs')->get('settings.stripe.stripe_description'),
                    'stripe_image' => \App::get('configs')->get('settings.stripe.stripe_image'),
                )
            );

            $button = $this->view->fetch('stripe::elements/button.php');
        } else {
            $button = false;
        }

        $this->returnButton($button);
    }
}
