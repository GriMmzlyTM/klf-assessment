<?php

/*
 * LORENZO TORELLI - 10/2018
 *
 * I made this as simple and basic as I could. The idea is that when the constructor is called
 * it assigns the region code from the IP, and the price and coupon code from the $_POST.
 * I didn't include any securities such as region checking.
 *
 */

///////////////////////////////////////
/// Includes
///


///////////////////////////////////////
/// Class
///
    class Order
    {

        ///////////////////////////////////////
        /// Variables
        ///
        private $region;
        private $myTax;
        private $couponCode;
        private $total;

        /**
         * Order constructor.
         * @param string $regionCode qc, on, etc
         * @param float $price Base price of item
         * @param string|null $couponCode coupon code
         */
        public function __construct(string $regionCode, float $price, string $couponCode = null)
        {
            $this->region       =   $regionCode;
            $this->couponCode   =   $couponCode;
            $this->total        =   $price;

            $this->UpdateTotal();

        }

        ///////////////////////////////////////
        /// Functions
        ///

        //Updates the total price based on current coupons, taxes, and price
        public function UpdateTotal()
        {
            include 'taxes.inc';
            include 'discount.inc';

            $this->myTax    =   array_sum($tax[$this->region]);

            if (isset($this->couponCode))
            {
                if (array_key_exists($this->couponCode, $coupon))
                {
                    $this->total *= (1 - $coupon[$this->couponCode]['amount']);
                }
            }

            $this->total *= (1 + $this->myTax);

        }

        //Updates price
        public  function  UpdatePrice(float $price)
        {
            $this->total = $price;

            $this->UpdateTotal();

        }

        //Change coupon
        public function ChangeCoupon($couponCode)
        {
            $this->couponCode = $couponCode;

            $this->UpdateTotal();
        }

        public function Charge()
        {
            //This function would hold transaction functionality.

            return $this->total;
        }


    }
