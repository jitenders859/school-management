<?php 
define('MEMBERSHIP_PRICE', 450);
define('DISCOUNT', 51);
$reduced_price = MEMBERSHIP_PRICE-DISCOUNT;
?>
<section class="neon">
	<div class="container paper">
        <div class="join-grid">
    		<div class="row">
    			<h1>Choose Your Payment Method</h1>	
    		</div>

            <div class="row">
                <div class="special"><span class="special-big"><?= date('F') ?> Special: </span> 
                Pay by crypto and enjoy full lifetime membership for only $<?= $reduced_price ?>!</div>
            </div>
            
            <div class="row">
                <div class="column"><h3><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/><path d="M0 0h24v24H0z" fill="none"/></svg> Your Cart</h3></div>
            </div>

            <div class="row">
                <div bp="grid vertical-end gap-none" id="bluep">
                    <div bp="6">Lifetime Membership</div>
                    <div bp="6 text-right">$<?= MEMBERSHIP_PRICE ?></div>
                    <div class="subtotal-row" none;" bp="6">Sub Total</div>
                    <div class="subtotal-row" none;" bp="6 text-right">$<?= MEMBERSHIP_PRICE ?></div>
                
                    <div class="discount-row" style="display: none;" bp="6">Discount</div>
                    <div class="discount-row" style="display: none;" bp="6 text-right">-$<?= DISCOUNT ?></div>
                
                    <div bp="6">&nbsp;</div>
                    <div bp="6 text-right">usd $<span class="grand-total" id="grand-total"><?= MEMBERSHIP_PRICE ?></span></div>
                
                    <div bp="12">
                        Payment Method
                    </div>
                
                    <div bp="6">
                        <button id="choosePaypalBtn" onclick="selectPaymentOption('paypal')" class="button-outline">Paypal</button> 
                        <button id="chooseCryptoBtn" onclick="selectPaymentOption('crypto')" class="button-outline">Crypto</button>
                    </div>
                    <div bp="6 text-right">

                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" id="next-btn-paypal" style="display: none;">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="SVBWA9D395JY8">
                        <button type="submit" class="button button-success">Next</button>
                        <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                        </form>

                        <form action="https://www.coinpayments.net/index.php" id="next-btn-crypto" method="post" style="display: none;">
                            <input type="hidden" name="cmd" value="_pay_simple">
                            <input type="hidden" name="reset" value="1">
                            <input type="hidden" name="merchant" value="024605abfb56906978198ee42420d3c4">
                            <input type="hidden" name="item_name" value="Speed Coding Academy Membership">
                            <input type="hidden" name="item_desc" value="Full lifetime membership to Speed Coding Academy.">
                            <input type="hidden" name="item_number" value="SCA">
                            <input type="hidden" name="currency" value="USD">
                            <input type="hidden" name="amountf" value="<?= $reduced_price ?>">
                            <input type="hidden" name="want_shipping" value="0">
                            <input type="hidden" name="success_url" value="<?= BASE_URL ?>thankyou">
                            <input type="hidden" name="cancel_url" value="<?= BASE_URL ?>cancel">
                            <button type="submit" class="button button-success">Next</button>
                        </form>
                    </div>
                </div>
            </div>

	        <div class="row" id="cryptocoins">
	            <div class="column">
	                <img src="join_module/images/bitcoin.png" alt="bitcoin">
	                Bitcoin
	            </div>
	            <div class="column">
	                <img src="join_module/images/ethereum.png" alt="ethereum">
	                Ethereum
	            </div>
	            <div class="column">
	                <img src="join_module/images/xrp.png" alt="xrp">
	                XRP
	            </div>
	            <div class="column">
	                <img src="join_module/images/bitcoin_cash.png" alt="bitcoin cash">
	                Bitcoin Cash
	            </div>
	            <div class="column">
	                <img src="join_module/images/tron.png" alt="tron">
	                Tron
	            </div>
	            <div class="column">
	                <img src="join_module/images/litecoin.png" alt="litecoin">
	                Litecoin
	            </div>
	            <div class="column">
	                <img src="join_module/images/dash.png" alt="dash">
	                Dash
	            </div>
	            <div class="column">
	                <img src="join_module/images/ethereum_classic.png" alt="ethereum classic">
	                Ethereum Classic
	            </div>
	        </div><!-- end of crypto coins -->

	        <div class="row">
	            <div class="column" id="paypal-info">NOTE: Paypal accepts all major credit and debit cards (no need to have a Paypal account). Crypto payments are processed by CoinPayments.net.</div>
	        </div>
        </div><!-- end of join grid -->
	</div>
</section>

<style>
    .neon {
        background-image: url("<?= BASE_URL ?>images/neon.jpg");
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        min-height: 80vh;  
    }

    .paper {
        background-image: url("<?= BASE_URL ?>images/ricepaper2.png");
        color: #000;
    }

    .join-grid {
        max-width: 900px;
        min-height: 80vh;
        margin: 0 auto;
    }

    .join-grid h1 {
    	text-align: center;
    	width: 100%;
    	margin-top: 33px;
    }

    .join-grid h3 {
        margin-top: 33px;
        margin-bottom: 0;
    }

    .join-grid > .row {
        font-size: 2rem;
        font-weight: bold;
    }

    .join-grid > .row > div {
        padding: 11px 0;
    }

    .join-grid > .row > div:nth-child(2) {
        text-align: right;
    }

    .join-grid .large {
        font-size: 3rem;
        font-weight: normal;
        padding-top: 33px;
    }

    button.button-outline {
    	background-color: #eee;
    }

    button.button-outline:hover {
    	background-color: #fff;
    }

    .button-success {
        color: #FFF;
        background-color: #24ad46;
        border-color: #24ad46;
    }

    .button-success:hover {
        background-color: #208c3b;
        border-color: #208c3b;
    }

    #cryptocoins {
        max-width: 820px;
        margin: 45px auto 0 auto;
    }

    #cryptocoins > div {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        text-align: center;
    }

    #paypal-info {
        margin-top: 45px;
    }

    .grand-total {
        font-size: 2em;
        color: green;
    }

    .special {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
        width: 100%;
        justify-content: center;
        text-align: center;
        border-radius: 4px;
        font-weight: normal;      
    }

    .special-big {
        text-transform: uppercase;
        font-weight: bold;
    }

    #bluep {
        width: 100%;
    }

    #bluep > div {
        padding: 12px 0;
        border-bottom: 1px silver solid;
    }

</style>


<script>
var price = <?= MEMBERSHIP_PRICE ?>;
var discount = <?= DISCOUNT ?>;
var choosePaypalBtn = document.getElementById("choosePaypalBtn");
var chooseCryptoBtn = document.getElementById("chooseCryptoBtn");
var subTotalEls = document.getElementsByClassName("subtotal-row");
var discountEls = document.getElementsByClassName("discount-row");

function selectPaymentOption(paymentOption) {

    if (paymentOption == 'paypal') {
        document.getElementById("next-btn-paypal").style.display = 'inline';
        document.getElementById("next-btn-crypto").style.display = 'none';
        document.getElementById("cryptocoins").style.display = 'none';
        document.getElementById("paypal-info").style.display = 'block';

        for (var i = 0; i < subTotalEls.length; i++) {
            subTotalEls[i].style.display = 'block';
        }

        for (var i = 0; i < discountEls.length; i++) {
            discountEls[i].style.display = 'none';
        }

        document.getElementById("grand-total").innerHTML = price;
        activateBtn('paypal');
    }

    if (paymentOption == 'crypto') {
        document.getElementById("next-btn-crypto").style.display = 'inline';
        document.getElementById("next-btn-paypal").style.display = 'none';
        document.getElementById("cryptocoins").style.display = 'flex';
        document.getElementById("paypal-info").style.display = 'none';

        for (var i = 0; i < subTotalEls.length; i++) {
            subTotalEls[i].style.display = 'none';
        }

        for (var i = 0; i < discountEls.length; i++) {
            discountEls[i].style.display = 'block';
        }

        document.getElementById("grand-total").innerHTML = price-discount;
        activateBtn('crypto');
    }

}

function activateBtn(btnType) {

    if (btnType == 'paypal') {
        var selectedBtn = choosePaypalBtn;
        var notSelectedBtn = chooseCryptoBtn;
    } else {
        var selectedBtn = chooseCryptoBtn;
        var notSelectedBtn = choosePaypalBtn;
    }

    selectedBtn.classList.remove("button-outline");
    selectedBtn.classList.add("button");
    selectedBtn.style.backgroundColor = '#9b4dca';
    selectedBtn.style.borderColor = '#9b4dca';

    notSelectedBtn.classList.remove("button");
    notSelectedBtn.classList.add("button-outline");
    notSelectedBtn.style.backgroundColor = '#fff';
    notSelectedBtn.style.borderColor = '#9b4dca';

}
</script>