<?php



    protected function save_patient_insurance($id, $data)
    {
        $sql = "UPDATE `apps_formitive_insurance` SET
                `bill_myself` 				= '". $data['bill_myself'] ."',
                `bill_spouse` 				= '". $data['bill_spouse'] ."',
                `bill_workers_comp` 			= '". $data['bill_workers_comp'] ."',
                `bill_auto_insurance` 			= '". $data['bill_auto_insurance'] ."',
                `bill_medicare` 			= '". $data['bill_medicare'] ."',
                `bill_medicaid` 			= '". $data['bill_medicaid'] ."',
                `bill_other`				= '". $data['bill_other'] ."',
                `insurance_carrier` 			= '". $data['insurance_carrier'] ."',
                `insurance_holders_name` 		= '". $data['insurance_holders_name'] ."',
                `insurance_holders_birthday`            = '". $data['insurance_holders_birthday'] ."',
                `insurance_card_number` 		= '". $data['insurance_card_number'] ."',
                `insurance_group_number` 		= '". $data['insurance_group_number'] ."',
                `insurance_pcp` 			= '". $data['insurance_pcp'] ."',
                `workers_comp_filed` 			= '". $data['workers_comp_filed'] ."',
                `workers_comp_carrier` 			= '". $data['workers_comp_carrier'] ."',
                `workers_comp_phone` 			= '". $data['workers_comp_phone'] ."',
                `workers_comp_claim_number`             = '". $data['workers_comp_claim_number'] ."',
                `workers_comp_date` 			= '". $data['workers_comp_date'] ."',
                `workers_comp_time` 			= '". $data['workers_comp_time'] ."',
                `workers_comp_am` 			= '". $data['workers_comp_am'] ."',
                `workers_comp_policy_number`            = '". $data['workers_comp_policy_number'] ."',
                `workers_comp_adjuster` 		= '". $data['workers_comp_adjuster'] ."'
                WHERE `id` = '$id' LIMIT 1;";

        return Database::execute($sql);
    }


    Class App_Formitive_Page_Insurance extends App_Formitive_Page implements App_Formitive_Page_Interface
    {
        
        public function html($id)
        {
            
        }
        
        protected function fetch($id)
        {
            
            
        }
        
        public function pdf($id)
        {
            
        }
        
        public function save($id)
        {
            
        }
    }
?>

<form method="POST" action="/intake/final/">
<input type="hidden" name="form-name" value="insurance">

<h2>Insurance Information:</h2>


<table width="100%">
    <tr>
        <th colspan="7">Who is Responsible for Your Bill?  YOU and ....</th>
    </tr>
    <tr>
        <td><input type="checkbox" name="bill_myself" value="ON">Myself ONLY</td>
        <td><input type="checkbox" name="bill_spouse" value="ON"> Spouse</td>
        <td><input type="checkbox" name="bill_workers_comp" value="ON"> Worker's Comp</td>
        <td><input type="checkbox" name="bill_auto_insurance" value="ON"> Auto Insurance</td>
        <td><input type="checkbox" name="bill_medicare" value="ON"> Medicare</td>
        <td><input type="checkbox" name="bill_medicaid" value="ON"> Medicaid</td>
        <td>Other: <input type="text" name="bill_other" size="10"></td>
    </tr>
    <tr>
        <td colspan="4">Personal Health Insurance Carrier:&nbsp;<input type="text" name="insurance_carrier" size="20"></td>
        <td colspan="3">Health ID Card #: <input type="text" name="insurance_card_number" size="25"></td>
    </tr>
    <tr>
        <td colspan="4">Policy Holder's Name: <input type="text" name="insurance_holders_name" size="32"></td>
        <td colspan="3">Group #: <input type="text" name="insurance_group_number" size="33"></td>
    </tr>
    <tr>
        <td colspan="4">Policy Holder's Date of Birth: <input type="text" name="insurance_holders_birthday" id="insurance_holders_birthday" size="20"></td>
        <td colspan="3">Primary Care Physician: <input type="text" name="insurance_pcp" size="20"></td>
    </tr>
</table>

<br />

<table width="90%">
    <tr>
        <th colspan="2">Workers Compensation Injury / Auto Injury / Personal Injury</th>
    </tr>
    <tr>
        <td>Have you filed an injury report with your employer?<select size="1" name="workers_comp_filed"><option>Yes</option><option>No</option><option selected></option></select></td>
        <td>Date: <input type="text" id="workers_comp_date" name="workers_comp_date" size="10"></td>
    </tr>
    <tr>
        <td>Carrier: <input type="text" name="workers_comp_carrier" size="42"></td>
        <td>Time:<input type="text" name="workers_comp_time" size="10"><select size="1" name="workers_comp_am"><option selected>AM</option><option>PM</option></select></td>
    </tr>
    <tr>
        <td>Carriers Phone #: <input type="text" name="workers_comp_phone" size="20"></td>
        <td>Policy #: <input type="text" name="workers_comp_policy_number" size="30"></td>
    </tr>
    <tr>
        <td>Claim #: <input type="text" name="workers_comp_claim_number" size="40"></td>
        <td>Adjuster: <input type="text" name="workers_comp_adjuster" size="36"></td>
    </tr>
</table>

<h4>By hitting 'finish' you are agreeing to the terms laid out in our <a target="_blank" href="http://www.goodback.net/consent/">patient consent form</a></h4>

<input class="submit" type="submit" value="Finish" name="B1">

</form>