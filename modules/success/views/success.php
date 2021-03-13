<section class="neon">
    <div class="container paper">
        <div class="row">
            <div class="column">
                <h1>Thank You</h1>
                <p>Your transaction was successfully processed. Please check your email inbo for instructions on how to setup your account.</p>
                <p><?php 

                $attributes['class'] = 'button';
                echo anchor(BASE_URL, 'Return To HomePage', $attributes);
                ?></p>
           
            </div>
        </div>
    </div>
</section>