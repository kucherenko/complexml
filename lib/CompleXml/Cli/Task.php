<?php
/**
 * CompleXml_Cli_Task
 *
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Cli_Task {

    private $_programm_name;

    private $_process_count = null;

    private $_process_name;

    private $_plock_file = null;

    private $_plocks_dir = null;

    private $_lock_file_name = null;

    protected $argv = null;
    /**
     * @var CompleXml_Cli_Getopt
     */
    public $Getopt;

    public function __construct ($argv = null, $options = null)
    {
        $this->argv = $argv;
        if (!is_null($options)){
            $this->Getopt = new CompleXml_Cli_Getopt($options);
            if (!is_null($argv)){
                $this->Getopt->parse($argv);
            }
        }
    }
    /**
     *
     * @return string
     */
    public function getPlocksDir ()
    {
        return $this->_plocks_dir;
    }
    /**
     * @param string $plocks_dir
     */
    public function setPlocksDir ($plocks_dir)
    {
        if (is_null($this->_plocks_dir)&&is_null($plocks_dir)){
            $plocks_dir = '/tmp';
        }elseif (!is_null($this->_plocks_dir)&&is_null($plocks_dir) ){
            $plocks_dir = $this->_plocks_dir;
        }
        $this->_plocks_dir = $plocks_dir;
    }

    /**
     * @return integer
     */
    public function getProcessCount ()
    {
        return $this->_process_count;
    }
    /**
     * @return string
     */
    public function getProcessName ()
    {
        return $this->_process_name;
    }
    /**
     * @return string
     */
    public function getProgrammName ()
    {
        return $this->_programm_name;
    }
    /**
     * @param integer $process_count
     */
    public function setProcessCount ($process_count)
    {
        if (is_null($process_count)&&is_null($this->_process_count)){
            $process_count = 1;
        }elseif (!is_null($this->_process_count)&&is_null($process_count)){
            return;
        }
        $this->_process_count = $process_count;
    }
    /**
     * @param string $process_name
     */
    public function setProcessName ($process_name)
    {
        $this->_process_name = $process_name;
    }
    /**
     * @param string $programm_name
     */
    public function setProgrammName ($programm_name)
    {
        if (is_null($programm_name)&&is_null($this->_process_name)){
            $ref = new ReflectionClass($this);
            $programm_name = $ref->getName();
        }elseif (is_null($programm_name)&&!is_null($this->_process_name)){
            $programm_name = $this->_programm_name;
        }
        $this->_programm_name = $programm_name;
    }

    public function start ($programm_name = null, $process_count = null, $plock_dir = null)
    {
        $this->setProgrammName($programm_name);
        $this->setProcessCount($process_count);
        $this->setPlocksDir($plock_dir);
        if (!$this->_process_count){
            return true;
        }
        for ($i = 1; $i <= $this->_process_count; $i ++) {
            $this->setProcessName($this->getProgrammName() . "_" . $i);
            $this->_lock_file_name = $this->getPlocksDir() . DIRECTORY_SEPARATOR . $this->getProcessName() . ".lock";
            $this->_plock_file = @fopen( $this->_lock_file_name, "a");
            if (! @flock($this->_plock_file, LOCK_NB | LOCK_EX)) {
                @fclose($this->_plock_file);
                continue;
            }
            CompleXml_Log::add('Process named ' . $this->getProcessName() . ' started', CompleXml_Log::INFO);
            return true;
        }
        CompleXml_Log::add('Can\'t start process, proccess already started (' . $this->getProcessName() . ')', CompleXml_Log::ERR);
        throw new CompleXml_Cli_Exception('Can\'t start process, proccess already started (' . $this->getProcessName() . ')');
    }

    public function finish ()
    {
        if (! is_null($this->_plock_file)) {
            flock($this->_plock_file, LOCK_UN);
            fclose($this->_plock_file);
            @unlink($this->_lock_file_name);
            CompleXml_Log::add('Process named ' . $this->getProcessName() . ' finished', CompleXml_Log::INFO);
            return true;
        }
    }

    public function init()
    {
        
    }
}
