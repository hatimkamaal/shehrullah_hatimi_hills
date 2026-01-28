<?php
echo 'not allowed';
exit;
do_for_post('__handle_post');

function __handle_post()
{

    $amount = $_POST['amount'];
    $pay_mode = $_POST['payment_mode'];
    $upi_ref = $_POST['transaction_ref'];
    $remarks = $_POST['remarks'];
    $category  = $_POST['category'];
    $paid_by  = $_POST['paid_by'];
    $date  = $_POST['date'];    

    $receipt_num = add_payment($category,$amount,$date,$pay_mode, $upi_ref,$paid_by,$remarks);

    if( $receipt_num == -1 ) {
        do_redirect_with_message('/home', 'Failed to create the entry.');
    } else {            
        do_redirect_with_message('/home', "Entry created");
    }
}

function content_display()
{
    $categories = get_payment_categories();
    $dropdown = [];
    foreach ($categories as $row) {  
        $dropdown[$row->id] = $row->label;
    }
    ?>
    <form method="post">
        <input type="hidden" name="action" value="register">
        <div class='col-xs-12'>
            <div class="mb-3 row">
                <label for="hof_id" class="col-sm-3 col-form-label">Category</label>
                <div class="col-sm-9">
                <?php util_get_dropdown('category', $dropdown, ''); ?>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="hof_id" class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-9">
                    <input type="date" required class="form-control" id="date" name="date">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="hof_id" class="col-sm-3 col-form-label">amount</label>
                <div class="col-sm-9">
                    <input type="text" required pattern="^[0-9]{1,9}$" class="form-control" name="amount">
                </div>
            </div>            
            <div class="mb-3 row">
                <label for="gender" class="col-sm-3 col-form-label">Mode (online | cash)</label>
                <div class="col-sm-9">
                <select class="form-control form-control-lg" required name="payment_mode" id="payment_mode">
                        <option  value="">Select...</option>
                        <option value="cash">Cash</option>
                        <option value="online">Online</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
            </div> 
            <div class="mb-3 row" id="transaction_ref_section">
                <label for="hof_id" class="col-sm-3 col-form-label">Reference No</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="transaction_ref" name="transaction_ref"
                        value="">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="hof_id" class="col-sm-3 col-form-label">Paid By</label>
                <div class="col-sm-9">
                <select class="form-control form-control-lg" required name="paid_by" id="paid_by">
                        <option  value="">Select...</option>
                        <option value="saifuddin">Saifuddin</option>
                        <option value="yusuf">Yusuf</option>
                        <option value="abdeali">Abdeali</option>
                    </select>
                    
                </div>
            </div>
            <div class="mb-3 row">
                <label for="hof_id" class="col-sm-3 col-form-label">Remarks</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="remarks" name="remarks">
                </div>
            </div>           
            <div class="form-group" style="font-weight:20px;margin-top: 25px;">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </div>        
    </form>
    <script>
        function the_script() {
            $('#transaction_ref_section').hide();
            $('#payment_mode').on('change', function() {
                var selectedValue = $(this).val();
                if( selectedValue == "online" || selectedValue == "cheque" ) {
                    $('#transaction_ref_section').show();
                    $("#transaction_ref").prop('required',true);
                } else {
                    $('#transaction_ref_section').hide();
                    $("#transaction_ref").prop('required',false);
                }
            }); 
        }
    </script>
    
    <?php
}