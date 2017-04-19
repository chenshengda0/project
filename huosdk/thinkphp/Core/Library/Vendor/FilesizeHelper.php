<?php

/**
 * FilesizeHelper.php UTF-8
 * @date: 2016年9月12日下午8:15:33
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : H5 2.0
 */
class FilesizeHelper
{
    private $path; // the socket to the server
    /**
     * File size in bytes
     * 
     * @var float
     * @access private
     */
    private $byteSize;
    
    /**
     * Whether we are on Windows platform or another OS (*nix and MacOS)
     * 
     * @var bool
     * @access private
     */
    private $isWindows;
    
    /**
     * Constructor
     * 
     * @access public
     * @param void
     * @return void
     */
    public function __construct() {
        // Check if we are on Windows platform
        $this->isWindows = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
    }
    
    /**
     * Gets the size of the specified file
     * 
     * @access public
     * @param string The file path
     * @param bool Whether to format the file size in KB, MB, GB, TB
     * @return mixed
     */
    public function getFileSize($file, $formatted = true) {
        
        // Set the path of the file
        $this->path = $file;
        
        if (strpos($this->path, "http") === 0) {
            $url = $this->path;
            $this->byteSize  = $this->_url_exists($url);
            if (empty($this->byteSize )) {
                return 0;
            }
        } else {
            // Check for a valid file path
            $rs = $this->__checkFilePath();
            if (!$rs) {
                return 0;
            }
            // Get the file size in bytes
            $this->byteSize = (float) $this->__getByteSize();
        }
        
        // If failed to get the file size or the file size is zero, return a blank result
        if (!$this->byteSize) {
            if (!$formatted) {
                return 0;
            }
            // Return a blank array
            $blank_size = $this->__formatFileSize();
            return array(
                0, 
                $blank_size[0], 
                $blank_size[1] 
            );
        }
        
        // Return the bytesize if no formatting is needed
        if (!$formatted) {
            return $this->byteSize;
        }
        
        // Return an array containing the file size information
        return $this->__formatFileSize();
    }
    
    /**
     * Formats the file size in KB, MB, GB, TB units
     * 
     * @access private
     * @param void
     * @return array Return arry containing the file size information
     */
    private function __formatFileSize() {
        
        // If the file size is zero return a blank result
        $_size = $this->byteSize;
        if (!$_size || $_size < 0) {
            return array(
                0, 
                '0 B', 
                array(
                    0, 
                    'B' 
                ) 
            );
        }
        
        // If the file size is smaller than 1KB
        if ($_size <= 1024) {
            return array(
                0, 
                '1 KB', 
                array(
                    1, 
                    'KB' 
                ) 
            );
        }
        
        // Set an array of all file size units
        $size_units = Array(
            'B', 
            'KB', 
            'MB', 
            'GB', 
            'TB', 
            'PB', 
            'EB' 
        );
        // Set the initial unit to Bytes
        $unit = $size_units[0];
        
        // Loop through all file size units
        for ($i = 1; ($i < count($size_units) && $_size >= 1024); $i++) {
            $_size = $_size / 1024;
            $unit = $size_units[$i];
        }
        
        // Set the number of digits after the decimal place in the resulted file size
        $round = 2;
        
        // If the file size is in KiloByte we do not need any decimal numbers
        if ($unit == 'KB') {
            $round = 0;
        }
        
        // Round the file size
        $formatted = round((float) $_size, $round);
        
        // Return the file size data
        return array(
            $this->byteSize, 
            $formatted . " " . $unit, 
            array(
                $formatted, 
                $unit 
            ) 
        );
    }
    
    /**
     * Chek if the file is exist
     * 
     * @access private
     * @param void
     * @return void
     */
    private function __checkFilePath() {
        clearstatcache();
        if (!file_exists($this->path)) {
            return false;
        }
        return true;
    }
    
    /**
     *
     * @link http://www.phpddt.com
     */
    private function _url_exists($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // 不下载
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        // 设置超时
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($ch);
        $http = curl_getinfo($ch);
        curl_close($ch);
        if ($http['http_code'] == 200) {
            return $http['download_content_length'];
        }
        return 0;
    }
    
    /**
     * Gets the size of the specified file in bytes
     * 
     * @access private
     * @param void
     * @return string The file size in bytes
     */
    private function __getByteSize() {
        
        // Try the php native filesize() function.
        $bytesize = @filesize($this->path);
        if (false !== $bytesize && $bytesize >= 0) {
            return $bytesize;
        }
        
        // If filesize() fails with larger files, try to get the size using curl module.
        $bytesize = $this->__useCurl();
        if ($bytesize) {
            return $bytesize;
        }
        
        // If curl fails to get the file size try using the php native seek function.
        $bytesize = $this->__useNativeSeek();
        if ($bytesize) {
            return $bytesize;
        }
        
        // If the native seek fails to get the file size and we are on windows try using Windows COM interface
        $bytesize = $this->__useCom();
        if ($bytesize) {
            return $bytesize;
        }
        
        // If all the above methods failed to get the file size try using external program (exec() function needed)
        $bytesize = $this->__useExec();
        if ($bytesize) {
            return $bytesize;
        }
        
        // Unable to get the file size in bytes
        throw new Exception("Unable to get the file size for the file " . $this->path . "!");
    }
    
    /**
     * Gets the file size using curl module
     * 
     * @access private
     * @param void
     * @return mixed The file size as string or false when fail or cUrl module not available
     * @see http://www.php.net/manual/en/function.filesize.php#100434
     */
    private function __useCurl() {
        
        // If the curl module is not available return false
        if (!function_exists("curl_init")) {
            return false;
        }
        
        $ch = curl_init("file://" . $this->path);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        
        if ($data !== false && preg_match('/Content-Length: (\d+)/', $data, $matches)) {
            return (string) $matches[1];
        }
    }
    
    /**
     * Gets the file size by using native fseek function
     * 
     * @access private
     * @param void
     * @return mixed The file size as string or false when fail
     * @see http://www.php.net/manual/en/function.filesize.php#79023
     * @see http://www.php.net/manual/en/function.filesize.php#102135
     */
    private function __useNativeSeek() {
        
        // This should work for large files on 64bit platforms and for small files every where
        $fp = @fopen($this->path, "rb");
        
        // If failed to open the file return false
        if (!$fp) {
            return false;
        }
        
        flock($fp, LOCK_SH);
        
        // Seeks past the end-of-file
        $res = fseek($fp, 0, SEEK_END);
        if ($res === 0) {
            // Get the current position of the file pointer
            $pos = ftell($fp);
            
            flock($fp, LOCK_UN);
            fclose($fp);
            
            // $pos will be positive int if file is <2GB
            // if is >2GB <4GB it will be negative number
            if ($pos >= 0) {
                return (string) $pos;
            } else {
                return sprintf("%u", $pos);
            }
        } else {
            flock($fp, LOCK_UN);
            fclose($fp);
            return false;
        }
    }
    
    /**
     * Gets the file size by using Windows COM interface
     * 
     * @access private
     * @param void
     * @return mixed The file size as string or false when fail or COM not available
     */
    private function __useCom() {
        if (!$this->isWindows || !class_exists("COM")) {
            return false;
        }
        
        // Use the Windows COM interface
        $fsobj = new COM('Scripting.FileSystemObject');
        
        if (dirname($this->path) == '.') {
            $this->path = ((substr(getcwd(), -1) == DIRECTORY_SEPARATOR) ? getcwd() . basename($this->path) : getcwd() . DIRECTORY_SEPARATOR . basename(
                    $this->path));
    }
    
    // Get the file data
    $f = $fsobj->GetFile($this->path);
    
    // Convert to string
    return (string) $f->Size;
}

/**
 * Gets the file size by using external program (exec needed)
 * 
 * @access private
 * @param void
 * @return mixed The file size as string or false when fail or or exec is disabled
 */
private function __useExec() {
    
    // If exeec is disable return false
    if (!function_exists("exec")) {
        return false;
    }
    
    // Escape the file path string to be used as a shell argument
    $escapedPath = escapeshellarg($this->path);
    
    // If we are on Windows
    if ($this->isWindows) {
        // Try using the NT substition modifier %~z
        $size = trim(exec("for %F in ($escapedPath) do @echo %~zF"));
    }     

    // If other OS (*nix and MacOS)
    else {
        // If the platform is not Windows, use the stat command (should work for *nix and MacOS)
        $size = trim(exec("stat -c%s $escapedPath"));
    }
    
    // If the return is not blank, not zero, and is number
    if ($size && ctype_digit($size)) {
        return (string) $size;
    }
    
    // An error has occured
    return false;
}
}