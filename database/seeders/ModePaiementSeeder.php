<?php

namespace Database\Seeders;

use App\Models\ModePaiement;
use App\Models\SystemePaiement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModePaiementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cash = ModePaiement::create([
            'MDP_LIBELLE' => 'Cash',
        ]);
        $cheque = ModePaiement::create([
            'MDP_LIBELLE' => 'Cheque',
        ]);

        //mobile money mode
        $mobileMoney = ModePaiement::create([
            'MDP_LIBELLE' => 'Mobile Money',
        ]);
        SystemePaiement::create([
            'MDP_ID_MOD_PAIEMENT' => $mobileMoney->MDP_ID_MOD_PAIEMENT,
            'SDP_LIBELLE' => 'MTN Mobile Money',
        ]);
        SystemePaiement::create([
            'MDP_ID_MOD_PAIEMENT' => $mobileMoney->MDP_ID_MOD_PAIEMENT,
            'SDP_LIBELLE' => 'Orange Money',
        ]);

        //credit card mode
        $creditCard = ModePaiement::create([
            'MDP_LIBELLE' => 'Credit Card',
        ]);
        SystemePaiement::create([
            'MDP_ID_MOD_PAIEMENT' => $creditCard->MDP_ID_MOD_PAIEMENT,
            'SDP_LIBELLE' => 'Visa',
        ]);
        SystemePaiement::create([
            'MDP_ID_MOD_PAIEMENT' => $creditCard->MDP_ID_MOD_PAIEMENT,
            'SDP_LIBELLE' => 'MasterCard',
        ]);
    }
}
