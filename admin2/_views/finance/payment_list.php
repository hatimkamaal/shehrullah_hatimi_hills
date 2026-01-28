<?php

function content_display()
{
    $categories = get_all_payment();    
    util_show_data_table($categories, [
        '__show_row_sequence'=>'S/No',
        'label' => 'Category',
        'date' => 'Date',
        'amount' => 'Amount',
        'pay_mode' => 'Pay_mode',
        'upi_ref' => 'UPI ref',
        'paid_by' => 'Paid BY',
        'remarks' => 'Remarks'
    ]);
}