<?php
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )  {   die('Direct access to this page denied');   }

//=
//= ERROR HANDLING
//=
    class WarningException              extends ErrorException {}
    class ParseException                extends ErrorException {}
    class NoticeException               extends ErrorException {}
    class CoreErrorException            extends ErrorException {}
    class CoreWarningException          extends ErrorException {}
    class CompileErrorException         extends ErrorException {}
    class CompileWarningException       extends ErrorException {}
    class UserErrorException            extends ErrorException {}
    class UserWarningException          extends ErrorException {}
    class UserNoticeException           extends ErrorException {}
    class StrictException               extends ErrorException {}
    class RecoverableErrorException     extends ErrorException {}
    class DeprecatedException           extends ErrorException {}
    class UserDeprecatedException       extends ErrorException {}

    function error_handler($code, $message, $fileName, $line, array $context)
    {
        switch ($code)
        {
            case E_ERROR:               throw new \ErrorException               ($message, $code, 0, $fileName, $line);
            case E_WARNING:             throw new \WarningException             ($message, $code, 0, $fileName, $line);
            case E_PARSE:               throw new \ParseException               ($message, $code, 0, $fileName, $line);
            case E_NOTICE:              throw new \NoticeException              ($message, $code, 0, $fileName, $line);
            case E_CORE_ERROR:          throw new \CoreErrorException           ($message, $code, 0, $fileName, $line);
            case E_CORE_WARNING:        throw new \CoreWarningException         ($message, $code, 0, $fileName, $line);
            case E_COMPILE_ERROR:       throw new \CompileErrorException        ($message, $code, 0, $fileName, $line);
            case E_COMPILE_WARNING:     throw new \CompileWarningException      ($message, $code, 0, $fileName, $line);
            case E_USER_ERROR:          throw new \UserErrorException           ($message, $code, 0, $fileName, $line);
            case E_USER_WARNING:        throw new \UserWarningException         ($message, $code, 0, $fileName, $line);
            case E_USER_NOTICE:         throw new \UserNoticeException          ($message, $code, 0, $fileName, $line);
            case E_STRICT:              throw new \StrictException              ($message, $code, 0, $fileName, $line);
            case E_RECOVERABLE_ERROR:   throw new \RecoverableErrorException    ($message, $code, 0, $fileName, $line);
            case E_DEPRECATED:          throw new \DeprecatedException          ($message, $code, 0, $fileName, $line);
            case E_USER_DEPRECATED:     throw new \UserDeprecatedException      ($message, $code, 0, $fileName, $line);
            default:                    throw new \ErrorException               ($message, $code, 0, $fileName, $line);
        }

        set_error_handler('\\' . __FUNCTION__, E_ALL);      //ZF 2 work around - issue #5462

        return true;
    }


    function exception_handler($Exception)
    {
        global $Logger;

        $Logger->err($Exception);

        $fatalErrors = array(E_ERROR, E_WARNING, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING, E_USER_ERROR, E_RECOVERABLE_ERROR);
        $FffuuException = new \FFFUUException\FFFUUException($Exception);

        if (in_array($Exception->getCode(), $fatalErrors))
        {
            if (!defined('DEBUG_MODE') OR !DEBUG_MODE)
            {
                //email report
                stream_frontendCrashout();
                die;
            }
            else
            {
                $FffuuException->stream_report();
                die;
            }
        }

        return true;
    }
