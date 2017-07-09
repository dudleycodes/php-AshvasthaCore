<?php
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )  {   die('Direct access to this page denied');   }

class App_Formitive_Admin extends App_Formitive
{
    public function view()
    {   
        global $Administrator, $Arguments, $Database;
        
        if ($Arguments->get(2) == null)
        {
            redirect('/apps/Formitive/view-ready');
        }
        elseif(strstr($Arguments->get(2), 'view-'))
        {
            switch ($Arguments->get(2))
            {
                case 'view-ready':      return $this->view_list('ready', 0);        break;
                case 'view-archived':   return $this->view_list('archived', 0);     break;
                case 'view-unread':     return $this->view_list('unread', 0);       break;
                case 'view-1day':       return $this->view_list('ready', 1);        break;
                case 'view-2days':      return $this->view_list('ready', 2);        break;
                case 'view-7days':      return $this->view_list('ready', 7);        break;
                case 'view-30days':     return $this->view_list('ready', 30);       break;                
            }
        }
        elseif($Arguments->get(2) == 'get-pdf' AND is_numeric($Arguments->get(3)))
        {
            $id = (int)$Arguments->get(3);
            try
            {
                $sql = "SELECT * FROM `apps_formitive_index` WHERE `id` = '$id' LIMIT 1;";
                $index = $Database->fetchRow($sql);
                unset($sql);
            }
            catch (Exception $e)
            {
                $msg = '<h3>Could not query database!</h3>';
                if (DEBUG_MODE) $msg = $msg . "<br>Exception thrown: $e<br><br>";
                if (DEBUG_MODE) $msg = $msg . $sql;
                $returnView->set_content($msg);
                return $returnView;
            }
            
            if (!in_array($index['portal'], $Administrator->portals('formitive', 'view')))
            {
                $msg = '<h3>You do not have access to this form!</h3>';
                $returnView->set_content($msg);
                return $returnView;
            }
            else
            {
                $this->stream_pdf($id);
            }
        }
        elseif ($Arguments->get(2) == 'action')
        {
            $msg = new view();
            if (isset($_POST['action-archive']) AND is_numeric($_POST['action-archive']))
            {                
                try
                {
                    $sql = "SELECT * FROM `apps_formitive_index` WHERE `id` = '". $_POST['action-archive'] ."' LIMIT 1;";
                    $index = $Database->fetchRow($sql);
                    unset($sql);
                }
                catch (Exception $e)
                {
                    $msg = '<h3>Could not query database!</h3>';
                    if (DEBUG_MODE) $msg = $msg . "<br>Exception thrown: $e<br><br>";
                    if (DEBUG_MODE) $msg = $msg . $sql;
                    $returnView->set_content($msg);
                    return $returnView;
                }
                
                if (in_array($index['portal'], $Administrator->portals('formitive', 'archive')))
                {
                    $this->set_patient_status($_POST['action-archive'], 'archived');
                    $msg->set_content('<h3>The form has been archived</h3><br><br><p><a href="/apps/Formitive/view-ready">Click here to continue...</a></p>');
                }
                return $msg;
            }
            else
            {   
            }
            return $msg;
        }
        else
        {
            $t = new View();
            $t->set_content('<h3>Page Not Found!</h3>')->set_status(404);
            return $t;
        }
    }
    
    
    /**
     * Retreive index of formitive forms
     *
     * @param string $status The status of the forms to be retrieved (ready, archived, etc)
     * @param integer $maxAge The maximum age (in days) of forms to be retrieved
     * 
     * @return View()
     */
    private function view_list($status = 'ready', $maxAge = 0)
    {
        global $Administrator, $Database;

        $maxAge = (int)($maxAge * 86400); //convert to seconds
        $portals = implode(',', $Administrator->portals('formitive', 'view'));
        $returnView = new View;
        
        $viewUnreadOnly = ($status == 'unread')? true: false;
        $validStatuses = array('archived', 'ready');
        if (!-in_array($status, $validStatuses)) $status = 'ready';
         
        $sql = "SELECT * FROM `apps_formitive_index` WHERE `portal` IN ($portals) AND `status` = '$status' ORDER BY `activity` DESC LIMIT 101;";
        try
        {           
            $result = $Database->fetchAssoc($sql);
            unset($sql);
        }
        catch (Exception $e)
        {
            $msg = '<h3>Could not query database!</h3>';
            if (DEBUG_MODE) $msg = $msg . "<br>Exception thrown: $e<br><br>";
            if (DEBUG_MODE) $msg = $msg . $sql;
            $returnView->set_content($msg);
            return $returnView;
        }
        
        $statUnread = 0;
        $stat24Hours = 0;
        $stat48Hours = 0;
        $stat7Days = 0;
        $stat30Days = 0;
        
        foreach($result as $id => $row)
        {
            if ((time() - $row['activity']) < 86400) $stat24Hours++;
            if ((time() - $row['activity']) < 172800) $stat48Hours++;
            if ((time() - $row['activity']) < 604800) $stat7Days++;
            if ((time() - $row['activity']) < 2592000) $stat30Days++;
            if ($row['accessed'] == 0) $statUnread++;
        }
        
        //determine table's title
        $mark = ($viewUnreadOnly)? 'unread': $status;
        $from = ($maxAge > 0)? 'From last '. (int)($maxAge / 86400) .' Days': '';
        $tableTitle = "View Patient Forms Marked \"$mark\" $from";
        
        
        $t = '
        <div class="portlet x3">
			
            <div class="portlet-header">
                <h4>Quick Links</h4>
            </div> <!-- .portlet-header -->
			
            <div class="portlet-content">
                <table cellspacing="0" class="info_table">
                    <tbody>
                        <tr>
                            <td class="value">--</td>
                            <td><a href="/apps/Formitive/view-ready">View Forms</a></td>
                        </tr>
                        <tr>
                            <td class="value">'. $statUnread .'</td>
                            <td class="full"><a href="/apps/Formitive/view-unread">Unread</a></td>
                        </tr>
                        <tr>
                            <td class="value">'. $stat24Hours .'</td>
                            <td class="full"><a href="/apps/Formitive/view-1day">Last 24 hours</a></td>
                        </tr>
                        <tr>
                            <td class="value">'. $stat48Hours .'</td>
                            <td class="full"><a href="/apps/Formitive/view-2days">Last 48 hours</a></td>
                        </tr>
                        <tr>
                            <td class="value">'. $stat7Days .'</td>
                            <td class="full"><a href="/apps/Formitive/view-7days">Last 7 days</a></td>
                        </tr>
                        <tr>
                            <td class="value">'. $stat30Days .'</td>
                            <td class="full"><a href="/apps/Formitive/view-30days">Last 30 days</a></td>
                        </tr>
                        <tr>
                            <td class="value">--</td>
                            <td class="full"><a href="/apps/Formitive/view-archived">View Archived Forms</a></td>
                        </tr>                        

                    </tbody>
                </table>
            </div> <!-- .portlet-content -->			
        </div> <!-- .portlet -->
        
        <div id="dash_chart" class="portlet x9">
        
            <div class="portlet-header">
                <h4>'. $tableTitle .'</h4>
            </div> <!-- .portlet-header -->

            <table cellpadding="0" cellspacing="0" border="0" class="display">
                <thead>
                    <tr>
                        <th>PDF</th>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th colspan="2">Primary Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
        
        
        foreach ($result as $row)
        {
            if ($maxAge == 0 OR ((time() - $row['activity'] + 3601) <= $maxAge))
            {
                $linkPDF = 'http://'. URL_FRONTEND .'apps/Formitive/get-pdf/'. $row['id'];
                $name = ucwords(trim($row['last']). ', '. trim($row['first']));
                $iconPDF = '/formitive/icons/pdf.png';

                $phone = trim(strtolower($row['phone1']));

                if (strstr($phone, 'work'))
                {
                    $iconPhone = '/formitive/icons/workphone.png';                    
                    $phone = str_replace('(work)', null, $phone);
                }
                elseif (strstr($phone, 'cell'))
                {
                    $iconPhone = '/formitive/icons/cellphone.png';                    
                    $phone = str_replace('(cell)', null, $phone);
                }
                elseif (strstr($phone, 'fax'))
                {
                    $iconPhone = '/formitive/icons/fax.png';     
                    $phone = str_replace('(fax)', null, $phone);
                }
                else
                {
                    $iconPhone = '/formitive/icons/phone.png';
                    $phone = str_replace('(home)', null, $phone);
                }
                $phone = $this->format_phonenumber($phone);

                if (!$viewUnreadOnly OR $row['accessed'] == 0)
                {
                    $t .= (!$viewUnreadOnly AND $row['accessed'] == 0)? '<tr class="even gradeC">': '<tr>';
                    $t .= ' <td><a href="'. $linkPDF .'" target="_blank"><img src="'. $iconPDF .'" height="28px"></a></td>
                            <td style="vertical-align:middle; padding-left: 0px;"><a href="'. $linkPDF .'" target="_blank">'. $name .'</a></td>
                            <td style="vertical-align:middle; padding-left: 0px;"><a href="mailto:'. $row['email'] .'">'. $row['email'] .'</a></td>
                            <td><img src="'. $iconPhone .'" height="28px"></td>
                            <td style="vertical-align:middle; padding-left: 0px;">'. $phone .'</td>
                            <td style="vertical-align:middle; padding-left: 0px;">
                                <form method="post" action="/apps/Formitive/action">
                                    <select name="action-archive" size="1">';
                                        if ($status != 'archived') $t .= '<option value="'. $row['id'] .'">Archive</option>';
                                    $t .='</select>
                                <input type="submit" value=">" />
                                </form>
                            </td>
                        </tr>';
                }
            }
        }
        
        $t .= '</tbody>
        </table>
        </div>';
        
        
        $returnView->set_content($t);
        return $returnView;
    }
    
    
    private function stream_pdf($id)
    {
        if ((defined('DEBUG_MODE') AND !DEBUG_MODE OR !defined(DEBUG_MODE)))
        {
            ini_set('display_errors','Off');
        }
        else
        {
            error_reporting (E_ALL ^ E_NOTICE);
        }
        

        $pdf = $this->admin_generate_pdf($id);
        if ($pdf)
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Transfer-Encoding: binary");
            echo $pdf;
            die('successful death');
        }
        else
        {
            die('<h2>Error - could not find patient form!</h2>');
        }
    }

    
    /**
     * Generates intake form pdf
     *
     * @access protected
     * @param  integer $id (required) ID of patient
     * @return boolean|string False if failed | String containing the generated pdf (hexidecimal)
     */
    protected function admin_generate_pdf($id)
    {
        global $Database;
        
        if (!is_numeric($id)) return false;

        try
        {
            $sql = "SELECT * FROM `apps_formitive_index` WHERE `id` = '$id' LIMIT 1";
            $index = $Database->fetchRow($sql);
            unset($sql);
        }
        catch (Exception $e)
        {
            return false;
        }

        if ( empty($index) )    {   return false;   }

        DEFINE( 'SEX', strtoupper($index['sex']) );

        /*
         *  Setup TCPDF
         */
        require_once( ROOT_CORE. 'Library/tcpdf/tcpdf.php' );
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);    // set default monospaced font
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);        //set auto page breaks
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);             //set image scale factor
        $pdf->SetFont('times', 'BI', 14);                       //set default font
        
        /*
         *  Set Document Information
         */
        $title = 'Patient intake form for '. ucwords(trim(trim($index['first']). ' '. $index['last']));
        $subject = $title .' last updated on '. date('l F jS, Y', $index['activity']);

        $pdf->SetAuthor('Formitive');
        $pdf->SetCreator('Formitive App');
        $pdf->SetTitle( $title );       unset($title);
        $pdf->SetSubject( $subject );   unset($subject);

        /*
         *  PAGE ONE 
         */
        $pdf->AddPage();

        $sql = "SELECT * FROM `apps_formitive_general` WHERE `id` = '$id' LIMIT 1;";
        $general = $Database->fetchRow($sql);
        unset($sql);

        $address = ucwords($general['gen_address']);
        $state = (strlen(trim($general['gen_state'])) == 2) ? strtoupper(trim($general['gen_state'])): trim($general['gen_state']);
        $addressCityStateZip = ucwords(trim($general['gen_city'])). ', '. $state. ' '. $general['gen_zip'];
        $birthday = $general['gen_birthday'];
        $dateFiled = date( 'F j, Y', $index['activity']);
        $email = $general['gen_email'];
        $employerFax = $general['employer_fax'];
        $employerName = ucwords($general['employer_name']);
        $employerPhone = $general['employer_phone'];
        $kinName = ucwords($general['kin_first'] .' '. $general['kin_last']);
        $kinPhone1 = $general['kin_phone1'];
        $kinPhone2 = $general['kin_phone2'];
        $kinRelationship = $general['kin_relationship'];
        $nameFull = ucwords($general['gen_first']. ' '. $general['gen_middle']. ' '. $general['gen_last']);
        $phone1 = $general['gen_phone1'];
        $phone2 = $general['gen_phone2'];
        $sex = ( SEX == 'M') ? 'Male' : 'Female';
        $spouseName = ucwords($general['gen_spouse']);
        unset($general);


        $sql = "SELECT * FROM `apps_formitive_insurance` WHERE `id` = '$id' LIMIT 1;";
        $insurance = $Database->fetchRow($sql);
        unset($sql);
        
        $insuranceResponsible = 'Myself';
        if ( $insurance['bill_spouse'] <> NULL )		{	$insuranceResponsible = ' and spouse';	}
        if ( $insurance['bill_workers_comp'] <> NULL )          {	$insuranceResponsible = ' and workers comp';	}
        if ( $insurance['bill_auto_insurance'] <> NULL )	{	$insuranceResponsible = ' and auto insurance';	}
        if ( $insurance['bill_medicaid'] <> NULL )		{	$insuranceResponsible = ' and medicaid';	}
        if ( $insurance['bill_other'] <> NULL )                 {	$insuranceResponsible = ' and '. $insurance['bill_other'];	}
        $insuranceCardNumber = $insurance['insurance_card_number'];
        $insuranceCarrier = $insurance['insurance_carrier'];
        $insuranceGroupNumber = $insurance['insurance_group_number'];
        $insuranceHoldersBirthday = $insurance['insurance_holders_birthday'];
        $insuranceHoldersName = ucwords($insurance['insurance_holders_name']);
        $insurancePcp = $insurance['insurance_pcp'];
        unset($insurance);
        
        $html = null;
        $html .= '<table width="95%">';
        $html .= "<tr>";
        $html .= '     <td width="250px"></td>';
        $html .= '<td></td>';
        $html .= "     <td>Patient Name: $nameFull<br />";
        $html .= "     Date Filed: $dateFiled<br />";
        $html .= "     Primary Phone: $phone1</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= '     <td colspan="2">';
        $html .= "         <hr />";
        $html .= "     </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "     <td><b>Birthday:</b> $birthday<br />";
        $html .= "     Sex: $sex<br />";
        if (!empty($spouseName))
        {
        $html .= "     Spouse Name: $spouseName<br />";
        }
        $html .= "      Phone 1: $phone1<br />";
        if (!empty($phone2))
        {
        $html .= "      Phone 2: $phone2";
        }
        $html .= "</td>";
        $html .= "     <td valign=\"top\"><b>Address: </b>$address<br />$addressCityStateZip<br />";
        $html .= "         Email: $email<br />";
        $html .= "     </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= '     <td colspan="2">';
        $html .= "         <hr />";
        $html .= "     </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= '     <td><p align="center"><u>Emergency Contact</u></p></td>';
        $html .= '     <td><p align="center"><u>Employer</u></p></td>';
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "     <td>";
        $html .= "         Name: $kinName<br />";
        $html .= "         Relationship: $kinRelationship<br />";
        $html .= "         Phone 1: $kinPhone1<br />";
        $html .= "         Phone 2: $kinPhone1";
        $html .= "     </td>";
        $html .= "     <td>";
        $html .= "         Name: $employerName<br />";
        $html .= "         Phone: $employerPhone<br />";
        $html .= "         Fax: $employerFax";
        $html .= "     </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= '     <td colspan="2">';
        $html .= "         <p><hr /><u>Insurance Information</u></p>";
        $html .= "     </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= '      <td colspan="2">';
        $html .= "          Who's Responsible for the bill: $insuranceResponsible<br />";
        $html .= "          Insurance Carrier: $insuranceCarrier<br />";
        $html .= "          Policy Holder's Name: $insuranceHoldersName<br />";
        $html .= "          Policy Holder's Birthday: $insuranceHoldersBirthday<br />";
        $html .= "          Health ID Card Number: $insuranceCardNumber<br />";
        $html .= "          Group #: $insuranceGroupNumber<br />";
        $html .= "          Primary Care Physician: $insurancePcp";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "</table>";

        $pdf->writeHTML( $html, true, 0, true, 0);
        unset($html);

        unset($address);                unset($addressCityStateZip);        unset($birthday);
        unset($dateFiled);              unset($email);                      unset($employerFax);
        unset($employerName);           unset($employerPhone);              unset($kinName);
        unset($kinPhone1);              unset($kinPhone2);                  unset($kinRelationship);
        unset($nameFull);               unset($phone1);                     unset($phone2);
        unset($sex);                    unset($spouseName);                 unset($state);
        
        unset($insuranceResponsible);   unset($insuranceCardNumber);        unset($insuranceCarrier);
        unset($insuranceGroupNumber);   unset($insuranceHoldersBirthday);   unset($insuranceHoldersName);
        unset($insurancePcp);
        

        /*
         *  PAGE TWO
         */

        $pdf->AddPage();

        $sql = "SELECT * FROM `apps_formitive_current` WHERE `id` = '$id' LIMIT 1;";
        $current = $Database->fetchRow($sql);

        $condition = $current['conditions'];
        $conditionCause = $current['conditions_type'];
        $conditionStartdate = $current['conditions_startdate'];
        $conditionExplain = $current['conditions_explain'];
        $conditionDate = $current['conditions_date'];
        $conditionTime = $current['conditions_time'];
        $conditionStarted = $current['conditions_started'];
        $conditionOthers = $current['conditions_others'];
        $conditionsPains = $this->chart_data_output_readable($current['conditions_chart']);
        
        unset($current);
        

        $sql = "SELECT * FROM `apps_formitive_systems` WHERE `id` = '$id' LIMIT 1;";
        $systems = $Database->fetchRow($sql);

        $constitutionals = $systems['constitutionals'];
        $ent = $systems['ent'];
        $vision = $systems['vision'];
        $respiration = $systems['respiration'];
        $cardio = $systems['cardio'];
        $gastrointestinal = $systems['gastrointestinal'];
        $female = $systems['female'];
        $male = $systems['male'];
        $endocrine = $systems['endocrine'];
        $skin = $systems['skin'];
        $nervous = $systems['nervous'];
        $psychologic = $systems['psychologic'];
        $allergy = $systems['allergy'];
        $hematologic = $systems['hematologic'];


        $html = null;
        $html .= '<table width="95%">';
        $html .= '<tr>';
        $html .= '      <td colspan="2">';
        $html .= '          <p align="center"><b>Current Conditions</b></p><hr />';
        $html .= '      </td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '      <td width="425px">';
        $html .= "          Unwanted condition: $condition<br />";
        $html .= "          Condition began: $conditionStartdate<br />";
        $html .= "          Cause: $conditionCause<br />";
        $html .= "          Details: $conditionExplain<br />";
        $html .= "          Date of Accident: $conditionDate<br />";
        $html .= "          Time of Accident: $conditionTime<br />";
        $html .= "          Condition Started: $conditionStarted<br />";
        $html .= "          Other conditions: $conditionOthers<br />";
        $html .= "      </td>";
        $html .= '      <td width="175px">';
        //$html .= "          <img width=\"160px\" height=\"184px\" src=\"$conditionsImage\"/>";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td colspan=\"2\">";
        $html .= "          Current Aches and Pains: $conditionsPains";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= '      <td colspan="2">';
        $html .= '          <p align="center">Review of Systems</p><hr />';
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= '      <td colspan="2">';
        $html .= "          Constitutional: $constitutionals<br />";
        $html .= "          Eyes/Vision: $vision<br />";
        $html .= "          Ears, Noseand Throat: $ent<br />";
        $html .= "          Respiration: $respiration<br />";
        $html .= "          Cardiovascular: $cardio<br />";
        $html .= "          Gastrointestinal: $gastrointestinal<br />";
        if ( SEX == 'F' )
        {
        $html .= "          Female: $female<br />";
        }
        else
        {
        $html .= "          Male: $male<br />";
        }
        $html .= "          Endocrine: $endocrine<br />";
        $html .= "          Skin: $skin<br />";
        $html .= "          Nervous System: $nervous<br />";
        $html .= "          Psychologic: $psychologic<br />";
        $html .= "          Allergy: $allergy<br />";
        $html .= "          Hematologic: $hematologic";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "</table>";

        $pdf->writeHTML( $html, true, 0, true, 0);
        unset($html);
        

        unset($condition);              unset($conditionCause);     unset($conditionStartdate);
        unset($conditionExplain);       unset($conditionDate);      unset($conditionTime);
        unset($conditionStarted);       unset($conditionOthers);    unset($conditionPains);

        unset($constitutionals);        unset($ent);                unset($vision);
        unset($respiration);            unset($cardio);             unset($gastrointestinal);
        unset($female);                 unset($male);               unset($endocrine);
        unset($skin);                   unset($nervous);            unset($psychologic);
        unset($allergy);                unset($hematologic);


        /*
         *  PAGE THREE
         */
        $pdf->AddPage();

        $sql = "SELECT * FROM `apps_formitive_history` WHERE `id` = '$id' LIMIT 1;";
        $result = $Database->fetchRow($sql);

        //general family
        $family = 'General family: '. $result['history_family_status'] .', ';
        $family .= ( _bool($result['history_family_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $family .= ' significant diseases: ';
        $family .= (empty($result['history_family_other']))? 'none': $result['history_family_other'];
        if (empty($result['history_family_status'])) $family = 'Family: patient left this section empty';
        //father
        $father = 'Father: '. $result['history_father_status'] .', ';
        $father .= ( _bool($result['history_father_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $father .= ' significant diseases: ';
        $father .= (empty($result['history_father_other']))? 'none': $result['history_father_other'];
        //mother
        $mother = 'Mother: '. $result['history_mother_status'] .', ';
        $mother .= ( _bool($result['history_mother_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $mother .= ' significant diseases: ';
        $mother .= (empty($result['history_mother_other']))? 'none': $result['history_mother_other'];
        //pgrandfather
        $pgrandfather = 'Paternal Grandfather: '. $result['history_paternal_grandfather_status'] .', ';
        $pgrandfather .= ( _bool($result['history_paternal_grandfather_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $pgrandfather .= ' significant diseases: ';
        $pgrandfather .= (empty($result['history_paternal_grandfather_other']))? 'none': $result['history_paternal_grandfather_other'];
        //pgrandmother
        $pgrandmother = 'Paternal Grandmother: '. $result['history_paternal_grandmother_status'] .', ';
        $pgrandmother .= ( _bool($result['history_paternal_grandmother_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $pgrandmother .= ' significant diseases: ';
        $pgrandmother .= (empty($result['history_paternal_grandmother_other']))? 'none': $result['history_paternal_grandmother_other'];
        //mgrandfather
        $mgrandfather = 'Maternal Grandfather: '. $result['history_maternal_grandfather_status'] .', ';
        $mgrandfather .= ( _bool($result['history_maternal_grandfather_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $mgrandfather .= ' significant diseases: ';
        $mgrandfather .= (empty($result['history_maternal_grandfather_other']))? 'none': $result['history_maternal_grandfather_other'];
        //mgrandmother
        $mgrandmother = 'Maternal Grandmother: '. $result['history_maternal_grandmother_status'] .', ';
        $mgrandmother .= ( _bool($result['history_maternal_grandmother_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $mgrandmother .= ' significant diseases: ';
        $mgrandmother .= (empty($result['history_maternal_grandmother_other']))? 'none': $result['history_maternal_grandmother_other'];
        //sons
        $sons = 'Sons: '. $result['history_sons_status'] .', ';
        $sons .= ( _bool($result['history_sons_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $sons .= ' significant diseases: ';
        $sons .= (empty($result['history_sons_other']))? 'none': $result['history_sons_other'];
        if ($result['history_sons_status'] == 'none') $sons = 'Sons: None.';
        //daughters
        $daughters = 'Daughters: '. $result['history_daughters_status'] .', ';
        $daughters .= ( _bool($result['history_daughters_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $daughters .= ' significant diseases: ';
        $daughters .= (empty($result['history_daughters_other']))? 'none': $result['history_daughters_other'];
        if ($result['history_daughters_status'] == 'none') $daughters = 'Daughters: None.';
        //brothers
        $brothers = 'Brothers: '. $result['history_brothers_status'] .', ';
        $brothers .= ( _bool($result['history_brothers_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $brothers .= ' significant diseases: ';
        $brothers .= (empty($result['history_brothers_other']))? 'none': $result['history_brothers_other'];
        if ($result['history_brothers_status'] == 'none') $brothers = 'Brothers: None.';
        //sisters
        $sisters = 'Sisters: '. $result['history_sisters_status'] .', ';
        $sisters .= ( _bool($result['history_sisters_normally_developed']) )? 'normally developed, ': 'not normally developed, ';
        $sisters .= ' significant diseases: ';
        $sisters .= (empty($result['history_sisters_other']))? 'none': $result['history_sisters_other'];
        if ($result['history_sisters_status'] == 'none') $sisters = 'Sisters: None.';

        $html = null;
        $html .= '<table width="95%">';
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= '          <p align="center">Family History</p><hr />';
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $family";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $father";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $mother";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $pgrandfather";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $pgrandmother";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $mgrandfather";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $mgrandmother";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $sons";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $daughters";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $brothers";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "      <td>";
        $html .= "          $sisters";
        $html .= "      </td>";
        $html .= "</tr>";
        $html .= "</table>";

        $pdf->writeHTML( $html, true, 0, true, 0);
        unset($html);

        /*
         *  PAGE FOUR
         */
        $pdf->AddPage();

        $sql = "SELECT * FROM `apps_formitive_history` WHERE `id` = '$id' LIMIT 1;";
        $history = $Database->fetchRow($sql);

        $previousWho = ucwords($history['previous_care_who']);
        $previousTreatment = ucfirst($history['previous_care_treatment']);
        $previousBeneficial = ucfirst($history['previous_care_treatment_beneficial']);
        $previousDetails = ucfirst($history['previous_care_details']);
        
        $chiroWho = ucwords($history['previous_chiro_name']);
        $chiroWhere = ucwords($history['previous_chiro_location']);
        $chiroWhen = $history['previous_chiro_date'];

        $childhood = (trim($history['childhood_illnesses']) == 'other,' )? 'None': $history['childhood_illnesses'];
        $adulthood = (trim($history['adult_illnesses']) == 'other,' )? 'None': $history['adult_illnesses'];

        $medications = null;
        $medications = (empty($history['medication1_name']))? null: $history['medication1_name'] .' ('. $history['medication1_dosage'] .') for '. $history['medication1_reason'] .' since '. $history['medication1_length'] .'<br />';
        $medications .= (empty($history['medication2_name']))? null: $history['medication2_name'] .' ('. $history['medication2_dosage'] .') for '. $history['medication2_reason'] .' since '. $history['medication2_length'] .'<br />';
        $medications .= (empty($history['medication3_name']))? null: $history['medication3_name'] .' ('. $history['medication3_dosage'] .') for '. $history['medication3_reason'] .' since '. $history['medication3_length'] .'<br />';
        $medications .= (empty($history['medication4_name']))? null: $history['medication4_name'] .' ('. $history['medication4_dosage'] .') for '. $history['medication4_reason'] .' since '. $history['medication4_length'] .'<br />';
        $medications .= (empty($history['medication5_name']))? null: $history['medication5_name'] .' ('. $history['medication5_dosage'] .') for '. $history['medication5_reason'] .' since '. $history['medication5_length'] .'<br />';
        $medications .= (empty($history['medication6_name']))? null: $history['medication6_name'] .' ('. $history['medication6_dosage'] .') for '. $history['medication6_reason'] .' since '. $history['medication6_length'] .'<br />';
        $medications .= (empty($history['medication7_name']))? null: $history['medication7_name'] .' ('. $history['medication7_dosage'] .') for '. $history['medication7_reason'] .' since '. $history['medication7_length'];
        $medications = (empty($medications))? 'None': $medications;

        $surgeries = null;
        $surgeries = (empty($history['surgery1']))? null: 'Surgery: '. $history['surgery1'] .' on '. $history['surgery1_date'] .'<br />';
        $surgeries .= (empty($history['surgery2']))? null: 'Surgery: '. $history['surgery2'] .' on '. $history['surgery1_date'] .'<br />';
        $surgeries .= (empty($history['surgery3']))? null: 'Surgery: '. $history['surgery3'] .' on '. $history['surgery1_date'] .'<br />';
        $surgeries .= (empty($history['surgery4']))? null: 'Surgery: '. $history['surgery4'] .' on '. $history['surgery1_date'] .'<br />';
        $surgeries .= (empty($history['surgery5']))? null: 'Surgery: '. $history['surgery5'] .' on '. $history['surgery1_date'] .'<br />';
        $surgeries .= (empty($history['surgery6']))? null: 'Surgery: '. $history['surgery6'] .' on '. $history['surgery1_date'];
        $surgeries = (empty($surgeries))? 'None': $surgeries;


        $injuries = null;
        $injuries = (empty($history['injury1']))? null: 'Injury: '. $history['injury1'] .' on '. $history['injury1_date'] .'<br />';
        $injuries .= (empty($history['injury2']))? null: 'Injury: '. $history['injury2'] .' on '. $history['injury2_date'] .'<br />';
        $injuries .= (empty($history['injury3']))? null: 'Injury: '. $history['injury3'] .' on '. $history['injury3_date'] .'<br />';
        $injuries .= (empty($history['injury4']))? null: 'Injury: '. $history['injury4'] .' on '. $history['injury4_date'] .'<br />';
        $injuries .= (empty($history['injury5']))? null: 'Injury: '. $history['injury5'] .' on '. $history['injury5_date'] .'<br />';
        $injuries .= (empty($history['injury6']))? null: 'Injury: '. $history['injury6'] .' on '. $history['injury6_date'];
        $injuries = (empty($injuries))? 'None': $injuries;


        $html .= '<table border="1" width="95%">';
        $html .= "      <tr>";
        $html .= "          <td colspan=\"2\">";
        $html .= "              <p align=\"center\">Past Health History</p>";
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= "          <td>";
        $html .= "              <center>Previous Care</center>";
        $html .= "          </td>";
        $html .= "          <td>";
        $html .= "              <center>Previous Chiropractor</center>";
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= "          <td>";
        $html .= "              Who: $previousWho<br />";
        $html .= "              Treatment: $previousTreatment<br />";
        $html .= "              Beneficial: $previousBeneficial<br />";
        $html .= "              Details: $previousDetails<br />";
        $html .= "          </td>";
        $html .= "          <td>";
        $html .= "              Who: $chiroWho<br />";
        $html .= "              Location: $chiroWhere<br />";
        $html .= "              When: $chiroWhen<br />";
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= '          <td colspan="2">';
        $html .= '              <b>Medications:</b>';
        $html .= "              <blockquote>$medications</blockquote>";
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= '          <td colspan="2">';
        $html .= '              <p align="center">Childhood Conditions</p>';
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= '          <td colspan="2">';
        $html .= "              $childhood";
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= '          <td colspan="2">';
        $html .= '              <p align="center">Adult Conditions</p>';
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= '          <td colspan="2">';
        $html .= "              $adulthood";
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= '          <td colspan="2">';
        $html .= "              <b>Surgeries:</b>";
        $html .= "              <blockquote>$surgeries</blockquote>";
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "      <tr>";
        $html .= '          <td colspan="2">';
        $html .= '              <b>Injuries:</b>';
        $html .= "              <blockquote>$injuries</blockquote>";
        $html .= "          </td>";
        $html .= "      </tr>";
        $html .= "</table>";

        $pdf->writeHTML( $html, true, 0, true, 0);
        unset($html);

        unset($previousWho);                unset($previousTreatment);          unset($previousBeneficial);
        unset($previousDetails);            unset($chiroWho);                   unset($chiroWhere);
        unset($chiroWhen);                  unset($childhood);                  unset($adulthood);
        unset($medications);                unset($surgeries);                  unset($injuries);
        
        /*
         * OUTPUT
         */

        return $pdf->Output( 'S');
    }

    protected function admin_write_image($id)
    {
        $filename = ROOT ."cache/formitive/$id.png";

        unlink($filename);
        $handle = fopen($filename, "w");
        $t = fwrite($handle, $this->chart_generate_image());
        fclose($handle);

        return ($t > 0)? $filename: false;
    }

    function format_phonenumber($phoneNumber)     //Currently only formats US phone numbers
    {   
        $phoneNumber = (string)preg_replace('/\D/', '', $phoneNumber);
        
        if (strlen($phoneNumber) == 11)        //US phone number w/ country code
        {
            return '('. substr($phoneNumber, 1 ,3) .') '. substr($phoneNumber, 4 ,3) .'-'. substr($phoneNumber, 7 ,4);
        }
        elseif (strlen($phoneNumber) == 10)    //US phone number w/o country code
        {
            return '('. substr($phoneNumber, 0 ,3) .') '. substr($phoneNumber, 3 ,3) .'-'. substr($phoneNumber, 6 ,4);
        }
        else
        {
            return $phoneNumber;
        }
    }
    
}


?>