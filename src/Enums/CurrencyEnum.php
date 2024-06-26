<?php

namespace MarekVikartovsky\TrustPay\Enums;

enum CurrencyEnum
{
    /**
     * Australian Dollar
     */
    case AUD;

    /**
     * Bosnia and Herzegovina convertible mark
     */
    case BAM;

    /**
     * Bulgarian lev
     */
    case BGN;

    /**
     * Canadian Dollar
     */
    case CAD;

    /**
     * Swiss Franc
     */
    case CHF;

    /**
     * Yuan Renminbi
     */
    case CNY;

    /**
     * Czech koruna
     */
    case CZK;

    /**
     * Danish Krone
     */
    case DKK;

    /**
     * Euro
     */
    case EUR;

    /**
     * Pound sterling
     */
    case GBP;

    /**
     * Hong Kong Dollar
     */
    case HKD;

    /**
     * Hungarian forint
     */
    case HUF;

    /**
     * Israeli new shekel
     */
    case ILS;

    /**
     * Japanese yen
     */
    case JPY;

    /**
     * Norwegian Krone
     */
    case NOK;

    /**
     * Polish zloty
     */
    case PLN;

    /**
     * Romanian leu
     */
    case RON;

    /**
     * Serbian dinar
     */
    case RSD;

    /**
     * Swedish Krona
     */
    case SEK;

    /**
     * Turkish Lira
     */
    case TRY;

    /**
     * United States dollar
     */
    case USD;

    /**
     * Return currency ID.
     *
     * @return int
     */
    public function getCurrencyID(): int
    {
        return match ($this) {
            self::AUD => 036,
            self::BAM => 977,
            self::BGN => 975,
            self::CAD => 124,
            self::CHF => 756,
            self::CNY => 156,
            self::CZK => 203,
            self::DKK => 208,
            self::EUR => 978,
            self::GBP => 826,
            self::HKD => 344,
            self::HUF => 348,
            self::ILS => 376,
            self::JPY => 392,
            self::NOK => 578,
            self::PLN => 985,
            self::RON => 946,
            self::RSD => 941,
            self::SEK => 752,
            self::TRY => 949,
            self::USD => 840,
        };
    }
}