<?php
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )  {   die('Direct access to this page denied');   }


class Formitive_App extends BaseApp
{
    protected $_session = null;

    
    public function _appConstruct()
    {
        if (!DEBUG_MODE) frontend_crashout('This App is currently under construction.');

        $this->session = new Zend_Session_Namespace('FORMITIVE_USERSPACE');
        if (!isset($this->_session->entryId)) $this->_session->entryId = null;
    }

    
    public function view()
    {
        global $Portal;

        
        $Entry = new Formitive_Entry($this->_session->entryId);
        if (!$Entry->get('portal')) $Entry->set_portal($Portal('id'));
        if ($Entry->pageset_count() < 1) $Entry->pageset_define($this->settings['pageset']);

        if (isset($this->arguments[0]) AND $this->arguments[0] == 'input')
        {
            $inputClass = (isset($this->arguments[1]))? 'App_Formitive_Input_'. $this->arguments[1]: null;
            $fieldName = (isset($this->arguments[2]))? $this->arguments[2]: null;

            if (!empty($inputClass) AND !empty($fieldName))
            {
                $Input = new $inputClass($fieldName);

                $Input->stream_html(array_slice($this->arguments, 3));
                die();
            }
            else
            {
                die('404 - Page Not Found');
            }
        }
        else
        {
            //=
            //= Process any _POSTED form information
            //=
            if (isset($_POST['SHAF-PAGENAME']) AND $Entry->pageset_contains($_POST['SHAF-PAGENAME']))
            {
                $Entry->update_activity($_POST['SHAF-PAGENAME']);
                $Entry->sync();
                if (empty($this->session->entryId)) $this->session->entryId = $Entry->get('id');

                $pageName = 'App_Formitive_Page_'. $_POST['SHAF-PAGENAME'];

                $Page = new $pageName($Entry);
                $Page->Sync();
                unset($Page);
            }

            //=
            //= Determine Page to Display
            //
            $pagename = (empty($this->arguments[0]))? 'Agreement': $this->arguments[0];

            if ($Entry->pageset_contains($pagename))
            {
                $pagename = "App_Formitive_Page_$pagename";
                $Page = new $pagename($Entry);

                $this->View->add_resource_file('/common/frame.css');
                $this->View->add_resource_file('/common/reset.css');
                $this->View->add_resource_file('/common/text.css');
                $this->View->add_resource_file('/common/uni-form/css/uni-form.min.css');
                $this->View->add_resource_file('/common/uni-form/css/default.uni-form.min.css');
                $this->View->add_resource_file('/common/uni-form/css/custom.css');
                $this->View->add_resource_file('/common/uni-form/js/uni-form-validation.jquery.min.js');
                $this->View->add_head_script("$(function(){ $('form.uniForm').uniform(); }); ");
                $this->View->absorb($Page->View());
                return $this->View;
            }
        }

        $this->View->set_title('Page Not Found');
        $this->View->absorb('<h2>Page Not Found!</h2>');

        return $this->View;
    }
}