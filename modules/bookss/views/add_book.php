<!-- Add New Book Area Start Here -->
<div class="card height-auto">
    <div class="card-body">
        <div class="heading-layout1">
            <div class="item-title">
                <h3>Add New Book</h3>
            </div>
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button"
                data-toggle="dropdown" aria-expanded="false">...</a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#"><i class="fas fa-times text-orange-red"></i>Close</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-cogs text-dark-pastel-green"></i>Edit</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-redo-alt text-orange-peel"></i>Refresh</a>
                </div>
            </div>
        </div>
        <form class="new-added-form" action="<?= $form_location ?>" method="post">
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-12 form-group">
                    <label>Book Name *</label>
                    <input type="text" name="book_name" value="<?= $book_name ?>" placeholder="" class="form-control">
                </div>
                <div class="col-xl-3 col-lg-6 col-12 form-group">
                    <label>Subject *</label>
                    <input type="text" name="subject" value="<?= $subject ?>"  placeholder="" class="form-control">
                </div>
                <div class="col-xl-3 col-lg-6 col-12 form-group">
                    <label>Writter Name *</label>
                    <input type="text" name="writter_name" value="<?= $writter_name ?>"  placeholder="" class="form-control">
                </div>
                <div class="col-xl-3 col-lg-6 col-12 form-group">
                    <label>Class *</label>

                    <?php
                    $attributes['class'] = 'select2';
                    echo form_dropdown('classes_id', $classes_options, $classes_id, $attributes);
                    ?>
                    <!-- <select class="select2">
                        <option value="">Please Select Class *</option>
                        <option value="1">Play</option>
                        <option value="2">Nursery</option>
                        <option value="3">One</option>
                        <option value="3">Two</option>
                        <option value="3">Three</option>
                        <option value="3">Four</option>
                        <option value="3">Five</option>
                    </select> -->
                </div>
                <div class="col-xl-3 col-lg-6 col-12 form-group">
                    <label>ID No</label>
                    <input type="text" name="id_no" value="<?= $id_no ?>" placeholder="" class="form-control">
                </div>
                <div class="col-xl-3 col-lg-6 col-12 form-group">
                    <label>Publishing Date *</label>
                    <input type="text" name="published_date" value="<?= $published_date ?>"  placeholder="dd/mm/yyyy" class="form-control air-datepicker" data-position="bottom right">
                    <i class="far fa-calendar-alt"></i>
                </div>


                <div class="col-md-3 d-none d-xl-block form-group">

                </div>
                <div class="col-12 form-group mg-t-8">
                    <button type="submit" class="btn-fill-lg btn-gradient-yell 
                    <button type="reset" class="btn-fill-lg bg-blue-dark btn-hover-yellow">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Add New Book Area End Here -->
