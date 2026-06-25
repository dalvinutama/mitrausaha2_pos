<?php

return [
    // Auto-approve limit untuk Purchase Order (dalam Rupiah)
    'po_auto_approve_limit' => env('PO_AUTO_APPROVE_LIMIT', 5000000),

    // Biaya pemesanan (ordering cost) untuk kalkulasi EOQ (dalam Rupiah)
    'ordering_cost' => env('ORDERING_COST', 50000),

    // Persentase biaya simpan (holding cost) untuk EOQ
    'holding_cost_percent' => env('HOLDING_COST_PERCENT', 0.20),

    // Z-Score untuk safety stock (1.65 = 95% service level)
    'z_score' => env('Z_SCORE', 1.65),
];
