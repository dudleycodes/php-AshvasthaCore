<?php

class App_Formitive_Page_HealthHistory extends App_Formitive_Page
{
    protected $pagename = 'HealthHistory';
    protected $tablename = 'app_formitive_healthhistory';
    
    protected function __define_fields()
    {
        //previous chiropractic care
        
        $this->fields['medications'] = new App_Formitive_Input_Combo('medications', $this->dbRow, 'Current Medications');
        
        $this->fields['childhood_illnesses'] = new App_Formitive_Input_Checkboxes('childhood_illnesses', $this->dbRow, 'Childhood Illnesses');
            $this->fields['childhood_illnesses']->options_add(array('ADD', 'Allergies/Hay Fever', 'Anemia', 'Asthma', 'Bedwetting', 'Cerebral Palsy', 
                                                                    'Chicken Pox', "Crohn's Colitis", 'Depression', 'Diabetes', 'Ear Infection', 
                                                                    'Eczema (Atopic Dermatitis)', 'Fetal Drug Exposure', 'Food Allergies', 'Headaches', 
                                                                    'Hepatitis', 'HIV', 'Psoriasis', 'Measles', 'Mumps', 'Rash', 'Scoliosis', 
                                                                    'Seizure Disorder','Sickel Cell Anemia','Spina Bifida' ));
        
        $this->fields['adult_illnesses'] = new App_Formitive_Input_Checkboxes('adult_illnesses', $this->dbRow, 'Adult Illnesses');
            $this->fields['childhood_illnesses']->options_add(array('ADD', "Alzheimer's", 'Anemia', 'Arthritis', 'Asthma', 'Cancer', 'Cerebral Palsy', 
                                                                    'Chicken Pox', "Crohn's/Colitis", 'CRPS (RSD)', 'CVA (Stroke)', 'Cystic Kidney Disease', 
                                                                    'Depression', 'Diabetes (Insulin Dep)', 'Diabetes (Non-Insulin)', 'Emphysema', 
                                                                    'Eye Problems', 'Fibromyalgia', 'Heart Disease', 'Hepatitis', 'HIV', 'Hyper-Tension', 
                                                                    'Influenza Pneumonia', 'Liver Disease', 'Lung Disease', 'Lupus Erythema (Systemic)', 
                                                                    'Lupus Erythema (Discoid)', 'Multiple Sclerosis', "Parkinson's Disease", 'Pneumonia', 
                                                                    'Psoriasis', 'Psychiatric Problems', 'Scoliosis', 'Seizures', 'Shingles', 
                                                                    'Suicide Attempt(s)', "STD's (Unspecified)", 'Thyroid Problems', 'Unspecified Pleural Effusion', 
                                                                    'Vertigo' ));

        $this->fields['surgeries'] = new App_Formitive_Input_Surgeries('surgeries', $this->dbRow, 'Surgeries');
        $this->fields['injuries'] = new App_Formitive_Input_Injuries('injuries', $this->dbRow, 'Injuries');
        
        $this->fields['pedigree_family'] = new App_Formitive_Input_Pedigree('pedigree_family', $this->dbRow, 'General Family');
        $this->fields['pedigree_father'] = new App_Formitive_Input_Pedigree('pedigree_father', $this->dbRow, 'Father');
        $this->fields['pedigree_mother'] = new App_Formitive_Input_Pedigree('pedigree_mother', $this->dbRow, 'Mother');
        $this->fields['pedigree_paternal_grandfather'] = new App_Formitive_Input_Pedigree('pedigree_paternal_grandfather', $this->dbRow, 'Paternal Grandfather');
        $this->fields['pedigree_paternal_grandmother'] = new App_Formitive_Input_Pedigree('pedigree_paternal_grandmother', $this->dbRow, 'Paternal Grandmother');
        $this->fields['pedigree_maternal_grandfather'] = new App_Formitive_Input_Pedigree('pedigree_maternal_grandfather', $this->dbRow, 'Maternal Grandfather');
        $this->fields['pedigree_maternal_grandmother'] = new App_Formitive_Input_Pedigree('pedigree_maternal_grandmother', $this->dbRow, 'Maternal Grandmother');
        $this->fields['pedigree_sons'] = new App_Formitive_Input_Pedigree('pedigree_sons', $this->dbRow, 'Sons');
        $this->fields['pedigree_daughters'] = new App_Formitive_Input_Pedigree('pedigree_daughters', $this->dbRow, 'Daughters');
        $this->fields['pedigree_brothers'] = new App_Formitive_Input_Pedigree('pedigree_brothers', $this->dbRow, 'Brothers');
        $this->fields['pedigree_sisters'] = new App_Formitive_Input_Pedigree('pedigree_sisters', $this->dbRow, 'Sisters');
        
    }
}

?>