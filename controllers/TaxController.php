<?php
class TaxController {
    private $pdo;
    private $taxRates = [
        'US' => [
            'default' => 0.00,  // No tax by default
            'CA' => 0.0725,     // California
            'NY' => 0.04,       // New York state
            'TX' => 0.0625,     // Texas
            // Add more state rates as needed
        ],
        'CA' => [
            'default' => 0.05,  // Canada GST
            'ON' => 0.13,       // Ontario HST
            'BC' => 0.12,       // British Columbia PST + GST
            // Add more province rates as needed
        ],
        'GB' => [
            'default' => 0.20,  // UK VAT
        ]
    ];
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function calculateTax($subtotal, $country, $state = null) {
        if (!isset($this->taxRates[$country])) {
            return 0;
        }
        
        $rate = $this->taxRates[$country]['default'];
        if ($state && isset($this->taxRates[$country][$state])) {
            $rate = $this->taxRates[$country][$state];
        }
        
        return round($subtotal * $rate, 2);
    }
    
    public function getTaxRate($country, $state = null) {
        if (!isset($this->taxRates[$country])) {
            return 0;
        }
        
        if ($state && isset($this->taxRates[$country][$state])) {
            return $this->taxRates[$country][$state];
        }
        
        return $this->taxRates[$country]['default'];
    }
    
    public function formatTaxRate($rate) {
        return number_format($rate * 100, 2) . '%';
    }
}