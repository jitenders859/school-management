    <div class="row">
        <div><a href="<?= $prev_url ?>" class="button button-outline">Prev</a></div>
        <div>
            <div>BUILD AN ONLINE JEWELLERY SHOP: LESSON 99</div>
            <div><h1>The Paypal Handshake</h1></div>
        </div>
        <div><a href="<?= $next_url ?>" class="button button-outline">Next</a></div>
    </div>
    <div class="row">
        <div class="column">
            <?= Modules::run('video_player/_draw', 'https://samplevid.s3.eu-west-2.amazonaws.com/speed_is_what_we_need.mp4', $id, $token) ?>
        </div>
    </div>
    <div class="row">
        <div class="column">
            <h4>Lesson Synopsis</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita tempora fuga ut odio alias eveniet nam voluptatibus, tenetur neque aperiam molestiae, voluptate saepe natus, reprehenderit, nemo iusto consequuntur? Voluptatem, iste!</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita tempora fuga ut odio alias eveniet nam voluptatibus, tenetur neque aperiam molestiae, voluptate saepe natus, reprehenderit, nemo iusto consequuntur? Voluptatem, iste!</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita tempora fuga ut odio alias eveniet nam voluptatibus, tenetur neque aperiam molestiae, voluptate saepe natus, reprehenderit, nemo iusto consequuntur? Voluptatem, iste!</p>
        </div>
        <div class="column" style="padding-left: 4em;">
            <h4>Essential-O-Meter</h4>
            <?= Modules::run('essentialometer/_draw', 88) ?>
        </div>
    </div>
    <?= Modules::run('video_lessons/_attempt_draw_resources', $id); ?>

    <div class="row">
        <div class="column">
            <h4>Code Snippets</h4>
            <?= $code_snippets ?>
        </div>
    </div>
    <?php
echo form_open('x');
echo '<fieldset>';
echo '<label for="commentField">Post a question, comment or suggestion</label>';
echo form_textarea('comment');
echo '<div class="float-right">';
$btn_attributes['class'] = 'button';
echo form_submit('submit', 'Submit Comment');
echo '</div>';
echo '<fieldset>';
echo form_close();
?>

<?= Modules::run('video_lessons/_attempt_draw_comments', $id); ?>
</div>

<script>
    var vid = document.getElementById('my-video');

    vid.addEventListener('ended', (ev) => {
        alert('The video has ended');
    })
</script>