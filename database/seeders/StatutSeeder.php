<?php

namespace Database\Seeders;

use App\Models\Statut;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuts = [
            // Order statuses
            ['STA_ID_STATUT' => 1, 'STA_LIBELLE' => 'Actived'],
            ['STA_ID_STATUT' => 2, 'STA_LIBELLE' => 'Deactivated'],
            ['STA_ID_STATUT' => 3, 'STA_LIBELLE' => 'Pending Order'],//The customer has visited the restaurant or the website but hasn\'t placed an order yet.
            ['STA_ID_STATUT' => 4, 'STA_LIBELLE' => 'Order Received'],//The restaurant has received the customer\'s order and is preparing it.
            ['STA_ID_STATUT' => 5, 'STA_LIBELLE' => 'In Preparation',],//The restaurant staff is currently preparing the customer\'s order.
            ['STA_ID_STATUT' => 6, 'STA_LIBELLE' => 'Out for Delivery'],//The order is out for delivery to the customer\'s address.
            ['STA_ID_STATUT' => 7, 'STA_LIBELLE' => 'Order Delivered'],//The order has been successfully delivered to the customer.
            ['STA_ID_STATUT' => 8, 'STA_LIBELLE' => 'Order Cancelled'],//The customer has cancelled the order before it was prepared or delivered.
            ['STA_ID_STATUT' => 9, 'STA_LIBELLE' => 'Delivery Issue'],//There was a problem with the delivery, e.g., the restaurant couldn\'t find the address or the customer was unreachable.
            ['STA_ID_STATUT' => 10, 'STA_LIBELLE' => 'Order Completed'],//The order has been successfully completed, and all stages of the process are finished.

            // Payment statuses
            ['STA_ID_STATUT' => 11, 'STA_LIBELLE' => 'Waiting for Payment'],//The customer has yet to make the payment
            ['STA_ID_STATUT' => 12, 'STA_LIBELLE' => 'Payment Received'],//The customer has made the payment

            // Reservation statuses
            ['STA_ID_STATUT' => 13, 'STA_LIBELLE' => 'Pending Reservation'],//The customer has requested a reservation but hasn\'t received confirmation yet.
            ['STA_ID_STATUT' => 14, 'STA_LIBELLE' => 'Reservation Confirmed'],//The reservation has been confirmed by the restaurant.
            ['STA_ID_STATUT' => 15, 'STA_LIBELLE' => 'Reservation Cancelled'],//The reservation has been cancelled by the customer or the restaurant.
        ];
        foreach ($statuts as $statutData) {
            Statut::create($statutData);
        }
    }
}
