<?php

use CheckoutFinland\SDK\Client;
use CheckoutFinland\SDK\Exception\HmacException;
use CheckoutFinland\SDK\Exception\ValidationException;
use CheckoutFinland\SDK\Model\CallbackUrl;
use CheckoutFinland\SDK\Request\PaymentRequest;
use CheckoutFinland\SDK\Model\Customer;
use CheckoutFinland\SDK\Model\Address;
use CheckoutFinland\SDK\Model\Item;
use CheckoutFinland\SDK\Response\PaymentResponse;

/**
 * Class Payment
 */
class Payment
{

    /**
     * Handle payment data and create payment with SDK client
     *
     * @param $data
     *
     * @return PaymentResponse|string
     */
    public function processPayment($data) {

        try {
            $client = new Client(
                375917,
                'SAIPPUAKAUPPIAS',
                [
                    'cofPluginVersion' => 'php-sdk-test-1.0.0',
                ]
            );

            $payment = new PaymentRequest();

            $this->setPaymentData($payment, $data);

            $response = $client->createPayment($payment);

            return $response;

        } catch (RequestException $e) {
            $errorMsg = $e->getMessage();

        } catch (HmacException $e) {
            $errorMsg = $e->getMessage();

        } catch (ValidationException $e) {
            $errorMsg = $e->getMessage();

        }
        return $errorMsg;

    }

    /**
     * Set data for Payment Request
     *
     * @param PaymentRequest $payment
     * @param array $data
     *
     * @return PaymentRequest
     */
    public function setPaymentData($payment, $data) {

        $payment->setStamp(hash('sha256', time()));

        $payment->setReference('your order reference');

        $payment->setCurrency('EUR');

        $payment->setLanguage('FI');

        $customer = $this->createCustomer($data);

        $payment->setCustomer($customer);

        $invoicingAddress = $this->createAddress($data);

        $payment->setInvoicingAddress($invoicingAddress);
        $payment->setDeliveryAddress($invoicingAddress);

        $items = $this->mapOrderItems();

        $payment->setItems($items);

        $payment->setRedirectUrls($this->createRedirectUrl());

        $payment->setCallbackUrls($this->createCallbackUrl());

        return $payment;
    }

    /**
     * Set data for Customer model
     *
     * @param array $data
     *
     * @return Customer
     */
    private function createCustomer($data) {

        $customer = new Customer();

        $customer->setEmail($data['email'])
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setPhone($data['phone']);

        return $customer;
    }

    /**
     * Set data for Address model
     *
     * @param array $data
     *
     * @return Address
     */
    private function createAddress($data) {

        $address = new Address();

        $address->setStreetAddress( $data['address'])
            ->setPostalCode( $data['postalCode'])
            ->setCity( $data['city'])
            ->setCounty( $data['county'])
            ->setCountry( $data['country']);

        return $address;
    }

    /**
     * Set order items data.
     * The actual order items must exist or this function does nothing.
     *
     * return array
     */
    private function mapOrderItems() {

        //Mockup function getOrderItems() for getting order item data
        $orderItems = $this->_orderModel->getOrderItems();

        //Loop through and map all order items
        $items = array_map(
            function ($item) {
                return $this->createItems($item);
            },
            $orderItems
        );

        return $items;
    }

    /**
     * Set data for Item model
     */
    private function createItems($item) {

        $orderItem = new Item();

        $orderItem->setUnitPrice($item['price'])
            ->setUnits($item['amount'])
            ->setVatPercentage($item['vat'])
            ->setProductCode($item['code'])
            ->setDeliveryDate(date('Y-m-d'))
            ->setDescription($item['title']);

        return $orderItem;
    }

    /**
     * Set redirect urls
     */
    private function createRedirectUrl() {

        $callback = new CallbackUrl();

        $callback->setSuccess('success_return_url');
        $callback->setCancel('cancel_url');

        return $callback;
    }

    /**
     * Set callback urls
     */
    private function createCallbackUrl() {

        $callback = new CallbackUrl();

        $callback->setSuccess('callback_success_url');
        $callback->setCancel('callback_cancel_url');

        return $callback;
    }

}