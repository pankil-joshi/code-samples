        
        <ul>
<!--            <li>#instapaid: 46,319<br>
                <h6>www Visitors: 12,113</h6>
                <span>Total: 58,432</span></li>-->
            <li>

                Users: <?= isset($users) ? $users : '0'; ?>
            </li>
            <li>
                Merchants: <?= isset($merchants) ? $merchants : '0'; ?>
                <h6> Revenue: <?= isset($merchants_revenue['revenue_total']) && $merchants_revenue['revenue_total'] != '' ? $currencies[$merchants_revenue['base_currency_code']] . number_format($merchants_revenue['revenue_total'], 2) : '0'; ?></h6>
            </li>
            <?php if(!empty($transections)) { 
                foreach($transections as $index => $transection) {
                ?>
            <li><h5><u>Transactions in <?= $transection['currency_code'];?></u></h5>
                <h6>Transactions: <?= isset($transection['amount']) && $transection['amount'] != '' ? $currencies[$transection['currency_code']] . number_format($transection['amount'], 2) : '0'; ?></h6>
                <h6>Refunded: <?= isset($refunded['amount']) && $refunded['amount'] != '' ? $currencies[$refunded['currency_code']] . number_format($refunded['amount'], 2) : '0'; ?></h6>
                <span>Average: <?= isset($transection['average']) && $transection['average'] != '' ? $currencies[$transection['currency_code']] . number_format($transection['average'], 2) : '0'; ?></span>
                <h6> Commissions: <?= isset($commissions[$index]['amount']) && $commissions[$index]['amount'] != '' ? $currencies[$commissions[$index]['currency_code']] . number_format($commissions[$index]['amount'], 2) : '0'; ?></h6></li>
            <?php } } ?>
<!--            <li>
                Profit: £658,022.61
            </li>-->
        </ul>