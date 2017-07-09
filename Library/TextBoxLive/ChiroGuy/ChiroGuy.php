<?php
/**
 * 
 * Input_Checkboxes
 * 
 * Creates a diagram of the human body for user to select and specify their
 * chiropractic concerns and issues.
 * 
 * Required database field length: 1536
 * 
 * ╔═══════════════════════╗
 * ║  FUNCTIONAL OVERVIEW  ║
 * ╚═══════════════════════╝
 * 
 *      validKeys is the set of areas that the user can select
 *      validValues is the set of pains that can be attributed to the areas (keys)
 * 
 */

class TextBoxLive_ChiroGuy extends TextBox960
{
    const USE_SESSION = TRUE;
    
    protected function __inputconstruct()
    {
        $this->validKeys = array( 'chest', 'forehead', 'head', 'left_arm', 'left_buttock', 'left_elbow', 'left_foot', 'left_forearm', 'left_hand',
            'left_inner_thigh', 'left_inner_leg', 'left_knee', 'left_leg', 'left_shoulder', 'left_temple', 'left_trapezius', 'left_thigh',
            'lower_back', 'middle_back', 'neck', 'sacrum', 'stomach', 'right_arm', 'right_buttock', 'right_elbow', 'right_foot', 'right_forearm',
            'right_hand', 'right_inner_leg',  'right_inner_thigh', 'right_knee', 'right_leg', 'right_shoulder', 'right_temple', 'right_trapezius',
            'right_thigh', 'upper_back' );
        
        $this->validValues = array('ache', 'burning', 'numbness', 'pins_&_needles', 'stabbing');
        
        //=
        //= If no pre-existing session->areas load and populate from implicit value
        //=
        if (!isset($this->session->values) AND !empty($this->implicitValue))
        {
            try
            {
                $this->sessions->values = Zend1_Json::decode($this->implicitValue);
            }
            catch (Exception $e)
            {
                $this->debugMessages[] = '__inputconstruct() was unable to Zend1_Json::decode implicit value of: '. $this->implicitValue ."<br>\n$e";
            }
        }
    }
    
    
    /**
     * Retrieves a variable or property of the input_object. Accepts 'fieldname' and 'value'.
     *
     * @param string $var 
     * @return void
     */
    public function get($var = null)
    {
        $var = strtolower($var);
        
        switch ($var)
        {
            default:
                return parent::get($var);
        }
    }
    
    /**
     * Gets the polygon cordinates for a given area name
     *
     * @param string $areaName
     * @return array Two-dimenisional array containing cordinates
     */
    protected function get_polygons($areaName)
    {
        $r = array();
        
        if (!in_array($areaName, $this->validAreas)) return $r;
        
        if (strstr($areaName, 'left'))
        {
            if ($areaName == 'left_arm')
            {
                $r[] = '77,104,77,97,58,97,58,106,59,107,58,117,58,117,58,118,74,118';
                $r[] = '264,110,264,91,267,85,280,85,281,92,281,106,284,112,284,116,270,116,269,115,268,112';
            }
            elseif ($areaName == 'left_buttock')
            {
                $r[] = '103,178,100,181,91,181,82,178,74,170,74,156,75,146,91,146,97,149,103,149';
            }
            elseif ($areaName == 'left_elbow')
            {
                $r[] = '74,118,58,118,56,124,50,141,69,141,71,137,72,123';
                $r[] = '271,125,270,116,284,116,290,131,291,136,274,136,273,134';
            }
            elseif ($areaName == 'left_hand')
            {
                $r[] = '56,173,44,173,35,185,34,198,36,201,39,202,43,202,47,198,50,192,53,188';
                $r[] = '300,167,290,167,291,180,292,187,296,194,302,198,307,197,308,194,308,185,311,183,311,179';
            }
            elseif ($areaName == 'left_inner_leg')
            {
                $r[] = '102,287,104,276,104,255,93,255,95,294,102,294';
                $r[] = '250,292,248,273,247,251,256,251,256,300,256,301,251,301';
            }
            elseif ($areaName == 'left_foot')
            {
                $r[] = '97,340,104,339,106,335,103,327,103,321,106,314,104,307,102,304,102,294,89,294,91,302,92,305,92,315,90,319,89,329,88,335,89,337';
                $r[] = '255,332,264,331,267,328,265,321,262,314,261,301,251,301,251,307,250,309,250,330';
            }
            elseif ($areaName == 'left_forearm')
            {
                $r[] = '69,141,50,141,50,154,48,163,44,173,56,173,58,166,63,156,66,151,68,147';
                $r[] = '274,136,292,136,294,144,296,153,299,165,299,166,290,166,287,158';
            }
            elseif ($areaName == 'left_knee')
            {
                $r[] = '85,243,83,255,104,255,103,233,84,233';
                $r[] = '244,228,247,251,265,251,266,251,266,245,264,240,264,228';
            }
            elseif ($areaName == 'left_inner_thigh')
            {
                $r[] = '91,181,93,233,103,233,103,183,100,181';
                $r[] = '243,224,242,221,243,173,252,173,252,227,244,227';
            }
            elseif ($areaName == 'left_leg')
            {
                $r[] = '82,267,83,255,93,255,95,294,89,294,87,290,84,283,83,279';
                $r[] = '262,290,260,298,260,301,256,301,256,251,266,251,267,257,266,268,265,276';
            }
            elseif ($areaName == 'left_shoulder')
            {
                $r[] = '63,74,61,82,60,83,59,85,58,97,78,97,84,87,84,66,70,66';
                $r[] = '256,61,267,62,273,67,277,76,280,85,267,85,266,81';
            }
            elseif ($areaName == 'left_temple')
            {
                $r[] = '252,17,254,22,254,24,248,24,247,21,248,13';
            }
            elseif ($areaName == 'left_thigh')
            {
                $r[] = '74,170,75,192,77,199,77,208,83,233,93,233,91,181,82,178';
                $r[] = '252,173,267,156,269,164,270,167,268,199,264,228,252,228';
            }
            elseif ($areaName == 'left_trapezius')
            {
                $r[] = '98,66,70,66,85,59,89,54,98,54';
            }
        }
        elseif (strstr($areaName, 'right'))
        {
            if ($areaName == 'right_arm')
            {
                $r[] = '142,105,141,95,122,95,126,117,144,117';
                $r[] = '197,101,196,116,214,116,215,114,215,84,197,84';
            }
            elseif ($areaName == 'right_buttock')
            {
                $r[] = '106,179,104,177,104,149,107,149,112,145,128,145,129,145,131,167,123,180,114,181,107,181';
            }
            elseif ($areaName == 'right_elbow')
            {
                $r[] = '131,129,126,117,144,117,145,123,151,139,133,139,132,136';
                $r[] = '196,127,195,131,191,138,210,138,211,132,212,131,214,116,196,116';
            }
            elseif ($areaName == 'right_foot')
            {
                $r[] = '116,340,122,340,124,338,125,337,125,331,123,325,122,320,121,315,120,312,120,299,122,293,108,293,109,296,110,319,110,332,109,334,108,338,110,340';
                $r[] = '236,333,234,331,227,331,227,327,228,322,230,316,231,314,231,301,241,301,242,306,243,311,245,317,246,322,247,324,247,332,244,333';
            }
            elseif ($areaName == 'right_forearm')
            {
                $r[] = '151,139,133,139,135,144,140,152,145,161,149,167,159,167,157,164,156,158,155,150';
                $r[] = '202,156,207,147,209,138,191,138,190,155,188,164,197,164';
            }
            elseif ($areaName == 'right_hand')
            {
                $r[] = '189,201,160,197,154,186,152,181,152,174,150,168,159,168,165,175,167,179,179,179,181,175,185,171,188,164,198,164,193,173,193,188,192,191,192,196,190,200';
            }
            elseif ($areaName == 'right_inner_thigh')
            {
                $r[] = '105,183,104,232,114,232,115,181,107,181';
                $r[] = '240,227,230,227,231,173,242,173';
            }
            elseif ($areaName == 'right_inner_leg')
            {
                $r[] = '106,285,105,277,105,256,115,256,114,293,108,293';
                $r[] = '242,278,243,273,243,254,241,251,235,251,235,301,241,301,242,301,242,293';
            }
            elseif ($areaName == 'right_knee')
            {
                $r[] = '122,233,123,244,125,255,125,256,105,256,104,232,122,232';
                $r[] = '241,233,242,235,242,250,241,251,222,251,221,238,221,227,241,227,241,232';
            }
            elseif ($areaName == 'right_leg')
            {
                $r[] = '125,266,122,293,114,293,115,256,125,256,126,260';
                $r[] = '229,293,225,277,223,269,222,268,222,251,235,251,235,301,231,301,231,297';
            }
            elseif ($areaName == 'right_shoulder')
            {
                $r[] = '135,72,139,80,141,89,142,89,142,95,120,95,116,87,116,66,129,66';
                $r[] = '201,71,210,63,214,62,220,62,214,84,196,84,197,81,198,80,199,75';
            }
            elseif ($areaName == 'right_temple')
            {
                $r[] = '223,18,228,13,231,21,230,24,223,24';
            }
            elseif ($areaName == 'right_thigh')
            {
                $r[] = '131,167,132,194,128,207,126,216,125,217,123,232,114,232,115,181,123,180';
                $r[] = '215,197,217,206,219,214,221,218,221,227,230,227,231,174,214,158';
            }
            elseif ($areaName == 'right_trapezius')
            {
                $r[] = '99,54,99,66,129,66,113,59,109,54';
            }
        }
        elseif (strstr($areaName, 'upper_back'))
        {
            $r[] = '98,66,84,67,84,86,85,87,115,87,116,86,116,66';
        }
        elseif (strstr($areaName, 'middle_back'))
        {
            $r[] = '85,87,77,98,79,119,87,121,113,121,121,120,121,100,120,95,115,87';
        }
        elseif (strstr($areaName, 'lower_back'))
        {
            $r[] = '77,124,87,121,113,121,123,123,128,145,112,145,109,138,95,138,91,146,75,146,77,137';
        }
        elseif (strstr($areaName, 'neck'))
        {
            $r[] = '256,61,224,61,230,51,232,53,241,53,248,49';
            $r[] = '108,47,110,41,88,41,89,48,88,54,109,54';
        }      
        elseif (strstr($areaName, 'stomach'))
        {
            $r[] = '218,136,215,144,214,158,231,173,251,173,269,154,266,144,265,136,264,125,257,123,251,118,229,118,217,122';
        }
        elseif (strstr($areaName, 'chest'))
        {
            $r[] = '230,118,251,118,256,122,264,125,264,90,266,84,255,61,221,61,214,84,215,84,217,122,229,118';
        }        
        elseif (strstr($areaName, 'sacrum'))
        {
            $r[] = '97,149,107,149,112,145,109,138,95,138,91,146';   
        }
        elseif (strstr($areaName, 'forehead'))
        {
            $r[] = '228,13,234,10,243,10,248,13,247,21,231,21';
        }        
        elseif (strstr($areaName, 'head'))
        {
            $r[] = '88,41,85,38,83,34,83,27,83,18,87,13,92,10,101,10,107,12,109,14,112,20,113,23,113,35,110,41';
            $r[] = '240,52,233,52,228,47,225,39,224,36,223,27,223,24,230,24,231,21,247,21,248,24,254,24,254,37,251,44,247,49';
        }
        
        return $r;
    }
    
    
    /**
     * Generates an image of "the guy" with the appropriate areas highlighted.
     *
     * @return String A binary blob containing the image data
     */
    protected function image_generate()
    {
        $backgroundFile = ROOT. 'Library/App/Formitive/Input/ChiroGuy/chiroguy1.png';
        
        try
        {
            $image = imagecreatefrompng($backgroundFile);
        }
        catch (Exception $e)
        {
            $this->debugMessages[] = "Input_ChiroGuy was unable to imagecreatefrompng($backgroundFile) on line ". __LINE__ ."<br>\n$e";
            return false;
        }
        
        //=
        //= Highlight any selected areas w/ colored polygons
        //=
        if (isset($this->session->values) AND !empty($this->session->values))
        {
            $color = imagecolorallocate($image, 0, 0, 230);
            
            $areaList = array_keys($this->session->values);
            foreach($areaList as $areaName)
            {
                $polyStrings = $this->get_polygons($areaName);
                foreach ($polyStrings as $polyString)
                {   
                    $polyString = str_replace(' ', null, $polyString);
                    $points = explode(',', $polyString);
                    $verticesCount = count($points) / 2;
                    
                    if ($verticesCount%2) $this->debugMessages[] = 'App_Formitive_Input_ChiroGuy -> image_generate() can\'t draw a polygon w/ an odd number of points';
                    if ($verticesCount < 3) $this->debugMessages[] = 'App_Formitive_Input_ChiroGuy -> image_generate() couldn\'t draw a polygon with less than three points.';

                    imagefilledpolygon($image, $points, $verticesCount, $color);                    
                }
            }
        }
        
        return $image;
    }
    
    
    public function snippet_div()
    {
        $r = null;
        
        $r .= ' <div class="ctrlHolder">';
        $r .= '     <label>'. $this->label .'</label>';
        $r .= '     <div>';
        $iFrameSource = 'http://'. DOMAIN .'/intake/input/ChiroGuy/'. $this->fieldname;
        $r .= '         <iframe src ="'. $iFrameSource .'" width="330px" height="380px" scrolling="no" frameborder="1">';
        $r .= '             <p>Your browser does not support iframes.</p>';
        $r .= '         </iframe>';
        $r .= '     </div>';
        $r .= '</div>';
        
        return $r;
    }
    
    public function stream_html($arguments = null)
    {   
        $areaName = (is_array($arguments))? array_shift($arguments): null;
        
        if ($areaName == 'image')
        {
            $this->stream_image();
        }
        elseif (!is_null($areaName) AND !in_array($areaName, $this->validAreas))
        {
            $this->stream_html_404();
        }
        else
        {
            if (!is_null($areaName))
            {
                if (isset($this->session->values[$areaName]))
                {
                    unset($this->session->values[$areaName]);
                }
                else
                {
                    $this->stream_html_pain_select($areaName);
                }
            }
            
            if (!empty($_POST))
            {
                $area = (isset($_POST[self::PREFIX . $this->fieldname .'_area']))? $_POST[self::PREFIX . $this->fieldname .'_area']: null;
                $pain = (isset($_POST[self::PREFIX . $this->fieldname .'_pain']))? $_POST[self::PREFIX . $this->fieldname .'_pain']: null;
                
                if (!empty($area) AND !empty($pain))
                {
                    $this->session->values[$area] = $pain;
                }
            }
            
            $this->stream_html_imagemap();
        }
    }
    
    protected function stream_html_404()
    {
        set_http_status(404);
        echo "<html>\n";
        echo "<head>";
        echo "<title>404 - Page Not Found!</title>";
        echo "</head>\n<body>";
        echo "<h2>Error: 404</h2>\n";
        echo "<h4>Page Not Found.</h4></body></html>";
        die();        
    }
    
    protected function stream_html_imagemap()
    {   
        $View = new View_Renderer();
        $View->add_resource_file('http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js');
        $View->add_resource_file('http://repo. - .com/formitive/js/jquery.metadata.js');
        $View->add_resource_file('http://repo. - .com/formitive/js/jquery.maphilight.js');
        $View->add_resource_file('http://repo. - .com/formitive/css/chiroguy.css');
        
        $View->add_head_script('$(function() {$(\'.map\').maphilight();})');
        
        $View->set_title('Select Your Problem Areas');
        
        $View->absorb('<body><p>');
        $imageFile = '/intake/input/ChiroGuy/'. $this->fieldname .'/image';
        $View->absorb('<img src="'. $imageFile .'" width="339" height="351" class="map" usemap="#map" border=0>');
        $View->absorb('<map name="map">');
        foreach ($this->validAreas as $area)
        {
            $description = ucwords(str_replace('_', ' ', $area));
            $polygonSets = $this->get_polygons($area);
            $selectionUrl = '/intake/input/ChiroGuy/'. $this->fieldname .'/'. $area;
            
            foreach ($polygonSets as $polygonSet)
            {
                $t = "\n".'<area shape="poly" alt="'. $description .'" coords="'. $polygonSet .'" href="'. $selectionUrl .'" title="'. $description .'" />';
                $View->absorb($t);
            }
        }
        $View->absorb('<area shape="default" nohref="nohref" alt="" />');
        $View->absorb('</map>');        
        $View->absorb('</p></body>');
        
        $View->set_status(200);
        
        echo $View->renderHtml();
        die();
    }
    
    
    protected function stream_html_pain_select($areaName)
    {
        if (empty($areaName)) $this->debugMessages[] = 'App_Formitive_Input_ChiroGuy > stream_html_pain_select() recieved empty argument $areaName!';
        
        set_http_status(200);
        echo '<html>';
        echo '<head><title>Select your type of pain</title></head>';
        echo '  <body>';
        echo '      <form method="POST" action="/intake/input/ChiroGuy/'. $this->fieldname .'">';
        echo '          <input type="hidden" name="'. self::PREFIX . $this->fieldname .'_area' .'" value="'. $areaName .'">';
        echo '          <p>&nbsp;</p>';
        echo '          <p>Type of Pain: <select size="1" name="'. self::PREFIX . $this->fieldname .'_pain' .'">';
        foreach ($this->validPains as $pain)
        {
            $description = ucwords(str_replace('_', ' ', $pain));
            echo "      <option value=\"$pain\">$description</option>";
        }
        echo '          </select>';
        echo '          <p><input type="submit" value="Save" name="buttonSubmit"></p>';
        echo '      </form>';
        echo '  </body>';
        echo '</html>';
        die();
    }
    
    
    protected function stream_image()
    {
        $image = $this->image_generate();
        
        if ($image)
        {
            header('Content-Type: image/png');
            header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: public');
            header('Expires: 0');
            imagepng($image);
            imagedestroy($image);
            die();
        }
        else
        {
            $this->stream_html_500();
        }
        die();
    }
}