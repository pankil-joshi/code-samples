<?php

namespace App\Models;

use Quill\Database as Database;

class CurrencyRate  extends Database{
    
    public $tableName = 'currency_rates';

    public function updateRate($rate) {
        
        $rate['updated_at'] = gmdate('Y-m-d H:i:s');
        
        $this->table()->upsert($rate);
    }
    
    public function getByCurrencyCode($currencyCode) {
        
        return $this->table()->select('conversion_rate_usd_base')->where(array('currency_code' => $currencyCode))->field();
    }
    
}
