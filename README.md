# TrustPay API PHP library
This library provides communication between client and TrustPay online cards payment gate.

# Currently supported payment methods
<ul>
    <li>Card payment</li>
    <li>EPS</li>
    <li>Sofort</li>
    <li>Giropay</li>
    <li>SepaCreditTransfer</li>
</ul>

# Requirements
<ul>
    <li>>= PHP 8.1</li>
</ul>

# Installation
`composer require marekvikartovsky/trustpay-php`

# Usage

### To create payment url you need to follow these steps.

As first as you need to instantiate `\MarekVikartovsky\TrustPay\TrustPay` class and provide information such as `project id`, `private key`, `notification url`, `return urls` and `locale`.<br>
`Cancel return URL`, `Error return URL` and `locale` do not need to be specified, they have predefined values.

```php
$trustpay = new \MarekVikartovsky\TrustPay\TrustPay(
'PROJET_ID',
'PRIVATE_KEY',
'NOTIFICATION_URL'
'SUCCESS_RETURN_URL',
'CANCEL_RETURN_URL',
'ERROR_RETURN_URL',
'en'
);
```

After that, you need to call a `payment` method, which creates payment object for specific payment method. The payment method is set by `payment` function parameter.<br>
Available payment methods:<br>
`\MarekVikartovsky\TrustPay\PaymentMethods\CardPayment::PAYMENT_METHOD_NAME`
`\MarekVikartovsky\TrustPay\PaymentMethods\Eps::PAYMENT_METHOD_NAME`
`\MarekVikartovsky\TrustPay\PaymentMethods\Giropay::PAYMENT_METHOD_NAME`
`\MarekVikartovsky\TrustPay\PaymentMethods\Sofort::PAYMENT_METHOD_NAME`
`\MarekVikartovsky\TrustPay\PaymentMethods\SepaCreditTransfer::PAYMENT_METHOD_NAME`

If you want to use `\MarekVikartovsky\TrustPay\PaymentMethods\CardPayment` method type, you have to call `setPaymentType` method with `Purchase` value.

```php
return $trustpay->payment(\MarekVikartovsky\TrustPay\PaymentMethods\CardPayment::PAYMENT_METHOD_NAME)
->setAmount((float) 10)
->setCurrency(\MarekVikartovsky\TrustPay\Enums\CurrencyEnum::EUR)
->setReference('MERCHANT_REFERENCE')
->setPaymentType('Purchase')
->getPaymentUrl();
```

If the payment gateway is not loaded inside an iframe. You have to call `allowRedirect` method on payment object.
```php
return $trustpay->payment(\MarekVikartovsky\TrustPay\PaymentMethods\CardPayment::PAYMENT_METHOD_NAME)
->allowRedirect()
->getPaymentUrl();
```

### Notification handler

As first as you need to instantiate `\MarekVikartovsky\TrustPay\CallbackHandlers\NotificationHandler` class and pass into its constructor `private key` and `\Illuminate\Http\Request()` instance.

```php
$notification = new \MarekVikartovsky\TrustPay\CallbackHandlers\NotificationHandler('XXXXX-PRIVATE-KEY-XXXXX', new \Illuminate\Http\Request())
```

After that you should check if signature is valid. You can do this by calling method `hasValidSignature()`. This method return boolean value.
```php
if($notification->hasValidSignature()){
    // do something
}
```

Than you can check for specific payment status. There are few methods which can be used: `isPaid()`, `isRejected()`, `isChargeBacked()`, `isRapidDisputeResolution()` or if you want to get value from status attribute, you can use `getStatus()` method. These methods return boolean values.
```php
if($notification->isPaid()){
    // do something
}
```

# Documentation
https://doc.trustpay.eu/aapi

# License
The TrustPay API PHP library is open-sourced software licensed under the <a href="https://opensource.org/licenses/MIT">MIT license</a>.