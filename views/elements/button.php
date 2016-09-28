<script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="<?php echo $publishable_key; ?>"
    data-amount="<?php echo ($total_due * 100); ?>"
    data-name="<?php echo $site_name; ?>"
    data-description="<?php echo $stripe_description; ?>"
    data-image="<?php echo $stripe_image; ?>"
    data-label="<?php echo $lang->get('pay_invoice'); ?>"
    data-panel-label="<?php echo $lang->get('pay_invoice'); ?>"
    data-currency="<?php echo $currency->code; ?>"
>
</script>