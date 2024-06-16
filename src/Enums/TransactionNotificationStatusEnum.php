<?php

namespace MarekVikartovsky\TrustPay\Enums;

enum TransactionNotificationStatusEnum: string
{
    case PAID = 'Paid';
    case AUTHORIZED = 'Authorized';
    case REJECTED = 'Rejected';
    case CHARGE_BACKED = 'Chargebacked';
    case RAPID_DISPUTE_RESOLUTION = 'RapidDisputeResolution';
}