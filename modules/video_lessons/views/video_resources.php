<div class="row">
    <div class="column">
        <h4>Resources</h4>
        <p>
            <?php
            foreach($resources as $resource) {
                if($resource->downloadable == 1) {
                    $class = 'button';
                    $icon_code = '<i class="fa fa-download"></i>';
                    $extra_code = 'download';
                } else {
                    $class = 'button button-outline';
                    $icon_code = '<i class="fa fa-external-link-alt"></i>';
                    $extra_code = '';
                }

                $attributes['class'] = $class;

                echo anchor($resource->target_url, $icon_code." ".$resource->button_title, $attributes, $extra_code);
            } ?>
        </p>
    </div>
</div>