<?php

    protected function save_patient_history($id, $data)
    {

        $sql = "UPDATE `apps_formitive_history` SET
            `previous_care` 					= '". $data['previous_care'] ."',
            `previous_care_who`					= '". $data['previous_care_who'] ."',
            `previous_care_treatment`				= '". $data['previous_care_treatment'] ."',
            `previous_care_treatment_beneficial`                = '". $data['previous_care_treatment_beneficial'] ."',
            `previous_care_details`				= '". $data['previous_care_details'] ."',
            `previous_chiro_name` 				= '". $data['previous_chiro_name'] ."',
            `previous_chiro_location` 				= '". $data['previous_chiro_location'] ."',
            `previous_chiro_date`				= '". $data['previous_chiro_date'] ."'
            WHERE `id` = '$id' LIMIT 1;";
        Database::execute($sql);
        unset($sql);

        $sql = "UPDATE `apps_formitive_history` SET
                `medication1_name` 				= '". $data['medication1_name'] ."',
                `medication1_dosage` 				= '". $data['medication1_dosage'] ."',
                `medication1_reason` 				= '". $data['medication1_reason'] ."',
                `medication1_length` 				= '". $data['medication1_length'] ."',
                `medication2_name` 				= '". $data['medication2_name'] ."',
                `medication2_dosage` 				= '". $data['medication2_dosage'] ."',
                `medication2_reason` 				= '". $data['medication2_reason'] ."',
                `medication2_length` 				= '". $data['medication2_length'] ."',
                `medication3_name` 				= '". $data['medication3_name'] ."',
                `medication3_dosage` 				= '". $data['medication3_dosage'] ."',
                `medication3_reason` 				= '". $data['medication3_reason'] ."',
                `medication3_length` 				= '". $data['medication3_length'] ."',
                `medication4_name` 				= '". $data['medication4_name'] ."',
                `medication4_dosage` 				= '". $data['medication4_dosage'] ."',
                `medication4_reason` 				= '". $data['medication4_reason'] ."',
                `medication4_length` 				= '". $data['medication4_length'] ."',
                `medication5_name` 				= '". $data['medication5_name'] ."',
                `medication5_dosage` 				= '". $data['medication5_dosage'] ."',
                `medication5_reason` 				= '". $data['medication5_reason'] ."',
                `medication5_length` 				= '". $data['medication5_length'] ."',
                `medication6_name` 				= '". $data['medication6_name'] ."',
                `medication6_dosage` 				= '". $data['medication6_dosage'] ."',
                `medication6_reason` 				= '". $data['medication6_reason'] ."',
                `medication6_length` 				= '". $data['medication6_length'] ."',
                `medication7_name` 				= '". $data['medication7_name'] ."',
                `medication7_dosage` 				= '". $data['medication7_dosage'] ."',
                `medication7_reason` 				= '". $data['medication7_reason'] ."',
                `medication7_length` 				= '". $data['medication7_length'] ."'
                WHERE `id` = '$id' LIMIT 1;";
        Database::execute($sql);
        unset($sql);

        foreach ($data as $key => $value)
        {
            if ( substr_count( $key, 'childhood_' ) )
            {
                $key = str_replace( 'childhood_', '', $key );
                $key = str_replace( '_', ' ', $key );
                @$childhood .= $key .', ';
            }
        }

        foreach ($data as $key => $value)
        {
            if ( substr_count( $key, 'adult_' ) )
            {
                $key = str_replace( 'adult_', '', $key );
                $key = str_replace( '_', ' ', $key );
                @$adult .= $key. ', ';
            }
        }
        $sql = "UPDATE `apps_formitive_history` SET
                        `childhood_illnesses` 	= '$childhood',
                        `adult_illnesses` 		= '$adult'
                        WHERE `id` = '$id' LIMIT 1;";
        Database::execute($sql);
        unset($sql);

        $sql = "UPDATE `apps_formitive_history` SET
            `surgery1` 					= '". $data['surgery1'] ."',
            `surgery1_date`				= '". $data['surgery1_date'] ."',
            `surgery2` 					= '". $data['surgery2'] ."',
            `surgery2_date`				= '". $data['surgery2_date'] ."',
            `surgery3` 					= '". $data['surgery3'] ."',
            `surgery3_date`				= '". $data['surgery3_date'] ."',
            `surgery4` 					= '". $data['surgery4'] ."',
            `surgery4_date`				= '". $data['surgery4_date'] ."',
            `surgery5` 					= '". $data['surgery5'] ."',
            `surgery5_date`				= '". $data['surgery5_date'] ."',
            `surgery6` 					= '". $data['surgery6'] ."',
            `surgery6_date`				= '". $data['surgery6_date'] ."'
            WHERE `id` = '$id' LIMIT 1;";
        Database::execute( $sql );
        unset($sql);

        $sql = "UPDATE `apps_formitive_history` SET
            `injury1`                                       = '". $data['injury1'] ."',
            `injury1_date`                                  = '". $data['injury1_date'] ."',
            `injury2`                                       = '". $data['injury2'] ."',
            `injury2_date`                                  = '". $data['injury2_date'] ."',
            `injury3`                                       = '". $data['injury3'] ."',
            `injury3_date`                                  = '". $data['injury3_date'] ."',
            `injury4`                                       = '". $data['injury4'] ."',
            `injury4_date`                                  = '". $data['injury4_date'] ."',
            `injury5`                                       = '". $data['injury5'] ."',
            `injury5_date`                                  = '". $data['injury5_date'] ."',
            `injury6`                                       = '". $data['injury6'] ."',
            `injury6_date`                                  = '". $data['injury6_date'] ."'
            WHERE `id` = '$id' LIMIT 1;";
        Database::execute( $sql );
        unset($sql);

        $sql = "UPDATE `apps_formitive_history` SET
            `history_family_status`                                 = '". $data['history_family_status'] ."',
            `history_family_normally_developed`                     = '". $data['history_family_normally_developed'] ."',
            `history_father_no_significant_disease`                 = '". $data['history_family_no_significant_disease'] ."',
            `history_family_other`                                  = '". $data['history_family_other'] ."',
            `history_father_status`                                 = '". $data['history_father_status'] ."',
            `history_father_normally_developed`                     = '". $data['history_father_normally_developed'] ."',
            `history_father_no_significant_disease`                 = '". $data['history_father_no_significant_disease'] ."',
            `history_father_other`                                  = '". $data['history_father_other'] ."',
            `history_mother_status`                                 = '". $data['history_mother_status'] ."',
            `history_mother_normally_developed`                     = '". $data['history_mother_normally_developed'] ."',
            `history_mother_no_significant_disease`                 = '". $data['history_mother_no_significant_disease'] ."',
            `history_mother_other`                                  = '". $data['history_mother_other'] ."',
            `history_paternal_grandfather_status`                   = '". $data['history_paternal_grandfather_status'] ."',
            `history_paternal_grandfather_normally_developed`       = '". $data['history_paternal_grandfather_normally_developed'] ."',
            `history_paternal_grandfather_no_significant_disease`   = '". $data['history_paternal_grandfather_no_significant_disease'] ."',
            `history_paternal_grandfather_other`                    = '". $data['history_paternal_grandfather_other'] ."',
            `history_paternal_grandmother_status`                   = '". $data['history_paternal_grandmother_status'] ."',
            `history_paternal_grandmother_normally_developed`       = '". $data['history_paternal_grandmother_normally_developed'] ."',
            `history_paternal_grandmother_no_significant_disease`   = '". $data['history_paternal_grandmother_no_significant_disease'] ."',
            `history_paternal_grandmother_other`                    = '". $data['history_paternal_grandmother_other'] ."',
            `history_maternal_grandfather_status`                   = '". $data['history_maternal_grandfather_status'] ."',
            `history_maternal_grandfather_normally_developed`       = '". $data['history_maternal_grandfather_normally_developed'] ."',
            `history_maternal_grandfather_no_significant_disease`   = '". $data['history_maternal_grandfather_no_significant_disease'] ."',
            `history_maternal_grandfather_other`                    = '". $data['history_maternal_grandfather_other'] ."',
            `history_maternal_grandmother_status`                   = '". $data['history_maternal_grandmother_status'] ."',
            `history_maternal_grandmother_normally_developed`       = '". $data['history_maternal_grandmother_normally_developed'] ."',
            `history_maternal_grandmother_no_significant_disease`   = '". $data['history_maternal_grandmother_no_significant_disease'] ."',
            `history_maternal_grandmother_other`                    = '". $data['history_maternal_grandmother_other'] ."',
            `history_sons_status`                                   = '". $data['history_sons_status'] ."',
            `history_sons_normally_developed`                       = '". $data['history_sons_normally_developed'] ."',
            `history_sons_no_significant_disease`                   = '". $data['history_sons_no_significant_disease'] ."',
            `history_sons_other`                                    = '". $data['history_sons_other'] ."',
            `history_daughters_status`                              = '". $data['history_daughters_status'] ."',
            `history_daughters_normally_developed`                  = '". $data['history_daughters_normally_developed'] ."',
            `history_daughters_no_significant_disease`              = '". $data['history_daughters_no_significant_disease'] ."',
            `history_daughters_other`                               = '". $data['history_daughters_other'] ."',
            `history_brothers_status`                               = '". $data['history_brothers_status'] ."',
            `history_brothers_normally_developed`                   = '". $data['history_brothers_normally_developed'] ."',
            `history_brothers_no_significant_disease`               = '". $data['history_brothers_no_significant_disease'] ."',
            `history_brothers_other`                                = '". $data['history_brothers_other'] ."',
            `history_sisters_status`                                = '". $data['history_sisters_status'] ."',
            `history_sisters_normally_developed`                    = '". $data['history_sisters_normally_developed'] ."',
            `history_sisters_no_significant_disease`                = '". $data['history_sisters_no_significant_disease'] ."',
            `history_sisters_other`                                 = '". $data['history_sisters_other'] ."'
            WHERE `id` = '$id' LIMIT 1;";
        Database::execute($sql);
        unset($sql);
    }
    
?>

<form method="POST" action="/intake/insurance/">
<input type="hidden" name="form-name" value="history">
	<center><h3>PAST HEALTH HISTORY</h3>
	Fill out carefully as these problems can affect your overall course of care.</center><br /><br />

        <table>
           <tr>
                <td>
                    <b>Previous Care for this Same Condition</b>
                </td>
                <td align="right">
                    <input type="checkbox" name="previous_dr_not_seen" value="ON"> I have not previously seen a doctor for this condition
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Have you seen other doctors for THIS CONDITION?
                    <select size="1" name="previous_care">
                        <option selected></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>&nbsp;&nbsp;&nbsp;&nbsp;
                    If so, who (name)? <input type="text" name="previous_care_who" size="20">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Type of Treatment: <input type="text" name="previous_care_treatment" size="20">&nbsp;&nbsp;Was the treatment beneficial in resolving condition?
                    <select size="1" name="previous_care_treatment_beneficial">
                    <option selected></option>
                    <option>Yes</option>
                    <option>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Explain: <input type="text" name="previous_care_details" size="48">
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td>
                    <b>Previous Chiropractic Care</b>
                </td>
                <td align="right">
                    <input type="checkbox" name="previous_chiro_treated" value="ON">
                    I have not previously seen a chiropractor
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Doctor's Name: <input type="text" name="previous_chiro_name" size="20">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Location: <input type="text" name="previous_chiro_location" size="16">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Date of Last Visit: <input type="text" id="prevvisit" name="previous_chiro_date" id="lastvisit" size="10">
                </td>
            </tr>
        </table>

		
		<table width="100%">
		<tr>
			<td colspan="4"><p align="center"><b>Current Medication(s):&nbsp;&nbsp;&nbsp;&nbsp;List ANY/ALL medications you are CURRENTLY taking.  Be Specific.</b></p></td>
		</tr>		
		<tr>
			<td><p align="center">Medication</p></td>
			<td><p align="center">Dosage</p></td>
			<td><p align="center">For what condition?</p></td>
			<td><p align="center">How long have you been taking this?</p></td>
		</tr>
		<tr>
			<td><input type="text" name="medication1_name" size="20"></td>
			<td><input type="text" name="medication1_dosage" size="10"></td>
			<td><input type="text" name="medication1_reason" size="30"></td>
			<td><input type="text" name="medication1_length" size="44"></td>
		</tr>
		<tr>
			<td><input type="text" name="medication2_name" size="20"></td>
			<td><input type="text" name="medication2_dosage" size="10"></td>
			<td><input type="text" name="medication2_reason" size="30"></td>
			<td><input type="text" name="medication2_length" size="44"></td>
		</tr>
		<tr>
			<td><input type="text" name="medication3_name" size="20"></td>
			<td><input type="text" name="medication3_dosage" size="10"></td>
			<td><input type="text" name="medication3_reason" size="30"></td>
			<td><input type="text" name="medication3_length" size="44"></td>
		</tr>
		<tr>
			<td><input type="text" name="medication4_name" size="20"></td>
			<td><input type="text" name="medication4_dosage" size="10"></td>
			<td><input type="text" name="medication4_reason" size="30"></td>
			<td><input type="text" name="medication4_length" size="44"></td>
		</tr>
		<tr>
			<td><input type="text" name="medication5_name" size="20"></td>
			<td><input type="text" name="medication5_dosage" size="10"></td>
			<td><input type="text" name="medication5_reason" size="30"></td>
			<td><input type="text" name="medication5_length" size="44"></td>
		</tr>
		<tr>
			<td><input type="text" name="medication6_name" size="20"></td>
			<td><input type="text" name="medication6_dosage" size="10"></td>
			<td><input type="text" name="medication6_reason" size="30"></td>
			<td><input type="text" name="medication6_length" size="44"></td>
		</tr>
		<tr>
			<td><input type="text" name="medication7_name" size="20"></td>
			<td><input type="text" name="medication7_dosage" size="10"></td>
			<td><input type="text" name="medication7_reason" size="30"></td>
			<td><input type="text" name="medication7_length" size="44"></td>
		</tr>
	</table><br>

	<br />
	
	<table width="100%">
		<tr>
			<td colspan="4"><b>Childhood Illness(es): List all health conditions</b></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="childhood_ADD" value="ON">&nbsp;ADD</td>
			<td><input type="checkbox" name="childhood_chicken_pox" value="ON">&nbsp;chicken pox</td>
			<td><input type="checkbox" name="childhood_headaches" value="ON">&nbsp;headaches</td>
			<td><input type="checkbox" name="childhood_scoliosis" value="ON">&nbsp;scoliosis</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="childhood_eczema" value="ON">&nbsp;atopic dermatitis (eczema)</td>
			<td><input type="checkbox" name="childhood_crohns" value="ON">&nbsp;Crohn's/colitis</td>
			<td><input type="checkbox" name="childhood_hepatitis" value="ON">&nbsp;hepatitis</td>
			<td><input type="checkbox" name="childhood_seizure_disorder" value="ON">&nbsp;seizure disorder</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="childhood_allergies" value="ON">&nbsp;allergies/hay fever</td>
			<td><input type="checkbox" name="childhood_depression" value="ON">&nbsp;depression</td>
			<td><input type="checkbox" name="childhood_HIV" value="ON">&nbsp;HIV</td>
			<td><input type="checkbox" name="childhood_sickle_cell_anemia" value="ON">&nbsp;sicke cell anemia</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="childhood_anemia" value="ON">&nbsp;anemia</td>
			<td><input type="checkbox" name="childhood_diabetes" value="ON">&nbsp;diabetes</td>
			<td><input type="checkbox" name="childhood_measles" value="ON">&nbsp;measles</td>
			<td><input type="checkbox" name="childhood_spina bifida" value="ON">&nbsp;spina bifida</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="childhood_asthma" value="ON">&nbsp;asthma</td>
			<td><input type="checkbox" name="childhood_ear_infection" value="ON">&nbsp;ear infection</td>
			<td><input type="checkbox" name="childhood_mumps" value="ON">&nbsp;mumps</td>
			<td rowspan="2">Other:<br><input type="text" name="childhood_other" size="30"></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="childhood_bedwetting" value="ON">&nbsp;bedwetting</td>
			<td><input type="checkbox" name="childhood_fetal_drug_exposure" value="ON">&nbsp;fetal drug exposure</td>
			<td><input type="checkbox" name="childhood_psoriasis" value="ON">&nbsp;psoriasis</td>
			
		</tr>
		<tr>
			<td><input type="checkbox" name="childhood_cerebral_palsy" value="ON">&nbsp;cerebral palsy</td>
			<td><input type="checkbox" name="childhood_food_allergies" value="ON">&nbsp;food allergies</td>
			<td><input type="checkbox" name="childhood_rash" value="ON">&nbsp;rash</td>
		</tr>
	</table>
	
<br />

	<table width="100%">
		<tr>
			<td colspan="4"><b>Adult Illness(es): LIST all current health conditions</b></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_ADD" value="ON">&nbsp;ADD</td>
			<td><input type="checkbox" name="adult_cystic_kidney_disease" value="ON">&nbsp;cystic kidney disease</td>
			<td><input type="checkbox" name="adult_hypertension" value="ON">&nbsp;hypertension</td>
			<td><input type="checkbox" name="adult_psychiatric_problems" value="ON">&nbsp;psychiatric problems</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_Alzheimers" value="ON">&nbsp;Alzheimer's</td>
			<td><input type="checkbox" name="adult_depression" value="ON">&nbsp;Depression</td>
			<td><input type="checkbox" name="adult_influenza_pneumonia" value="ON">&nbsp;influenza pneumonia</td>
			<td><input type="checkbox" name="adult_scoliosis" value="ON">&nbsp;scoliosis</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_anemia" value="ON">&nbsp;anemia</td>
			<td><input type="checkbox" name="adult_diabetes_(insulin_dep)" value="ON">&nbsp;diabetes (insulin dep)</td>
			<td><input type="checkbox" name="adult_liver_disease" value="ON">&nbsp;liver disease</td>
			<td><input type="checkbox" name="adult_seizures" value="ON">&nbsp;seizures</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_arthritis" value="ON">&nbsp;arthritis</td>
			<td><input type="checkbox" name="adult_diabetes_(non_insulin)" value="ON">&nbsp;diabetes (non insulin)</td>
			<td><input type="checkbox" name="adult_lung_disease" value="ON">&nbsp;lung disease</td>
			<td><input type="checkbox" name="adult_shingles" value="ON">&nbsp;shingles</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_asthma" value="ON">&nbsp;asthma</td>
			<td><input type="checkbox" name="adult_eczema" value="ON">&nbsp;eczema</td>
			<td><input type="checkbox" name="adult_lupus_erythema" value="ON">&nbsp;lupus erythema (discoid)</td>
			<td><input type="checkbox" name="adult_history_of_similar_symptoms" value="ON">&nbsp;past history of similar symptoms</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_cancer" value="ON">&nbsp;cancer</td>
			<td><input type="checkbox" name="adult_emphysema" value="ON">&nbsp;emphysema</td>
			<td><input type="checkbox" name="adult_lupus_erythema" value="ON">&nbsp;lupus erythema (systemic)</td>
			<td><input type="checkbox" name="adult_STDs_(unspecified)" value="ON">&nbsp;STD's (unspecified)</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_cerebral_palsy" value="ON">&nbsp;cerebral palsy</td>
			<td><input type="checkbox" name="adult_eye_problems" value="ON">&nbsp;eye problems</td>
			<td><input type="checkbox" name="adult_multiple_scerosis" value="ON">&nbsp;multiple sclerosis</td>
			<td><input type="checkbox" name="adult_suicide_attempt(s)" value="ON">&nbsp;suicide attempt(s)</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_chicken_pox" value="ON">&nbsp;chicken pox</td>
			<td><input type="checkbox" name="adult_fibromyalgia" value="ON">&nbsp;fibromyalgia</td>
			<td><input type="checkbox" name="adult_Parkinsons_disease" value="ON">&nbsp;Parkinson's disease</td>
			<td><input type="checkbox" name="adult_thyroid_problems" value="ON">&nbsp;thyroid problems</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_Chrons" value="ON">&nbsp;Crohn's/colitis</td>
			<td><input type="checkbox" name="adult_heart_disease" value="ON">&nbsp;heart disease</td>
			<td><input type="checkbox" name="adult_unspecified_pleural" value="ON">&nbsp;unspecified pleural effusion</td>
			<td><input type="checkbox" name="adult_vertigo" value="ON">&nbsp;vertigo</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_CRPS_(RSD)" value="ON">&nbsp;CRPS (RSD)</td>
			<td><input type="checkbox" name="adult_hepatitis" value="ON">&nbsp;hepatitis</td>
			<td><input type="checkbox" name="adult_pneumonia" value="ON">&nbsp;pneumonia</td>
			<td rowspan="2">Other:<br><input type="text" name="adult_other" size="30"></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="adult_CVA_(stroke)" value="ON">&nbsp;CVA (stroke)</td>
			<td><input type="checkbox" name="adult_HIV" value="ON">&nbsp;HIV</td>
			<td><input type="checkbox" name="adult_psoriasis" value="ON">&nbsp;psoriasis</td>
		</tr>
	</table>

	<br />

	<table width="75%">
            <tr>
                <td>
                    Surgery(ies): LIST All Surgical Procedures
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="surgery1">
                    <option selected></option>
                    <option>angioplasty</option>
                    <option>appendectomy</option>
                    <option>caesarian section</option>
                    <option>cardiac catheterization</option>
                    <option>carpal tunnel repair</option>
                    <option>coronary artery bypass</option>
                    <option>cosmetic</option>
                    <option>D & C</option>
                    <option>dental surgery</option>
                    <option>gall bladder</option>
                    <option>hemorrhoidectomy</option>
                    <option>hernia repair</option>
                    <option>hysterectomy</option>
                    <option>joint reconstruction</option>
                    <option>joint replacement</option>
                    <option>knee repair</option>
                    <option>laminectomy</option>
                    <option>mastectomy</option>
                    <option>pacemaker insertion</option>
                    <option>rotator cuff</option>
                    <option>spinal fusion</option>
                    <option>tonsillectomy</option>
                    </select>
                    Date : <input type="text" id="surgery1date" name="surgery1_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="surgery2">
                    <option selected></option>
                    <option>angioplasty</option>
                    <option>appendectomy</option>
                    <option>caesarian section</option>
                    <option>cardiac catheterization</option>
                    <option>carpal tunnel repair</option>
                    <option>coronary artery bypass</option>
                    <option>cosmetic</option>
                    <option>D & C</option>
                    <option>dental surgery</option>
                    <option>gall bladder</option>
                    <option>hemorrhoidectomy</option>
                    <option>hernia repair</option>
                    <option>hysterectomy</option>
                    <option>joint reconstruction</option>
                    <option>joint replacement</option>
                    <option>knee repair</option>
                    <option>laminectomy</option>
                    <option>mastectomy</option>
                    <option>pacemaker insertion</option>
                    <option>rotator cuff</option>
                    <option>spinal fusion</option>
                    <option>tonsillectomy</option>
                    </select>
                    Date: <input type="text" id="surgery2date" name="surgery2_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="surgery3">
                    <option selected></option>
                    <option>angioplasty</option>
                    <option>appendectomy</option>
                    <option>caesarian section</option>
                    <option>cardiac catheterization</option>
                    <option>carpal tunnel repair</option>
                    <option>coronary artery bypass</option>
                    <option>cosmetic</option>
                    <option>D & C</option>
                    <option>dental surgery</option>
                    <option>gall bladder</option>
                    <option>hemorrhoidectomy</option>
                    <option>hernia repair</option>
                    <option>hysterectomy</option>
                    <option>joint reconstruction</option>
                    <option>joint replacement</option>
                    <option>knee repair</option>
                    <option>laminectomy</option>
                    <option>mastectomy</option>
                    <option>pacemaker insertion</option>
                    <option>rotator cuff</option>
                    <option>spinal fusion</option>
                    <option>tonsillectomy</option>
                    </select>
                    Date: <input type="text" id="surgery3date" name="surgery3_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="surgery4">
                    <option selected></option>
                    <option>angioplasty</option>
                    <option>appendectomy</option>
                    <option>caesarian section</option>
                    <option>cardiac catheterization</option>
                    <option>carpal tunnel repair</option>
                    <option>coronary artery bypass</option>
                    <option>cosmetic</option>
                    <option>D & C</option>
                    <option>dental surgery</option>
                    <option>gall bladder</option>
                    <option>hemorrhoidectomy</option>
                    <option>hernia repair</option>
                    <option>hysterectomy</option>
                    <option>joint reconstruction</option>
                    <option>joint replacement</option>
                    <option>knee repair</option>
                    <option>laminectomy</option>
                    <option>mastectomy</option>
                    <option>pacemaker insertion</option>
                    <option>rotator cuff</option>
                    <option>spinal fusion</option>
                    <option>tonsillectomy</option>
                    </select>
                    Date: <input type="text" id="surgery4date" name="surgery4_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="surgery5">
                    <option selected></option>
                    <option>angioplasty</option>
                    <option>appendectomy</option>
                    <option>caesarian section</option>
                    <option>cardiac catheterization</option>
                    <option>carpal tunnel repair</option>
                    <option>coronary artery bypass</option>
                    <option>cosmetic</option>
                    <option>D & C</option>
                    <option>dental surgery</option>
                    <option>gall bladder</option>
                    <option>hemorrhoidectomy</option>
                    <option>hernia repair</option>
                    <option>hysterectomy</option>
                    <option>joint reconstruction</option>
                    <option>joint replacement</option>
                    <option>knee repair</option>
                    <option>laminectomy</option>
                    <option>mastectomy</option>
                    <option>pacemaker insertion</option>
                    <option>rotator cuff</option>
                    <option>spinal fusion</option>
                    <option>tonsillectomy</option>
                    </select>
                    Date: <input type="text" id="surgery5date" name="surgery5_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    Other: <input type="text" name="surgery6" size="30"> Date:
                    <input type="text" id="surgery6date" name="surgery6_date" size="12">
                </td>
            </tr>
        </table>
	
	<table width="75%">
            <tr>
                <td>
                    Injury(ies): List ALL Injuries
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="injury1">
                    <option selected></option>
                    <option>back injury</option>
                    <option>broken bones</option>
                    <option>disability(ies)</option>
                    <option>fall (severe)</option>
                    <option>facture</option>
                    <option>head injury (loss of consciousness)</option>
                    <option>head injury (noloss of consciousness)</option>
                    <option>industrial accident</option>
                    <option>joint injury</option>
                    <option>laceration (severe)</option>
                    <option>motor vehicle accident</option>
                    <option>soft tissue injury (mild)</option>
                    <option>soft tissue injury (moderate)</option>
                    <option>soft tissue injury (severe)</option>
                    </select>
                    Date : <input type="text" id="injury1date" name="injury1_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="injury2">
                    <option selected></option>
                    <option>back injury</option>
                    <option>broken bones</option>
                    <option>disability(ies)</option>
                    <option>fall (severe)</option>
                    <option>facture</option>
                    <option>head injury (loss of consciousness)</option>
                    <option>head injury (noloss of consciousness)</option>
                    <option>industrial accident</option>
                    <option>joint injury</option>
                    <option>laceration (severe)</option>
                    <option>motor vehicle accident</option>
                    <option>soft tissue injury (mild)</option>
                    <option>soft tissue injury (moderate)</option>
                    <option>soft tissue injury (severe)</option>
                    </select>
                    Date : <input type="text" id="injury2date" name="injury2_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="injury3">
                    <option selected></option>
                    <option>back injury</option>
                    <option>broken bones</option>
                    <option>disability(ies)</option>
                    <option>fall (severe)</option>
                    <option>facture</option>
                    <option>head injury (loss of consciousness)</option>
                    <option>head injury (noloss of consciousness)</option>
                    <option>industrial accident</option>
                    <option>joint injury</option>
                    <option>laceration (severe)</option>
                    <option>motor vehicle accident</option>
                    <option>soft tissue injury (mild)</option>
                    <option>soft tissue injury (moderate)</option>
                    <option>soft tissue injury (severe)</option>
                    </select>
                    Date : <input type="text"  id="injury3date" name="injury3_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="injury4">
                    <option selected></option>
                    <option>back injury</option>
                    <option>broken bones</option>
                    <option>disability(ies)</option>
                    <option>fall (severe)</option>
                    <option>facture</option>
                    <option>head injury (loss of consciousness)</option>
                    <option>head injury (noloss of consciousness)</option>
                    <option>industrial accident</option>
                    <option>joint injury</option>
                    <option>laceration (severe)</option>
                    <option>motor vehicle accident</option>
                    <option>soft tissue injury (mild)</option>
                    <option>soft tissue injury (moderate)</option>
                    <option>soft tissue injury (severe)</option>
                    </select>
                    Date : <input type="text"  id="injury4date" name="injury4_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    <select size="1" name="injury5">
                    <option selected></option>
                    <option>back injury</option>
                    <option>broken bones</option>
                    <option>disability(ies)</option>
                    <option>fall (severe)</option>
                    <option>facture</option>
                    <option>head injury (loss of consciousness)</option>
                    <option>head injury (noloss of consciousness)</option>
                    <option>industrial accident</option>
                    <option>joint injury</option>
                    <option>laceration (severe)</option>
                    <option>motor vehicle accident</option>
                    <option>soft tissue injury (mild)</option>
                    <option>soft tissue injury (moderate)</option>
                    <option>soft tissue injury (severe)</option>
                    </select>
                    Date : <input type="text" id="injury5date" name="injury5_date" size="12">
                </td>
            </tr>
            <tr>
                <td>
                    Other: <input type="text" name="injury6" size="30"> Date:
                    <input type="text" id="injury6date" name="injury6_date" size="12">
                </td>
            </tr>
        </table>

        
    <table>
        <tr>
            <td colspan="5"><b>Family History: Mark all the apply.  List any specific conditions past or present after has/had</b></td>
        </tr>
        <tr>
            <td width="100px">General Family</td>
            <td><select size="1" name="history_family_status" style="width:80px"><option></option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_family_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_family_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_family_other" size="20"></td>
        </tr>
        <tr>
            <td>Father</td>
            <td><select size="1" name="history_father_status" style="width:80px"><option></option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_father_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_father_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_father_other" size="20"></td>
        </tr>
        <tr>
            <td>Mother</td>
            <td><select size="1" name="history_mother_status" style="width:80px"><option></option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_mother_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_mother_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_mother_other" size="20"></td>
        </tr>
        <tr>
            <td>Paternal Grandfather</td>
            <td><select size="1" name="history_paternal_grandfather_status" style="width:80px"><option></option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_paternal_grandfather_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_paternal_grandfather_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_paternal_grandfather_other" size="20"></td>
        </tr>
        <tr>
            <td>Paternal Grandmother</td>
            <td><select size="1" name="history_paternal_grandmother_status" style="width:80px"><option></option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_paternal_grandmother_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_paternal_grandmother_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_paternal_grandmother_other" size="20"></td>
        </tr>
        <tr>
            <td>Maternal Grandfather</td>
            <td><select size="1" name="history_maternal_grandfather_status" style="width:80px"><option></option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_maternal_grandfather_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_maternal_grandfather_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_maternal_grandfather_other" size="20"></td>
        </tr>
        <tr>
            <td>Maternal Grandmother</td>
            <td><select size="1" name="history_maternal_grandmother_status" style="width:80px"><option></option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_maternal_grandmother_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_maternal_grandmother_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_maternal_grandmother_other" size="20"></td>
        </tr>
        <tr>
            <td>Son(s)</td>
            <td><select size="1" name="history_sons_status" style="width:80px"><option>none</option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_sons_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_sons_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_sons_other" size="20"></td>
        </tr>
        <tr>
            <td>Daughter(s)</td>
            <td><select size="1" name="history_daughters_status" style="width:80px"><option>none</option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_daughters_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_daughters_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_daughters_other" size="20"></td>
        </tr>
        <tr>
            <td>Brother(s)</td>
            <td><select size="1" name="history_brothers_status" style="width:80px"><option>none</option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_brothers_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_brothers_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_brothers_other" size="20"></td>
        </tr>
        <tr>
            <td>Sister(s)</td>
            <td><select size="1" name="history_sisters_status" style="width:80px"><option>none</option><option>alive</option><option>deceased</option></select></td>
            <td>Normally Developed: <select size="1" name="history_sisters_normally_developed" style="width:60px"><option>yes</option><option>no</option></select></td>
            <td><input type="checkbox" name="history_sisters_no_significant_disease" value="ON">&nbsp;No significant disease</td>
            <td>has/had: <input type="text" name="history_sisters_other" size="20"></td>
        </tr>
    </table>
		
	<br />
	
	
	<br>
	<table border="0" width="80%" cellspacing="0" cellpadding="0">
		<tr>
			<td><p align="right"><input class="submit" type="submit" value="Continue &gt;&gt;" name="B1">
			</td>
		</tr>
	</table>
	</form>

<?php
unset($result);
?>