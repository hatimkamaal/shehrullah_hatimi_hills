<?php
do_for_post('__handle_post');


function __handle_post()
{
    $action = $_POST['action'];
    $label = $_POST['label'];
    $desc = $_POST['desc'];
    if ($action === 'register') {
        $result = add_payment_categories($label, $desc);

        if (is_null($result)) {
            setSessionData(TRANSIT_DATA, 'Added');
        } else {
            setSessionData(TRANSIT_DATA, $result);
        }
    }
}

function content_display()
{
    $categories = get_payment_categories();

    ?>
    <form method="post">
        <input type="hidden" name="action" value="register">
        <div class='col-xs-12'>
            <div class="mb-3 row">
                <label for="label" class="col-sm-3 col-form-label">Label</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="label" id="label">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="desc" class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="desc" name="desc">
                </div>
            </div>
            <div class="form-group" style="font-weight:20px;margin-top: 25px;">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </div>
    </form>
    <?php
    util_show_data_table($categories, [
        'label' => 'Label',
        'description' => 'Description'
    ]);
}