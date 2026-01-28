<?php
if (!(is_user_role(SUPER_ADMIN) || is_user_role(FINANCE))) {
    do_redirect_with_message('/home', 'Redirected as tried to access unauthorized area.');
}
function content_display()
{
    $hijri_year = get_current_hijri_year();
    $receipt_data = get_all_receipt_data_for($hijri_year);
    ?>
    <p>Receipt History</p>
    <?php __display_table_records([$receipt_data]) ?>
    <?php
}

function __display_table_records($data)
{
    $records = $data[0];
    util_show_data_table($records, [
        '__show_row_sequence' => 'SN#',
        'id' => 'Receipt ID',
        '__date_format' => 'Date',        
        'payment_mode' => 'Mode',        
        'hof_id' => 'HOF',
        'amount' => 'Amount',
        'remarks' => 'Remarks',
        '__print_link' => 'Print'
    ]);
}


function __date_format($row, $index) {
    $date=date_create($row->created);
    return date_format($date,"d/m/Y");;
}

function __print_link($row, $index) {
    $receipt_num = $row->id;
    $uri = getAppData('BASE_URI');
    return "<a target='receipt' class='btn btn-primary' href='$uri/receipt/$receipt_num'>Print</a>";
}