<?php

class App_Formitive_Page_SystemsReview extends App_Formitive_Page
{
    protected $pagename = 'SystemsReview';
    protected $tablename = 'app_formitive_systemsreview';
    
    protected function __define_fields()
    {
        $this->fields[] = 'Review of Systems';
        
        
        $this->fields['constitutionals'] = new App_Formitive_Input_Checkboxes('constitutionals', $this->dbRow, 'Constitutional');
            $this->fields['constitutionals']->options_add(array('Chills', 'Daytime Drowsiness', 'Fatigue', 'Fever', 'Night Sweats', 'Weight Gain', 'Weight Loss'));
            
            
        $this->fields['vision'] = new App_Formitive_Input_Checkboxes('vision', $this->dbRow, 'Eyes/Vision');
            $this->fields['vision']->options_add(array('Blindness', 'Blurred Vision', 'Cataracts', 'Change in Vision', 'Double Vision', 'Eye Pain', 'Field Cuts', 
                                                        'Glasses or Contracts', 'Glaucoma', 'Itching', 'Photophobia', 'Tearing'));
            
            
        $this->fields['ent'] = new App_Formitive_Input_Checkboxes('ent', $this->dbRow, 'Ears, Nose and Throat');
            $this->fields['ent']->options_add(array('Bleeding', 'Congestion', 'Dentures', 'Difficulty Swallowing', 'Discharge', 'Dizziness', 'Drainage', 'Ear Pain', 
                                                    'Fainting', 'Headaches', 'Hearing Loss', 'History of Head Injury', 'History of Sore Throats', 'Hoarseness', 
                                                    'Loss of Sense of Smell', 'Nosebleeds', 'Postnasal Drip', 'Rhinorrhea', 'Sinsus Infections', 'Snoring', 'Sore Throat', 
                                                    'Tinnitus', 'TMJ Problems'));
            
            
        $this->fields['respiration'] = new App_Formitive_Input_Checkboxes('respiration', $this->dbRow, 'Respiration');
            $this->fields['respiration']->options_add(array('Asthma', 'Coughing', 'Coughing Up Blood', 'Shortness of Breath', 'Sputum Productions', 'Wheezing'));
            
            
        $this->fields['cardio'] = new App_Formitive_Input_Checkboxes('cardio', $this->dbRow, 'Cardiovascular');
            $this->fields['cardio']->options_add(array('Angina', 'Chest Pain', 'Claudication', 'Heart Murmur', 'Heart Probnlems', 'High Blood Pressure', 'Low Blood Pressure', 
                                                        'Orthopnea', 'Palpitations', 'Paroxysmal Nocturnal Dyspnea', 'Shortness of Breath with Exertion', 'Swelling of Legs', 
                                                        'Ulcers', 'Varicose Veins'));
            
        $this->fields['gastrointestinal'] = new App_Formitive_Input_Checkboxes('gastrointestinal', $this->dbRow, 'Gastrointestinal');
            $this->fields['gastrointestinal']->options_add(array('Abdominal Pain', 'Abnormal Stool Caliber', 'Abnormal Stool Color', 'Abnormal Stool Consistency', 'Belching', 
                                                                'Constipation', 'Diarrhea', 'Difficulty Swallowing', 'Heartburn', 'Hemorrhoids', 'Indigestion', 'Jaundice', 
                                                                'Nausea', 'Rectal Bleeding', 'Tarry Stools', 'Vomiting', 'Vominting Blood'));
            
        $this->fields['female'] = new App_Formitive_Input_Checkboxes('female', $this->dbRow, 'Female Specific');
            $this->fields['female']->options_add(array('Birth Control', 'Breat Lumps/Pain', 'Burning Urination', 'Cramps', 'Frequent Urination', 'Irregular Menstruation', 
                                                        'Hormone Therapy', 'Pregnancy', 'Urine Retention', 'Vaginal Bleeding', 'Vaginal Discharge'));
        
            
        $this->fields['male'] = new App_Formitive_Input_Checkboxes('male', $this->dbRow, 'Male Specific');
            $this->fields['male']->options_add(array('Burning Urination', 'Erectile Dysfunction', 'Frequent Urination', 'Hesitancy Dribbling', 'Prostate Problems',
                                                    'Urine Retention'));
            
        
        $this->fields['endocrine'] = new App_Formitive_Input_Checkboxes('endocrine', $this->dbRow, 'Endocrine');
            $this->fields['endocrine']->options_add(array('Abnormal Frequency of Urination', 'Cold Intolerance', 'Diabetes', 'Excessive Appetite', 'Excessive Thirst', 'Goiter', 
                                                            'Hair Loss', 'Heat Intolerance', 'Unusual Hair Growth', 'Voice Changes'));
            
        $this->fields['skin'] = new App_Formitive_Input_Checkboxes('skin', $this->dbRow, 'Skin');
            $this->fields['skin']->options_add(array('Changes in Skin Color', 'Changes in Nail Texture', 'Hair Growth', 'Hair Loss', 'History of Skin Disorders', 'Hives', 
                                                    'Itching', 'Rash', 'Skin Lesions/Ulcers', 'Paresthesias', 'Varicosities'));
            
        $this->fields['nervous'] = new App_Formitive_Input_Checkboxes('nervous', $this->dbRow, 'Nervous System');
            $this->fields['nervous']->options_add(array('Dizziness', 'Facial Weakness', 'Headache', 'Limb Weakness', 'Loss of Consciousness', 'Loss of Memory', 'Numbness', 
                                                        'Seizures', 'Sleep Disturbance', 'Slurred Speech', 'Stress', 'Strokes', 'Termor', 'Unsteadiness of Gait/Loss of Balance'));
            
            
        $this->fields['psychologic'] = new App_Formitive_Input_Checkboxes('psychologic', $this->dbRow, 'Psychological');
            $this->fields['psychologic']->options_add(array('Anhedonia', 'Anxiety', 'Behavioral Change', 'Bi-Polar Disorder', 'Confusion', 'Convulsions', 'Depression', 'Insomnia', 
                                                            'Loss or Change in Appetite', 'Memory Loss', 'Mood Changes'));
            
            
        $this->fields['allergy'] = new App_Formitive_Input_Checkboxes('allergy', $this->dbRow, 'Allergies');
            $this->fields['allergy']->options_add(array('Acute Nasal Congestion', 'Anaphylaxis', 'Chronic Nasal Congestion', 'Food Intolerance', 'Itching', 'Rash', 'Sneezing'));
            
            
        $this->fields['hematologic'] = new App_Formitive_Input_Checkboxes('hematologic', $this->dbRow, 'Hematologic');
            $this->fields['hematologic']->options_add(array('Anemia', 'Bleeding', 'Blood Clotting', 'Blood Transfusion', 'Bruising Easily', 'Fatigue', 'Lymph Node Swelling'));
            
            
            
                
                
                
        
    }
}

?>