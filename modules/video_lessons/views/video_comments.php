<table class="table table-striped">
    <tbody>
    <?php
            foreach($comments as $comment) { ?>
        <tr>
            <td>
                <b><?= $comment->username; ?></b> (<?= date('l jS \of F Y \a\t h:i:s A', $comment->date_created); ?>)<br>
                <div><?= $comment->comment; ?></div>
            </td>
        </tr>
            <?php } ?>
    </tbody>
</table>

<style>
.table-striped td {
    padding: 0.6em;
}

.table-striped tr:nth-child(even) {
    background: #eee;
}
</style>