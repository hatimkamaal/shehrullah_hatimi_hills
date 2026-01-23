<?php
$date = date("d/m/Y");
$print = false;
?>
<style>
    .smalltext {
        font-size: 11px;
    }

    <?php if (!$print) { ?>
        #printableArea {
            position: relative;
        }

        #printableArea::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: repeating-linear-gradient(-45deg,
                    transparent,
                    transparent 10px,
                    rgba(220, 53, 69, 0.02) 10px,
                    rgba(220, 53, 69, 0.02) 20px);
            pointer-events: none;
            z-index: 1;
        }

        .watermark-layer {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 2;
            overflow: hidden;
        }

        .watermark-layer::after {
            content: 'ADMIN PRINT ONLY   •   NOT FOR SELF PRINTING   •   ADMIN PRINT ONLY   •   NOT FOR SELF PRINTING   •   ADMIN PRINT ONLY   •   NOT FOR SELF PRINTING   •   ADMIN PRINT ONLY   •   NOT FOR SELF PRINTING   •   ';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 36px;
            font-weight: 900;
            color: rgba(220, 53, 69, 0.12);
            white-space: nowrap;
            width: 300%;
            text-align: center;
            line-height: 150px;
            letter-spacing: 3px;
        }

        #printableArea .card-body {
            position: relative;
            z-index: 3;
        }

    <?php } ?>
</style>
<?php if (!$print) { ?>
    <div class="alert alert-primary" role="alert">
        <strong>
            <h2>Shukran! Data Collected. Form will be printed on the Takhmeen day.</h2>
        </strong>
    </div>
<?php } ?>
<div class="card" id="printableArea">
    <?php if (!$print) { ?>
        <div class="watermark-layer"></div>
    <?php } ?>
    <div class="card-body">
        <table class='table table-bordered'>
            <tr>
                <td>HHM</td>
                <td>
                    <table class='table table-bordered'>
                        <tr>
                            <th style='font-size: 12px'>SHEHRULLAH 1447H / Hatimi Hills Markaz
                            </th>
                            <td><?= $date ?></td>
                        </tr>
                    </table>
                </td>

            </tr>
        </table>

        <table class='table table-bordered'>
            <tr>
                <th style='font-size: 12px'>HOF</th>
                <td style='font-size: 12px' colspan="5">[<?= $dao->hofData->hof_id ?>] <?= $dao->hofData->full_name ?>
                </td>
            </tr>
            <tr>
                <th style='font-size: 12px; width: 25%'>Sabil</th>
                <td style='font-size: 12px; width: 25%'>NA</td>
                <th style='font-size: 12px; width: 25%'>WApp</th>
                <td style='font-size: 12px; width: 25%'>Fill_WA</td>
            </tr>
            <tr>
                <th style='font-size: 12px'>Addr:</th>
                <td style='font-size: 12px' colspan="5">Fill_Address</td>
            </tr>
        </table>

        <table class='table table-bordered'>
            <tr>
                <th style='font-size: 12px'>SN</th>
                <th style='font-size: 12px'>ITS - NAME</th>
                <th style='font-size: 12px'>Gender/Age</th>
                <th style='font-size: 12px'>Chair</th>
                <th style='font-size: 12px'>Mohallah</th>
            </tr>
            <?php
            $index = 0;
            foreach ($dao->attendees_records as $attendees) {
                $atten_pref = $attendees->attend_pref;
                if( $atten_pref == 'N' ) {
                    continue;
                }

                $its = $attendees->its_id;
                $name = $attendees->full_name;
                $index++;
            
                $age = $attendees->age;
                $gender = $attendees->gender;
                $gender = substr($gender, 0, 1);

                $chair_preference = $attendees->chair_preference;
                $mohalla = substr($attendees->mohallah ?? "Other", 0, 1);

                echo "<tr>
                        <td style='font-size: 12px'>$index</td>
                        <td style='font-size: 12px'>$its - $name</td>
                        <td style='font-size: 12px'>$gender/$age</td>
                        <td style='font-size: 12px'>$atten_pref</td>
                        <td style='font-size: 12px'><b>$mohalla</b></td>                        
                        </tr>";
            }
            ?>
        </table>

        <table class='table table-bordered'>
            <tr>
                <th style='font-size: 12px'>Niyaz Khdimat</th>
                <th style='font-size: 12px'>Hub</th>
            </tr>
            <tr>
                <th style='font-size: 12px'>Full Niyaz</th>
                <td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i><?= $dao->shehrullah_data->full_niyaz ?>
                </td>
            </tr>
            <tr>
                <th style='font-size: 12px'>Half Niyaz</th>
                <td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i><?= $dao->shehrullah_data->half_niyaz ?>
                </td>
            </tr>
            <tr>
                <th style='font-size: 12px'>Per Head Hub</th>
                <td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i><?= $dao->shehrullah_data->family_niyaz ?>
                </td>
            </tr>
            <tr>
                <th style='font-size: 12px'>Kids Hub</th>
                <td style='font-size: 12px'><i
                        class='mdi mdi-currency-inr'></i><?= $dao->shehrullah_data->per_kid_niyaz ?></td>
            </tr>
            <tr>
                <th style='font-size: 12px'>Pirsa</th>
                <td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i><?= $dao->shehrullah_data->pirsu ?></td>
            </tr>
            <tr>
                <th style='font-size: 12px'>Chair</th>
                <td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i><?= $dao->shehrullah_data->chair ?></td>
            </tr>
        </table>

        <table class='table table-bordered small-text'>
            <tr>
                <th style='font-size: 12px' colspan="4">Kindly submit form to receive izan card & carry izan card for
                    our
                    convenience.</th>
            </tr>
            <tr>
                <th style='font-size: 12px; width: 25%'>Niyaz Amount</th>
                <td style='font-size: 12px; width: 25%'>SHOW_PREV_NIYAZ</td>
                <th style='font-size: 12px; width: 25%'>Committed Hub Amount</th>
                <td style='font-size: 12px; width: 25%'></td>
            </tr>
        </table>

        <!-- Signature Section -->
        <div style="margin-top: 40px; display: flex; justify-content: space-between; padding: 0 20px;">
            <div style="width: 45%; text-align: center;">
                <div style="border-bottom: 2px solid #000; height: 60px; margin-bottom: 8px;"></div>
                <p style="font-size: 12px; font-weight: bold; margin: 0;">HOF Signature</p>
            </div>
            <div style="width: 45%; text-align: center;">
                <div style="border-bottom: 2px solid #000; height: 60px; margin-bottom: 8px;"></div>
                <p style="font-size: 12px; font-weight: bold; margin: 0;">Authorized Signature</p>
            </div>
        </div>
    </div>
</div>
<?php if ($print) { ?>
    <div class="card">
        <div class='card-footer row' id='print_button_section'>
            <div class='col-12'>
                <button class='btn btn-primary' id='Print'>Print</button>
            </div>
        </div>
    </div>
    <script>
        function the_script() {
            $('#Print').click(function () {
                var printContents = document.getElementById('printableArea').innerHTML;
                var originalContents = document.body.innerHTML;

                var htmlToPrint = '' +
                    '<style type="text/css">' +
                    'table th, table tr, table td {' +
                    'border:1px solid #000;' +
                    'padding:0.5em;' +
                    '}' +
                    '</style>';
                htmlToPrint += printContents;


                document.body.innerHTML = htmlToPrint;
                window.print();
                document.body.innerHTML = originalContents;
            });
        }
    </script>
    <?php
} else {
    ?>
    <style type="text/css" media="print">
        * {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('keydown', function (event) {
            if (event.ctrlKey && event.key === 'p') {
                event.preventDefault();
            }
        });
    </script>
    <?php
}

// function __display_niyaz_section(...$data)
// {

//     $family_niyaz = getAppData('family_niyaz');

//     $takhmeen_data = (object) $data[0];
//     $markaz_data = $data[1];

//     $family_hub = $takhmeen_data->family_hub;
//     $niyaz_type = $takhmeen_data->niyaz_type;
//     $niyaz_count = $takhmeen_data->niyaz_count;
//     $niyaz_hub = get_niyaz_amount_for($niyaz_type, $family_hub, $markaz_data);
//     $total_niyaz = $niyaz_hub * $niyaz_count;

//     $full_niyaz_count = $niyaz_type == 'full' ? 1 : 0;
//     $half_niyaz_count = $niyaz_type == 'half' ? 1 : 0;
//     $family_niyaz_count = $niyaz_type == 'family' ? 1 : 0;

//     $full_niyaz_total = $markaz_data->full_niyaz * $full_niyaz_count;
//     $half_niyaz_total = $markaz_data->half_niyaz * $half_niyaz_count;
//     $family_niyaz_total = $family_hub * $family_niyaz_count;


//     $sehori_count = $takhmeen_data->sehori_count;
//     $sehori_hub = $markaz_data->sehori;//SHEHRULLAH_CONFIG->SEHORI;
//     $sehori_total = $sehori_count * $sehori_hub;

//     $net_total = $sehori_total + $total_niyaz;

//     $zabihat_count = $takhmeen_data->zabihat_count;
//     $zabihat_hub = $markaz_data->zabihat;//SHEHRULLAH_CONFIG->ZABIHAT;
//     $zabihat_total = $zabihat_count * $zabihat_hub;

//     $iftar_count = $takhmeen_data->iftar_count;
//     $iftar_hub = $markaz_data->iftar;//SHEHRULLAH_CONFIG->IFTAR;
//     $iftar_total = $iftar_count * $iftar_hub;

//     $iftar_fadilraat_hub = 0;//SHEHRULLAH_CONFIG->IFTAR_FADILRAAT;
//     $iftar_fadilraat_count = $takhmeen_data->iftar_fadilraat;
//     $iftar_fadilraat_total = $iftar_fadilraat_count * $iftar_fadilraat_hub;

//     $khajoor_count = $takhmeen_data->khajoor_count;
//     $khajoor_hub = $markaz_data->khajoor;//SHEHRULLAH_CONFIG->KHAJOOR;
//     $khajoor_total = $khajoor_count * $khajoor_hub;


//     $fateha_count = 0;//$takhmeen_data->fateha_count;
//     $fateha_hub = $markaz_data->fateha;//SHEHRULLAH_CONFIG->CHAIR;
//     $fateha_total = $fateha_count * $fateha_hub;

//     $pirsa_count = $takhmeen_data->pirsa_count;
//     $pirsa_hub = $markaz_data->pirsu;//SHEHRULLAH_CONFIG->PIRSA;
//     //$pirsa_total = $pirsa_count * $pirsa_hub;
//     $pirsa_selection = $pirsa_count > 0 ? '1' : '0';

//     $chair_count = $takhmeen_data->chair_count;
//     $chair_hub = $markaz_data->chair;//SHEHRULLAH_CONFIG->CHAIR;
//     $chair_total = $chair_count * $chair_hub;

// 	//20Jan - Other section removed.
//         echo "
//     <table class='table table-bordered'>
//         <tr>                            
//             <th style='font-size: 12px'>Niyaz Khdimat</th><th style='font-size: 12px'>Hub</th>
//         </tr>
//         <tr>            
//             <th style='font-size: 12px'>Full Niyaz</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$markaz_data->full_niyaz</td>
//         </tr>
//         <tr>            
//             <th style='font-size: 12px'>Half Niyaz</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$markaz_data->half_niyaz</td>
//         </tr>
//         <tr>            
//             <th style='font-size: 12px'>Per Head Hub</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$markaz_data->family_niyaz</td>
//         </tr>
//         <tr>            
//             <th style='font-size: 12px'>Kids Hub</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$markaz_data->per_kid_niyaz</td>
//         </tr>
//         <tr>               
//             <th style='font-size: 12px'>Pirsa</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$pirsa_hub</td>
//         </tr>
//         <tr>               
//             <th style='font-size: 12px'>Chair</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$chair_hub</td>
//         </tr>  
//         </table>      
//     ";

//     // echo "
//     // <table class='table table-bordered'>
//     //     <tr>                            
//     //         <th style='font-size: 12px'>Niyaz Khdimat</th><th style='font-size: 12px'>Hub</th><th style='font-size: 12px'>Count</th>
//     //         <th style='font-size: 12px'>Other Khidmat</th><th style='font-size: 12px'>Hub</th><th style='font-size: 12px'>Count</th>
//     //     </tr>
//     //     <tr>            
//     //         <th style='font-size: 12px'>Full Niyaz</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$markaz_data->full_niyaz</td><td>&nbsp;</td>
//     //         <th style='font-size: 12px'>Iftar</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$iftar_hub</td><td>&nbsp;</td>
//     //     </tr>
//     //     <tr>            
//     //         <th style='font-size: 12px'>Half Niyaz</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$markaz_data->half_niyaz</td><td>&nbsp;</td>
//     //         <th style='font-size: 12px'>Zabihat</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$zabihat_hub</td><td>&nbsp;</td>
//     //     </tr>
//     //     <tr>            
//     //         <th style='font-size: 12px'>Family Niyaz</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$family_niyaz</td><td>&nbsp;</td>
//     //         <th style='font-size: 12px'>Fateha</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$fateha_hub</td><td>&nbsp;</td>
//     //     </tr>
//     //     <tr>            
//     //         <th style='font-size: 12px' colspan=3>&nbsp</th>
//     //         <th style='font-size: 12px'>Khajoor</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$khajoor_hub</td><td>&nbsp;</td>
//     //     </tr>        
//     //     <tr>               
//     //         <th style='font-size: 12px'>Pirsa</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$pirsa_hub</td><td style='font-size: 12px'>$pirsa_selection</td>
//     //         <th style='font-size: 12px'>Chair</th><td style='font-size: 12px'><i class='mdi mdi-currency-inr'></i>$chair_hub</td><td style='font-size: 12px'>$chair_count</td>
//     //     </tr>  
//     //     </table>      
//     // ";
// }

// function __show_its_and_name($row, $index)
// {
//     $its = $row->its_id;
//     $name = $row->full_name;
//     $index = ((int) $index) + 1;
//     return "$index. [$its] $name";
// }

// function __show_gender_and_age($row, $index)
// {
//     $age = $row->age;
//     $gender = $row->gender;
//     $gender = substr($gender, 0, 1);

//     return "$gender/$age";
// }